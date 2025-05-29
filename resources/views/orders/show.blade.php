<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-3 text-dark-emphasis">
            🧾 Order Details <span class="text-muted">#{{ $order->id }}</span>
        </h2>
    </x-slot>

    <div class="container py-5">
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <!-- Left: Back Button -->
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>

    @if($order->payment_status !== 'paid' && in_array(Auth::user()->usertype, ['admin', 'devadmin', 'cashier']))
        <!-- Right: Mark as Paid Button -->
        <form action="{{ route('orders.markPaid', $order) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to mark this order as paid?');">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary shadow-sm">
                <i class="bi bi-check-circle me-1"></i> Mark as Paid
            </button>
        </form>
    @endif
</div>


        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Order Summary</h5>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <div><strong>Store:</strong> {{ $order->store->name ?? 'N/A' }}</div>
                    <div><strong>Cashier:</strong> {{ $order->cashier->name ?? 'N/A' }}</div>
                    <div><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</div>
                    <div>
                        <strong>Status:</strong> 
                        <span class="badge text-bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <strong>Payment:</strong> 
                        <span class="badge text-bg-{{ $order->payment_status === 'paid' ? 'success' : 'danger' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div><strong>Total:</strong> KSH {{ number_format($order->total, 2) }}</div>
                    <div><strong>Due Date:</strong> {{ $order->due_date ?? 'N/A' }}</div>
                    <div><strong>Created At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Order Items</h5>

                @if ($order->items->isEmpty())
                    <div class="alert alert-info">No items found for this order.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
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
                                        <td>
                                            <strong>KSH {{ number_format(($item->price - $item->discount) * $item->quantity, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if ($order->payment_status === 'partially_paid')
    <div class="alert alert-warning">
        <strong>Partial Payment:</strong> KES {{ number_format($order->amount_paid, 2) }} paid.<br>
        <strong>Remaining Balance:</strong> KES {{ number_format($order->balance, 2) }}
    </div>
@endif

            </div>
        </div>
@if(session('scan_token'))
    <h5>Customer QR Code</h5>
    {!! QrCode::size(200)->generate(route('scans.lookup', session('scan_token'))) !!}
@endif

        @if($order->payment_status !== 'paid')
            <div class="mt-4 col-auto">
                <a href="{{ route('orders.showPaymentForm', $order) }}" 
                   class="btn btn-lg btn-success shadow-sm">
                    <i class="bi bi-phone me-1"></i> Pay via M-Pesa
                </a>
            </div>
        @endif
    </div>


        <style>
            .badge {
                font-size: 0.85rem;
                padding: 0.4em 0.65em;
                border-radius: 0.5rem;
            }

            .table th, .table td {
                vertical-align: middle;
            }

            .card-title {
                font-size: 1.25rem;
                border-bottom: 1px solid #eaeaea;
                padding-bottom: 0.5rem;
                margin-bottom: 1.5rem;
            }
        </style>
</x-app-layout>
