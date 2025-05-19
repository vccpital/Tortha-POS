<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($order && $order->items->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (KSH)</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <h4>Total: KSH {{ number_format($order->total, 2) }}</h4>

                <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline-block">
                    @csrf
                    <button class="btn btn-success">Checkout</button>
                </form>
            </div>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
</x-app-layout>
