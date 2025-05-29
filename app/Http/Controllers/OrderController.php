<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class OrderController extends Controller
{

public function index(Request $request)
{
    $user = Auth::user();
    $filter = $request->get('filter'); // e.g., 'my' or 'all'
    $cashierId = $request->get('user_id'); // New filter for admin

    if ($user->usertype === 'user') {
        // Regular users see only their orders
        $orders = Order::with(['items', 'cashier', 'customer'])
            ->where('customer_id', $user->id)
            ->get();
    } elseif ($user->usertype === 'cashier') {
        $query = Order::with(['items', 'cashier', 'customer'])
            ->where('store_id', $user->store_id);

        // If filter is set to 'my', show only the cashier's own orders
        if ($filter === 'my') {
            $query->where('user_id', $user->id);
        }

        $orders = $query->get();
    } elseif ($user->usertype === 'admin') {
        $query = Order::with(['items', 'cashier', 'customer', 'store'])
            ->where('store_id', $user->store_id);

        // If a cashier is selected, filter by that cashier
        if ($cashierId) {
            $query->where('user_id', $cashierId);
        } else {
            // Otherwise, order by newest first
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->get();

    } elseif (in_array($user->usertype, ['devadmin'])) {
        // Devadmins see all orders, grouped by store
        $orders = Order::with(['items', 'cashier', 'customer', 'store'])->get();
    } else {
        // Default: no access
        $orders = collect();
    }

    // For admin, also pass list of cashiers of the store for the dropdown
    $cashiers = null;
    if ($user->usertype === 'admin') {
        $cashiers = \App\Models\User::where('store_id', $user->store_id)
            ->where('usertype', 'cashier')
            ->get();
    }

    return view('orders.index', compact('orders', 'cashiers', 'cashierId'));
}


public function edit(Order $order)
{
    return view('orders.edit', compact('order'));
}


    public function show(Order $order) {
        $order->load(['items', 'cashier', 'customer']);
        return view('orders.show', compact('order'));
    }
    
    public function showPaymentForm(Order $order)
    {
        return view('mpesa-test', compact('order'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'user_id' => 'nullable|exists:users,id',
            'customer_id' => 'nullable|exists:users,id',
            'total' => 'numeric',
            'status' => 'in:cart,pending,paid,cancelled,refunded',
            'payment_status' => 'in:unpaid,partially_paid,paid',
            'is_draft' => 'boolean',
            'due_date' => 'nullable|date',
        ]);
        Order::create($validated);
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

public function update(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:cart,pending,paid,cancelled,refunded',
        'payment_status' => 'required|in:unpaid,partially_paid,paid',
    ]);

    $wasPreviouslyUnpaid = $order->payment_status !== 'paid' && $validated['payment_status'] === 'paid';

    DB::transaction(function () use ($order, $validated, $wasPreviouslyUnpaid) {
        $order->update($validated);

        if ($wasPreviouslyUnpaid) {
            foreach ($order->items as $item) {
                $product = $item->product;

                if ($product && $product->stock_qty >= $item->quantity) {
                    $product->decrement('stock_qty', $item->quantity);
                }
            }
        }
    });

    // Recalculate the balance AFTER update
    $order->refresh(); // Make sure we have the latest data
    $amountPaid = $order->mpesaTransactions()->sum('amount');
    $balance = $order->total - $amountPaid;

    $message = 'Order updated successfully.';
    if ($validated['payment_status'] === 'partially_paid') {
        $message .= " Remaining balance: KES " . number_format($balance, 2);
    }

    return redirect()->route('orders.show', $order)->with('success', $message);
}




    public function destroy(Order $order) {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    // Payment method for initiating M-Pesa payment
public function payment(Request $request, Order $order, MpesaService $mpesaService)
{
    $request->validate([
        'phone' => 'required|numeric|starts_with:254',
        'amount' => 'required|numeric|min:1'
    ]);

    if ($order->payment_status === 'paid') {
        return redirect()->route('orders.show', $order)
                         ->with('error', 'This order has already been paid.');
    }

    $phone = $request->input('phone');
    $amount = $request->input('amount');

    $response = $mpesaService->stkPush(
        amount: $amount,
        phone: $phone,
        accountReference: "ORDER-{$order->id}",
        transactionDesc: "Payment for Order #{$order->id}"
    );

    if ($response['success']) {
        $data = $response['data'];
        MpesaTransaction::create([
            'order_id' => $order->id,
            'merchantRequestId' => $data['MerchantRequestID'] ?? null,
            'checkoutRequestId' => $data['CheckoutRequestID'] ?? null,
            'amount' => $amount,
            'phoneNumber' => $phone,
            'transactionDate' => now(),
        ]);

        return redirect()->route('orders.show', $order)
                         ->with('success', 'STK Push initiated. Complete payment on your phone.');
    }

    return redirect()->route('orders.show', $order)
                     ->with('error', 'STK Push failed: ' . ($response['message'] ?? 'Unknown error'));
}


    // Callback method to handle M-Pesa payment confirmation
public function callback(Request $request)
{
    $data = $request->input('Body.stkCallback');

    if (!$data) {
        Log::error('Empty callback body.');
        return response()->json(['error' => 'Empty body'], 400);
    }

    Log::info('M-Pesa Callback Data:', $data);

    $checkoutRequestId = $data['CheckoutRequestID'] ?? null;
    $resultCode = $data['ResultCode'] ?? null;

    if (!$checkoutRequestId) {
        Log::error('Missing CheckoutRequestID in callback.');
        return response()->json(['error' => 'Invalid callback data'], 400);
    }

    $transaction = MpesaTransaction::where('checkoutRequestId', $checkoutRequestId)->first();

    if (!$transaction) {
        Log::error('Transaction not found for CheckoutRequestID: ' . $checkoutRequestId);
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    $items = collect($data['CallbackMetadata']['Item'] ?? []);

    $amount = $items->firstWhere('Name', 'Amount')['Value'] ?? null;
    $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
    $phone = $items->firstWhere('Name', 'PhoneNumber')['Value'] ?? null;
    $date = $items->firstWhere('Name', 'TransactionDate')['Value'] ?? now()->format('YmdHis');

    $transaction->update([
        'mpesaReceiptNumber' => $receipt,
        'phoneNumber' => $phone,
        'amount' => $amount,
        'transactionDate' => $date,
    ]);

    if ((int) $resultCode === 0) {
        if ($transaction->order) {
            $order = $transaction->order;
            $order->save();

            Log::info('✅ Order marked as paid', [
                'order_id' => $order->id,
            ]);
        } else {
            Log::warning('⚠️ Payment received but no order linked to transaction ID ' . $transaction->id);
        }
    } else {
        Log::warning('❌ M-Pesa STK Push failed. ResultCode: ' . $resultCode);
    }

    return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success'], 200);
}

public function markAsPaid(Order $order)
{
    if ($order->payment_status === 'paid') {
        return redirect()->route('orders.index')->with('error', 'Order is already marked as paid.');
    }

    // First, validate stock levels for all items
    foreach ($order->items as $item) {
        $product = $item->product;

        if (!$product) {
            return redirect()->route('orders.index')->with('error', "Product not found for item ID {$item->id}.");
        }

        if ($product->stock_qty < $item->quantity) {
            return redirect()->route('orders.index')->with('error', "Insufficient stock for product: {$product->name}. Required: {$item->quantity}, Available: {$product->stock_qty}");
        }
    }

    // Proceed with payment status update and stock deduction
    DB::transaction(function () use ($order) {
        $order->update([
            'payment_status' => 'paid',
        ]);

        foreach ($order->items as $item) {
            $product = $item->product;

            if ($product) {
                $product->decrement('stock_qty', $item->quantity);
            }
        }
    });

    return redirect()->route('orders.index')->with('success', "Order #{$order->id} marked as paid and stock updated.");
}


public function export(Request $request)
{
    $filter = $request->input('filter', 'all');

    switch ($filter) {
        case 'today':
            $startDate = Carbon::now()->startOfDay()->toDateTimeString();
            $endDate = Carbon::now()->endOfDay()->toDateTimeString();
            break;
        case 'week':
            $startDate = Carbon::now()->startOfWeek()->toDateTimeString();
            $endDate = Carbon::now()->endOfWeek()->toDateTimeString();
            break;
        case 'month':
            $startDate = Carbon::now()->startOfMonth()->toDateTimeString();
            $endDate = Carbon::now()->endOfMonth()->toDateTimeString();
            break;
        case '3months':
            $startDate = Carbon::now()->subMonths(3)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::now()->endOfMonth()->toDateTimeString();
            break;
        case 'year':
            $startDate = Carbon::now()->startOfYear()->toDateTimeString();
            $endDate = Carbon::now()->endOfYear()->toDateTimeString();
            break;
        case 'custom':
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            break;
        default:
            $startDate = null;
            $endDate = null;
            break;
    }

    return Excel::download(new OrdersExport($startDate, $endDate), 'orders.xlsx');
}

}