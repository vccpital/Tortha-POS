<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-3 text-dark-emphasis">
            {{ __('🛍️ Our Products') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('products.create') }}" class="btn btn-gradient-primary shadow-sm px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i> Create Product
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse ($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-lg rounded-4 position-relative product-card">
                        <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info rounded-circle" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>

                        <div class="card-img-top text-center p-3" style="background: #f8f9fa;">
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                 class="img-fluid rounded-3 object-fit-contain"
                                 style="max-height: 180px;" loading="lazy">
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-semibold text-dark">{{ $product->name }}</h5>
                                <p class="text-muted mb-1">KSH {{ number_format($product->price, 2) }}</p>
                                <small class="text-secondary">Stock: {{ $product->stock_qty }}</small>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST" class="mt-3 d-flex align-items-center gap-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="number" name="quantity" value="1" min="1"
                                       max="{{ $product->stock_qty }}"
                                       class="form-control form-control-sm w-25 text-center shadow-sm"
                                       aria-label="Quantity">
                                <button class="btn btn-success btn-sm shadow-sm">
                                    <i class="bi bi-cart-plus me-1"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-4 rounded-3 fs-5 shadow-sm">
                        No products available. Start by adding your first product!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('styles')
        <style>
            .btn-gradient-primary {
                background: linear-gradient(135deg, #4f46e5, #3b82f6);
                color: #fff;
                transition: all 0.3s ease-in-out;
            }

            .btn-gradient-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            }

            .product-card:hover {
                transform: translateY(-5px);
                transition: all 0.3s ease;
            }

            .product-card img {
                transition: transform 0.3s ease;
            }

            .product-card:hover img {
                transform: scale(1.05);
            }
        </style>
    @endpush
</x-app-layout>
