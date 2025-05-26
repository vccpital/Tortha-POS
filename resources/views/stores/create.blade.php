<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">🏬 Create New Store</h4>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('stores.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Store Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Store Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                            </div>

                            <h5 class="mt-4 mb-3 text-primary">📞 Contact Info</h5>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="contact_info[phone]" id="phone" class="form-control" value="{{ old('contact_info.phone') }}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="contact_info[email]" id="email" class="form-control" value="{{ old('contact_info.email') }}">
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-1"></i> Create Store
                                </button>
                                <a href="{{ route('stores.index') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
