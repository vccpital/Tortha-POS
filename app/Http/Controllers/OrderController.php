<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MpesaTransaction;
use App\Services\MpesaService;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with(['items', 'cashier', 'customer'])->get();
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
public function payment(Order $order, MpesaService $mpesaService)
{
    if ($order->payment_status === 'paid') {
        return redirect()->route('orders.show', $order)
                         ->with('error', 'This order has already been paid.');
    }

    $phone = '2547XXXXXXXX'; // Ideally from authenticated user or request

    $response = $mpesaService->stkPush(
        amount: $order->total,
        phone: $phone,
        accountReference: "ORDER-{$order->id}",
        transactionDesc: "Payment for Order #{$order->id}"
    );

    if ($response['success']) {
        // Optionally store MerchantRequestID & CheckoutRequestID now
        $data = $response['data'];
        MpesaTransaction::create([
            'order_id' => $order->id,
            'merchantRequestId' => $data['MerchantRequestID'] ?? null,
            'checkoutRequestId' => $data['CheckoutRequestID'] ?? null,
            'amount' => $order->total,
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
    $data = $request->all();

    // Log raw callback data for debugging
    Log::info('M-Pesa Callback Received:', $data);

    // Check if the necessary data exists in the callback response
    $orderId = $data['OrderID'] ?? null;

    if ($orderId) {
        $order = Order::find($orderId);

        // Check if the order exists
        if ($order) {
            // Create the MpesaTransaction record
            MpesaTransaction::create([
                'order_id' => $orderId,
                'merchantRequestId' => $data['MerchantRequestID'] ?? null,
                'checkoutRequestId' => $data['CheckoutRequestID'] ?? null,
                'mpesaReceiptNumber' => $data['MpesaReceiptNumber'] ?? null,
                'phoneNumber' => $data['PhoneNumber'] ?? null,
                'amount' => $data['Amount'] ?? 0,
                'transactionDate' => now(), // Can also be parsed from the callback if provided
            ]);

            // Check if the payment was successful (ResultCode 0)
            if (isset($data['ResultCode']) && $data['ResultCode'] == 0) {
                // Payment is successful, update order's payment status to 'paid'
                $order->payment_status = 'paid';
                $order->status = 'paid'; // Optional: You can also mark the order status as 'paid'
                $order->save();

                return redirect()->route('orders.show', $order)
                                 ->with('success', 'Payment confirmed and order marked as paid.');
            } else {
                // Payment failed, log the error and update the order status accordingly
                Log::error('M-Pesa Payment Failed', ['data' => $data]);
                return redirect()->route('orders.show', $order)
                                 ->with('error', 'Payment failed or was not successful.');
            }
        }

        return redirect()->route('orders.index')->with('error', 'Order not found.');
    }

    return redirect()->route('orders.index')->with('error', 'Invalid payment callback data.');
}

}
