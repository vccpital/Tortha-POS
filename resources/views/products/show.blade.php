<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $product->name }}</h5>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <strong>Store:</strong> {{ $product->store->name ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>SKU:</strong> {{ $product->sku }}
                </div>
                <div class="col-md-6">
                    <strong>Barcode:</strong> {{ $product->barcode }}
                </div>
                <div class="col-md-6">
                    <strong>Category:</strong> {{ $product->category ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Price:</strong> KSH {{ number_format($product->price, 2) }}
                </div>
                <div class="col-md-6">
                    <strong>Stock Quantity:</strong> {{ $product->stock_qty }}
                </div>
                <div class="col-12 mt-4">
                    <strong>Images:</strong>
                    <div class="d-flex flex-wrap mt-2">
                        @forelse ($product->images as $image)
                            <img 
                                src="{{ $image->image_url }}" 
                                alt="Product Image" 
                                class="img-thumbnail me-2 mb-2" 
                                width="100"
                            >
                        @empty
                            <p>No images uploaded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
    </div>
</x-app-layout>
