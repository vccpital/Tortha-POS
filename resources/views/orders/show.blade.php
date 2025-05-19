<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Order Details (ID: {{ $order->id }})</h2>
    </x-slot>

    <div class="container py-5">
        <div class="mb-3">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Order Summary</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Store:</strong> {{ $order->store->name ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Cashier:</strong> {{ $order->cashier->name ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
                    <li class="list-group-item"><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</li>
                    <li class="list-group-item"><strong>Total:</strong> KSH {{ number_format($order->total, 2) }}</li>
                    <li class="list-group-item"><strong>Due Date:</strong> {{ $order->due_date ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Created At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Order Items</h5>
                @if ($order->items->isEmpty())
                    <p>No items found for this order.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>KSH {{ number_format($item->price, 2) }}</td>
                                    <td>KSH {{ number_format($item->discount, 2) }}</td>
                                    <td>KSH {{ number_format(($item->price - $item->discount) * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
            <!-- Add Payment Button -->
             @if($order->payment_status != 'paid') 
             <!-- Only show button if order is not paid -->
              <a href="{{ route('orders.showPaymentForm', $order) }}" class="btn btn-sm btn-success">Pay via M-Pesa</a>
            @endif
    </div>
    
</x-app-layout>
