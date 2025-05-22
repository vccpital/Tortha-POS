<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Stores') }}
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 py-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-shop me-2"></i>Store List
            </h5>
            <a href="{{ route('stores.create') }}" class="btn btn-sm btn-success" aria-label="Add new store" title="Add new store">
                <i class="bi bi-plus-circle me-1"></i> Add New
            </a>
        </div>

        <div class="card-body p-0">
            {{-- Session Feedback --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-0 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Store Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="text-primary fw-semibold">
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Contact Info</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td class="fw-medium">{{ $store->name }}</td>
                                <td>{{ $store->address }}</td>
                                <td>
                                    @php
                                        $contact = is_array($store->contact_info)
                                            ? $store->contact_info
                                            : json_decode($store->contact_info, true);
                                    @endphp
                                    @if($contact)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($contact as $key => $value)
                                                <li>
                                                    <span class="badge bg-secondary me-1">{{ ucfirst($key) }}:</span> {{ $value }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted fst-italic">No contact info</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="Store actions">
                                        <a href="{{ route('stores.show', $store->id) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           title="View store details"
                                           aria-label="View store">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('stores.edit', $store->id) }}"
                                           class="btn btn-outline-secondary btn-sm"
                                           title="Edit store"
                                           aria-label="Edit store">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('stores.destroy', $store->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this store?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Delete store"
                                                    aria-label="Delete store">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-1"></i> No stores found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
