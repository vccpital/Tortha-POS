<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-semibold text-dark">M-Pesa Transactions</h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($transactions->isEmpty())
                    <p>No transactions found.</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Receipt Number</th>
                                <th>Phone Number</th>
                                <th>Amount</th>
                                <th>Transaction Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->order_id }}</td>
                                    <td>{{ $transaction->mpesaReceiptNumber }}</td>
                                    <td>{{ $transaction->phoneNumber }}</td>
                                    <td>KSH {{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->transactionDate->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if ($transaction->order)
                                            <a href="{{ route('orders.show', $transaction->order_id) }}" class="btn btn-sm btn-info">View Order</a>
                                        @else
                                            <span class="text-muted">No Order</span>
                                        @endif
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
