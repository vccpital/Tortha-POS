<x-app-layout>
    <div class="container mt-4">
    <h2>Create Store</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('stores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Store Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" class="form-control" id="address" rows="3" required>{{ old('address') }}</textarea>
        </div>

        <h5>Contact Info</h5>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="contact_info[phone]" class="form-control" id="phone" value="{{ old('contact_info.phone') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="contact_info[email]" class="form-control" id="email" value="{{ old('contact_info.email') }}">
        </div>

        <button type="submit" class="btn btn-primary">Create Store</button>
        <a href="{{ route('stores.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-app-layout>