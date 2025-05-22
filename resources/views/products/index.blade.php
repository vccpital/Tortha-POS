<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Create Product</a>
        </div>

        <div class="table-responsive shadow-sm bg-white rounded p-3">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Store</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->store->name ?? 'N/A' }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>{{ $product->category ?? '-' }}</td>
                            <td>KSH {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock_qty }}</td>
                            <td>
                                @foreach ($product->images as $image)
                               <img src="{{ $image->image_url }}" alt="Product Image" width="50" class="rounded me-1 mb-1">
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info mb-1">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger mb-1">Delete</button>
                                </form>
                                    <!-- Add to Cart -->
    <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block mt-2">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}"
               class="form-control mb-1" style="width: 70px; display: inline-block;">
        <button class="btn btn-sm btn-success">Add to Cart</button>
    </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
