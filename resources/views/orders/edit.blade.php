<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Edit Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="container mt-5">
        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="store_id" class="form-label">Store ID</label>
                <input type="text" class="form-control" name="store_id" value="{{ $order->store_id }}" required>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" name="total" class="form-control" value="{{ $order->total }}">
            </div>

            <!-- Status dropdown -->
            <div class="mb-3">
                <label for="status" class="form-label">Order Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="cart" {{ $order->status === 'cart' ? 'selected' : '' }}>Cart</option>
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <!-- Payment status dropdown -->
            <div class="mb-3">
                <label for="payment_status" class="form-label">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-select" required>
                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partially_paid" {{ $order->payment_status === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <!-- Add other fields here as needed -->

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</x-app-layout>
