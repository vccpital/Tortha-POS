<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Mpesa Transactions</h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3 d-flex justify-content-between align-items-center">
            @if (Auth::user()->usertype === 'admin')
                <div>
                    <span>Viewing transactions grouped by cashier for your store</span>
                </div>
            @elseif (Auth::user()->usertype === 'devadmin')
                <div>
                    <span>Viewing transactions grouped by store</span>
                </div>
            @endif

            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Create New Transaction</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($transactions->isEmpty())
                    <p>No transactions found.</p>
                @else
                    @if (Auth::user()->usertype === 'admin')
                        @foreach ($transactions as $cashierName => $transactionGroup)
                            <h5 class="mt-4">{{ $cashierName }}</h5>
                            @include('mpesa_transactions.partials.table', ['transactions' => $transactionGroup])
                        @endforeach
                    @elseif (Auth::user()->usertype === 'devadmin')
                        @foreach ($transactions as $storeName => $transactionGroup)
                            <h5 class="mt-4">{{ $storeName }}</h5>
                            @include('mpesa_transactions.partials.table', ['transactions' => $transactionGroup])
                        @endforeach
                    @else
                        @include('mpesa_transactions.partials.table', ['transactions' => $transactions])
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
