{{-- resources/views/mpesa_transactions/partials/table.blade.php --}}
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>Cashier/Store</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->order->id ?? 'N/A' }}</td>
                <td>{{ $transaction->order->cashier->name ?? $transaction->order->store->name ?? 'N/A' }}</td>
                <td>{{ $transaction->order->customer->name ?? 'N/A' }}</td>
                <td>KSH {{ number_format($transaction->amount, 2) }}</td>
                <td>{{ ucfirst($transaction->status) }}</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('mpesa_transactions.show', $transaction) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('mpesa_transactions.edit', $transaction) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('mpesa_transactions.destroy', $transaction) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this transaction?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
