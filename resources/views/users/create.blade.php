<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-5 d-flex justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('users.store') }}">
                                @csrf

                                <input type="hidden" name="status" value="active">

                                <div class="mb-4 text-center">
                                    <h5 class="text-success fw-bold">Add New User</h5>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="usertype" class="form-label">User Type</label>
                                    <select name="usertype" id="usertype" class="form-select" required>
                                        <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="cashier" {{ old('usertype') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                        <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="devadmin" {{ old('usertype') == 'devadmin' ? 'selected' : '' }}>Dev Admin</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="store-id-field">
                                    <label for="store_id" class="form-label">Select Store</label>
                                    <select name="store_id" id="store_id" class="form-select">
                                        <option value="">-- Choose Store --</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                </div>
                            </form>

                            <script>
                                const usertypeSelect = document.getElementById('usertype');
                                const storeField = document.getElementById('store-id-field');

                                function toggleStoreField() {
                                    const type = usertypeSelect.value;
                                    storeField.style.display = (type === 'cashier' || type === 'admin') ? 'block' : 'none';
                                }

                                usertypeSelect.addEventListener('change', toggleStoreField);
                                document.addEventListener('DOMContentLoaded', toggleStoreField);
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
