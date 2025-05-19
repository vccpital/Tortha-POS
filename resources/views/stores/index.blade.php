<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Stores</h2>
            <a href="{{ route('stores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Store
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Contact Info</th>
                        <th scope="col" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->address }}</td>
                            <td>
                                @php
                                    $contact = is_array($store->contact_info) ? $store->contact_info : json_decode($store->contact_info, true);
                                @endphp
                                @if($contact)
                                    <ul class="mb-0 ps-3">
                                        @foreach($contact as $key => $value)
                                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No contact info</em>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('stores.show', $store->id) }}"><i class="bi bi-eye me-1"></i>View</a></li>
        <li><a class="dropdown-item" href="{{ route('stores.edit', $store->id) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a></li>
        <li>
            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this store?')">
                @csrf
                @method('DELETE')
                <button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash me-1"></i>Delete</button>
            </form>
        </li>
    </ul>
</div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No stores found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
