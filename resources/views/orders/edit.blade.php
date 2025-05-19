<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Edit Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="container mt-5">
        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf
            @method('PUT')

            <!-- Example Fields -->
            <div class="mb-3">
                <label for="store_id" class="form-label">Store ID</label>
                <input type="text" class="form-control" name="store_id" value="{{ $order->store_id }}" required>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" name="total" class="form-control" value="{{ $order->total }}">
            </div>

            <!-- Add other fields here -->

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</x-app-layout>
