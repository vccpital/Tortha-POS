<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

public function index(Request $request)
{
    $user = Auth::user();
    $filter = $request->get('filter'); // e.g., 'my' or 'all'

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
            $query->where('cashier_id', $user->id);
        }

        $orders = $query->get();
    } elseif (in_array($user->usertype, ['admin', 'devadmin'])) {
        // Admins and devadmins see all orders
        $orders = Order::with(['items', 'cashier', 'customer', 'store'])->get();
    } else {
        // Default: no access
        $orders = collect();
    }

    return view('orders.index', compact('orders'));
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

    public function update(Request $request, Order $order) {
        $order->update($request->all());
        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
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
            $transaction->order->update([
                'payment_status' => 'paid',
                'status' => 'paid',
            ]);
            Log::info('Payment successful for order #' . $transaction->order->id);
        } else {
            Log::warning('Payment received, but no order linked.');
        }
    } else {
        Log::warning('M-Pesa STK Push failed. ResultCode: ' . $resultCode);
    }

    return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success'], 200);
}
}