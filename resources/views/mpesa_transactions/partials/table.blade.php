<table class="table table-striped table-hover mb-0 align-middle">
    <thead class="table-light text-primary fw-semibold">
        <tr>
            <th>ID</th>
            <th>Order ID</th>
            <th>Cashier/Store</th>
            <th>Customer</th>
            <th>Amount</th>
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
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                <td class="text-end">
                    <div class="btn-group" role="group" aria-label="Transaction actions">
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
