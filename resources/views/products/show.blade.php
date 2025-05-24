<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-3 text-dark-emphasis">{{ __('📦 Product Details') }}</h2>
    </x-slot>

    <div class="container py-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $product->name }}</h4>
                <a href="{{ route('products.edit', $product) }}" 
                   class="btn btn-sm btn-outline-warning" 
                   aria-label="Edit Product">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <p><strong>Store:</strong> {{ $product->store->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Category:</strong> {{ $product->category ?? '-' }}</p><p><strong>Category:</strong> {{ $product->category ?? '-' }}</p>v>
                    <div class="col-md-6">
                        <p>
                            <strong>Price:</strong> 
                            <span class="badge bg-gradient-success fs-6">
                                KSH {{ number_format($product->price, 2) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Stock Quantity:</strong> 
                            <span class="text-{{ $product->stock_qty > 10 ? 'success' : 'danger' }}">
                                {{ $product->stock_qty }}
                            </span>
                        </p>
                    </div>
                    <div class="col-12">
    <p><strong>Description:</strong></p>
    <div class="border rounded p-3 bg-light text-dark-emphasis">
        {{ $product->description ?? 'No description provided.' }}
    </div>
</div>

                </div>

                <div class="mt-5">
                    <h6 class="fw-semibold">Images</h6>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        @forelse ($product->images as $image)
                            <img src="{{ $image->image_url }}" 
                                 alt="Product image for {{ $product->name }}" 
                                 class="rounded shadow-sm border" 
                                 style="width: 110px; height: 110px; object-fit: cover;">
                        @empty
                            <p class="text-muted">No images uploaded.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <a href="{{ route('products.index') }}" 
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

        <style>
            .bg-gradient-success {
                background: linear-gradient(135deg, #28a745, #218838);
                color: #fff;
                padding: 0.35em 0.75em;
                border-radius: 0.4rem;
            }
        </style>
</x-app-layout>
