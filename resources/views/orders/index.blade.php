<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Orders</h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3 d-flex justify-content-between align-items-center">
            {{-- Show filters only for cashier --}}
            @if (Auth::user()->usertype === 'cashier')
                <div>
                    <a href="{{ route('orders.index', ['filter' => 'all']) }}"
                       class="btn btn-outline-primary btn-sm {{ request('filter') !== 'my' ? 'active' : '' }}">
                       All Store Orders
                    </a>
                    <a href="{{ route('orders.index', ['filter' => 'my']) }}"
                       class="btn btn-outline-secondary btn-sm {{ request('filter') === 'my' ? 'active' : '' }}">
                       My Orders
                    </a>
                </div>
            @endif

            {{-- Admin/Devadmin Information --}}
            @if (in_array(Auth::user()->usertype, ['admin', 'devadmin']))
                <div class="text-muted fw-medium">
                    @if (Auth::user()->usertype === 'admin')
                        Viewing all orders for your store, grouped by <strong>cashier</strong>
                    @elseif (Auth::user()->usertype === 'devadmin')
                        Viewing all orders grouped by <strong>store</strong>
                    @endif
                </div>
            @endif

            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Create New Order</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($orders->isEmpty())
                    <p>No orders found.</p>
                @else
                    {{-- For Admin: Show orders for their store, grouped by cashier --}}
                    @if (Auth::user()->usertype === 'admin')
                        @foreach ($orders->groupBy('cashier.name') as $cashierName => $cashierOrders)
                            <h5 class="mt-4">{{ $cashierName ?? 'Unknown Cashier' }}</h5>
                            @include('orders.partials.table', ['orders' => $cashierOrders])
                        @endforeach
                    {{-- For Devadmin: Group orders by store --}}
                    @elseif (Auth::user()->usertype === 'devadmin')
                        @foreach ($orders->groupBy('store.name') as $storeName => $storeOrders)
                            <h5 class="mt-4">{{ $storeName ?? 'Unknown Store' }}</h5>
                            @include('orders.partials.table', ['orders' => $storeOrders])
                        @endforeach
                    {{-- For other users (like cashier), show orders without grouping --}}
                    @else
                        @include('orders.partials.table', ['orders' => $orders])
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
