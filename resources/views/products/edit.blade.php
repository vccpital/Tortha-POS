<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="store_id" class="form-label">Store</label>
                                <select name="store_id" id="store_id" class="form-select" required>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ $product->store_id == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price (KSH)</label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="stock_qty" class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_qty" id="stock_qty" value="{{ old('stock_qty', $product->stock_qty) }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image (Optional)</label>
                                <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                @if ($product->images->first())
                                <div class="mt-2">
                                    <strong>Current Image:</strong><br>
                                    <img src="{{ $product->images->first()->image_url }}" alt="Current Image" width="100">
                                </div>
                                @endif
                            </div>


                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
