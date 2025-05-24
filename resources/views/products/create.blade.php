<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Create Product') }}
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
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="store_id" class="form-label">Store</label>
                                <select name="store_id" id="store_id" class="form-select" required>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                            </div>


                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="form-control" placeholder=" code assigned to a specific product to help track inventory, manage sales, and identify product variations. " required>
                            </div>

                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="price" class="form-label">Price (KSH)</label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="stock_qty" class="form-label">Stock Quantity</label>
                                <input type="number" name="stock_qty" id="stock_qty" value="{{ old('stock_qty') }}" class="form-control">
                            </div>

                                <!-- Product Image -->
                                 <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Create Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
