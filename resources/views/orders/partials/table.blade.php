{{-- resources/views/orders/partials/table.blade.php --}}
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="d-none d-md-table-cell">ID</th>
            <th>Store</th>
            <th>Cashier</th>
            <th class="d-none d-md-table-cell">Customer</th>
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
                <td class="d-none d-md-table-cell">{{ $order->id }}</td>
                <td>{{ $order->store->name ?? 'N/A' }}</td>
                <td>{{ $order->cashier->name ?? 'N/A' }}</td>
                <td class="d-none d-md-table-cell">{{ $order->customer->name ?? 'N/A' }}</td>
                <td>KSH {{ number_format($order->total, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ ucfirst($order->payment_status) }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                    @if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'devadmin')
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                    @endif
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
