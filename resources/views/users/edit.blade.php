<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Edit User') }}
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

                            <form method="POST" action="{{ route('users.update', $user->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-4 text-center">
                                    <h5 class="text-primary fw-bold">Update User Details</h5>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="usertype" class="form-label">User Type</label>
                                    <select name="usertype" id="usertype" class="form-select" required>
                                        <option value="user" {{ old('usertype', $user->usertype) == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="cashier" {{ old('usertype', $user->usertype) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                        <option value="admin" {{ old('usertype', $user->usertype) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="devadmin" {{ old('usertype', $user->usertype) == 'devadmin' ? 'selected' : '' }}>Dev Admin</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="store-id-field">
                                    <label for="store_id" class="form-label">Select Store</label>
                                    <select name="store_id" id="store_id" class="form-select">
                                        <option value="">-- Choose Store --</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id', $user->store_id) == $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="password" class="form-label">Password (leave blank to keep current)</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Update User</button>
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
