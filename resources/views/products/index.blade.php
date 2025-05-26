<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    {{-- Custom Styles --}}
    <style>
        /* Your existing styles here... */
        .product-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
        }

        .product-card img {
            max-height: 180px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.01);
        }

        .form-label {
            font-size: 0.85rem;
            color: #555;
        }

        h4.text-primary {
            border-left: 4px solid #3b82f6;
            padding-left: 10px;
            font-size: 1.25rem;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        .swiper-container {
            padding-bottom: 30px;
            position: relative; /* Important for nav positioning */
        }

        /* Navigation button styles */
        .swiper-button-next,
        .swiper-button-prev {
            color: #0d6efd;
            position: absolute;
            top: 50%;
            width: 44px;
            height: 44px;
            margin-top: -22px;
            z-index: 10;
            cursor: pointer;
            user-select: none;
        }

        .swiper-button-next {
            right: 10px;
        }

        .swiper-button-prev {
            left: 10px;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            color: #0a58ca;
        }

        /* Hide navigation on small screens */
        @media (max-width: 768px) {
            .swiper-button-next,
            .swiper-button-prev {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .sticky-filter-btn {
                position: sticky;
                top: 70px;
                z-index: 1030;
                background: #fff;
                padding: 0.5rem 1rem;
                border-bottom: 1px solid #eee;
            }
        }

        /* Show filter always on md+ */
        @media (min-width: 768px) {
            #filterFormCollapse {
                display: block !important;
                visibility: visible !important;
                height: auto !important;
                overflow: visible !important;
            }
        }
    </style>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="fw-bold fs-3 text-primary-emphasis mb-0">
                {{ __('🛍️ Our Products') }}
            </h2>
            @if (in_array(Auth::user()->usertype, ['admin', 'devadmin']))
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-light shadow-sm px-4 py-2">
                    <i class="bi bi-plus-circle me-1"></i> Create Product
                </a>
            @endif
        </div>
    </x-slot>

    @php $user = Auth::user(); @endphp

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Mobile Filter Toggle --}}
        <div class="d-md-none mb-3 text-end">
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterFormCollapse" aria-expanded="false" aria-controls="filterFormCollapse">
                <i class="bi bi-sliders"></i> Filters
            </button>
        </div>

        {{-- Filter Form (responsive) --}}
        <div class="collapse" id="filterFormCollapse">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="row g-3 align-items-end">
                            {{-- Category --}}
                            <div class="col-sm-6 col-md-3">
                                <label for="category" class="form-label fw-semibold">🗂️ Category</label>
                                <select name="category" id="category" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Search --}}
                            <div class="col-sm-6 col-md-3">
                                <label for="search" class="form-label fw-semibold">🔍 Search</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Product name..." value="{{ request('search') }}">
                            </div>

                            {{-- Min Price --}}
                            <div class="col-6 col-md-2">
                                <label for="min_price" class="form-label fw-semibold">💰 Min</label>
                                <input type="number" name="min_price" id="min_price" class="form-control form-control-sm" placeholder="0.00" value="{{ request('min_price') }}" min="0" step="0.01">
                            </div>

                            {{-- Max Price --}}
                            <div class="col-6 col-md-2">
                                <label for="max_price" class="form-label fw-semibold">💸 Max</label>
                                <input type="number" name="max_price" id="max_price" class="form-control form-control-sm" placeholder="0.00" value="{{ request('max_price') }}" min="0" step="0.01">
                            </div>

                            {{-- In Stock --}}
                            <div class="col-6 col-md-1 text-start text-md-center">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label small" for="in_stock">In Stock</label>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-6 col-md-1">
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-md-4">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Product Grid --}}
        @foreach ($products as $categoryId => $categoryProducts)
            @php
                $category = $categories->firstWhere('id', $categoryId);
            @endphp

            <div class="mb-5">
                <h4 class="fw-bold text-primary mb-3">{{ $category->name }}</h4>

                <div class="swiper-container swiper-{{ $categoryId }}">
                    <div class="swiper-wrapper">
                        @foreach ($categoryProducts as $product)
                            <div class="swiper-slide" style="width: 300px;">
                                <div class="card h-100 border-0 shadow-lg rounded-4 product-card">
                                    <!-- Product Image with Overlay Buttons -->
                                    <div class="position-relative text-center p-3" style="background: #f8f9fa;">
                                        <!-- Overlay Buttons -->
                                        <div class="position-absolute top-0 end-0 m-2 d-flex gap-1 z-2">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info rounded-circle" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </div>
                                        <!-- Product Image -->
                                        <img src="{{ $product->images->first()->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="img-fluid rounded-3 object-fit-contain" style="max-height: 180px;" loading="lazy">
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
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}" class="form-control form-control-sm w-25 text-center shadow-sm" aria-label="Quantity">
                                            <button class="btn btn-success btn-sm shadow-sm">
                                                <i class="bi bi-cart-plus me-1"></i> Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add navigation buttons -->
                    <div class="swiper-button-next swiper-button-next-{{ $categoryId }}"></div>
                    <div class="swiper-button-prev swiper-button-prev-{{ $categoryId }}"></div>
                </div>
            </div>

            <script>
                new Swiper('.swiper-{{ $categoryId }}', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.swiper-button-next-{{ $categoryId }}',
                        prevEl: '.swiper-button-prev-{{ $categoryId }}',
                    },
                    autoplay: {
                        delay: 3000, // time in milliseconds
                        disableOnInteraction: false // keep autoplay even after user interaction
                    },
                    breakpoints: {
                        0: { slidesPerView: 2 },
                        768: { slidesPerView: 2 },
                        992: { slidesPerView: 3 },
                        1200: { slidesPerView: 4 },
                    },
                    loop: true // Optional: loop slides
                });
            </script>
        @endforeach
    </div>

    {{-- JS to auto-hide filter on mobile submit --}}
    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                const collapse = document.getElementById('filterFormCollapse');

                if (window.innerWidth < 768 && collapse && collapse.classList.contains('show')) {
                    e.preventDefault(); // Stop default submit temporarily
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                    bsCollapse.hide();

                    // Submit after collapse animation
                    setTimeout(() => {
                        form.submit();
                    }, 400);
                }
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
</x-app-layout>
