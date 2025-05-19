<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">M-Pesa STK Push Test</h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('orders.payment', $order->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number (e.g. 254712345678)</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount (KES)</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Pay Now</button>
        </form>
    </div>
</x-app-layout>
