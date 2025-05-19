<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Orders</h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">Create New Order</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($orders->isEmpty())
                    <p>No orders found.</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Store</th>
                                <th>Cashier</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->store->name ?? 'N/A' }}</td>
                                    <td>{{ $order->cashier->name ?? 'N/A' }}</td>
                                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                    <td>KSH {{ number_format($order->total, 2) }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>{{ ucfirst($order->payment_status) }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</button>
                                        </form>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
