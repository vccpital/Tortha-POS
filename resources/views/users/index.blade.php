<x-app-layout>
<x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold fs-3 mb-0 text-primary-emphasis">
            <i class="bi bi-people-fill me-2"></i>Users Overview
        </h2>
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success" aria-label="Add new user">
            <i class="bi bi-person-plus me-1"></i> Add New User
        </a>
    </div>
</x-slot>

@if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'devadmin')
            <div class="card shadow-sm border-1 border-transparent py-4">
                <div class="card-body">

                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Store Info (Admin only) --}}
                    @if(Auth::user()->usertype === 'admin')
                        <p class="text-muted mb-3">
                            <strong>Viewing users from:</strong>
                            {{ auth()->user()->store ? auth()->user()->store->name : 'No store' }}
                        </p>
                    @endif

                    {{-- Search--}}
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
                        <input id="myInput" type="text" class="form-control w-100 w-md-50" placeholder="Search for any user..." aria-label="Search users">
                    </div>

                    {{-- Users Table --}}
                    <div class="table-responsive" style="max-height: 578px; overflow-y: auto;">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-primary fw-bold">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    @if(Auth::user()->usertype === 'devadmin')
                                        <th>Store</th>
                                    @endif
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable">
                                @if(Auth::user()->usertype === 'admin')
                                    @foreach ($usersSameStore as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst($user->usertype) }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group" aria-label="User actions">
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit user">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete user">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @elseif(Auth::user()->usertype === 'devadmin')
                                    @foreach ($usersByStore as $storeName => $users)
                                        <tr>
                                            <td colspan="5" class="bg-light fw-bold text-center text-secondary">
                                                {{ $storeName }}
                                            </td>
                                        </tr>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ ucfirst($user->usertype) }}</td>
                                                <td>{{ $user->store ? $user->store->name : 'No store' }}</td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group" aria-label="User actions">
                                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit user">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete user">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

    {{-- JavaScript Filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('myInput');
            input.addEventListener('keyup', function () {
                const value = this.value.toLowerCase();
                document.querySelectorAll("#usersTable tr").forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
                });
            });
        });
    </script>
@else
    <div class="alert alert-warning col-auto p-3 fw-bold m-4" role="alert">
        <i class="bi bi-shield-exclamation me-2"></i> You do not have permission to access this section.
    </div>
@endif
</x-app-layout>
