<x-app-layout>
<x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left side: Title -->
        <h2 class="fw-bold fs-3 text-primary-emphasis">🧾 Orders</h2>

        <!-- Right side: Form inline -->
        <form action="{{ route('orders.export') }}" method="GET" class="d-flex align-items-center gap-2 mb-0">
            <select name="filter" id="filter" class="form-select form-select-sm">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="3months">Last 3 Months</option>
                <option value="year">This Year</option>
                <option value="custom">Custom Range</option>
            </select>

            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Start Date" id="custom-start" style="display: none; width: auto;">
            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="End Date" id="custom-end" style="display: none; width: auto;">

            <button type="submit" class="btn btn-success btn-sm">Download</button>
        </form>
    </div>
</x-slot>

<script>
    document.getElementById('filter').addEventListener('change', function () {
        const custom = this.value === 'custom';
        document.getElementById('custom-start').style.display = custom ? 'inline-block' : 'none';
        document.getElementById('custom-end').style.display = custom ? 'inline-block' : 'none';
    });
</script>


    <div class="border-1 py-4">
        {{-- Feedback Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Toolbar --}}
        <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            {{-- Filters (Cashier only) --}}
            @if (Auth::user()->usertype === 'cashier')
                <div class="btn-group" role="group" aria-label="Order filter">
                    <a href="{{ route('orders.index', ['filter' => 'all']) }}"
                       class="btn btn-outline-primary btn-sm {{ request('filter') !== 'my' ? 'active' : '' }}"
                       aria-label="View all store orders">
                       All Store Orders
                    </a>
                    <a href="{{ route('orders.index', ['filter' => 'my']) }}"
                       class="btn btn-outline-secondary btn-sm {{ request('filter') === 'my' ? 'active' : '' }}"
                       aria-label="View my orders only">
                       My Orders
                    </a>
                </div>
            @endif

            {{-- Role Info (Admin/Devadmin) --}}
            @if (in_array(Auth::user()->usertype, ['admin', 'devadmin']))
<div class="text-muted small d-flex align-items-center gap-2">
    <i class="bi bi-info-circle"></i>
    @if (Auth::user()->usertype === 'admin')
        <span>Viewing all orders for your store grouped by <strong>cashier</strong>.</span>
    @else
        <span>Viewing all orders grouped by <strong>store</strong>.</span>
    @endif

    @if (Auth::user()->usertype === 'admin')
        <form method="GET" action="{{ route('orders.index') }}" class="d-flex align-items-center ms-auto">
            <label for="cashier-select" class="form-label small mb-0 me-2">Filter by Cashier:</label>
            <select name="cashier_id" id="cashier-select" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Cashiers</option>
                @foreach ($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ (int) $cashierId === $cashier->id ? 'selected' : '' }}>
                        {{ $cashier->name }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif
</div>

            @endif

            {{-- Create Order --}}
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary" aria-label="Create new order">
                    <i class="bi bi-cart-plus me-1"></i> Create New Order
                </a>
            </div>
        </div>

        {{-- Orders List --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if ($orders->isEmpty())
                    <div class="text-center text-muted">
                        <i class="bi bi-folder-x fs-4 me-2"></i>No orders found.
                    </div>
                @else
                    {{-- Admin View: Grouped by Cashier --}}
                    @if (Auth::user()->usertype === 'admin')
                        @foreach ($orders->groupBy('cashier.name') as $cashierName => $cashierOrders)
                            <h5 class="fw-semibold mt-4 text-secondary">
                                <i class="bi bi-person-badge me-1"></i>{{ $cashierName ?? 'Unknown Cashier' }}
                            </h5>
                            @include('orders.partials.table', ['orders' => $cashierOrders])
                        @endforeach

                    {{-- Devadmin View: Grouped by Store --}}
                    @elseif (Auth::user()->usertype === 'devadmin')
                        @foreach ($orders->groupBy('store.name') as $storeName => $storeOrders)
                            <h5 class="fw-semibold mt-4 text-secondary">
                                <i class="bi bi-shop-window me-1"></i>{{ $storeName ?? 'Unknown Store' }}
                            </h5>
                            @include('orders.partials.table', ['orders' => $storeOrders])
                        @endforeach

                    {{-- Cashier or Other User View --}}
                    @else
                        @include('orders.partials.table', ['orders' => $orders])
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
