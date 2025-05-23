<table class="table table-striped table-hover mb-0 align-middle">
    <thead class="table-light text-primary fw-semibold">
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>Cashier/Store</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created</th>
            <th class="text-end">Actions</th>
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
                <td class="text-end">
                    <div class="btn-group" role="group" aria-label="Transaction actions">
                        <a href="{{ route('mpesa_transactions.show', $transaction) }}" class="btn btn-outline-info btn-sm" title="View transaction">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('mpesa_transactions.edit', $transaction) }}" class="btn btn-outline-warning btn-sm" title="Edit transaction">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('mpesa_transactions.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this transaction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete transaction">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
