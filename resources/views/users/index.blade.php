<x-app-layout>
    @if (Auth::user()->usertype === 'admin')
    <div class="py-12 d-flex">
        <div class="w-75 mx-auto sm:px-6 lg:px-8 justify-content-center">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show col-md-6" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            <div class="text-center fw-bold mb-4"><h2><strong style="color: #ee662a;">Users Overview</strong></h2></div>
            
            <!-- Displaying message about the store -->
            <div class="mb-3">
                <strong>You're viewing users from: </strong>{{ auth()->user()->store ? auth()->user()->store->name : 'No store' }}
            </div>

            <!-- Add New User Button (Sticky or Positioned) -->
                <div class="d-flex justify-content-between align-items-center mb-4 gap-2">
                    <input id="myInput" type="text" placeholder="Search for any user..." class="form-control">
                    <a href="{{ route('users.create') }}" class="btn btn-primary" style="white-space: nowrap;">Add New User</a>
                </div>

            <div style="max-height: 578px; overflow-y: auto;">
            <table class="table table-hover table-responsive bg-light">
                <thead>
                    <tr class="fw-bolder text-primary">
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    @foreach ($usersSameStore as $user)
                        <tr class="text-success fw-bold">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->usertype }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#usersTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endif


    
@if (Auth::user()->usertype === 'devadmin')
    <div class="py-12 d-flex">
        <div class="w-75 mx-auto sm:px-6 lg:px-8 justify-content-center">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show col-md-6" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-auto mt-1">Add New User</a>
            <div class="text-center fw-bold mb-4"><h2><strong style="color: #ee662a;">Users Overview</strong></h2></div>
            <div class="col-md-6 col-lg-4 mb-4">
                <input id="myInput" type="text" placeholder="Search for any user..." class="form-control">
            </div>
            <div style="max-height: 578px; overflow-y: auto;">
            <table class="table table-hover table-responsive bg-light">
                <thead>
                    <tr class="fw-bolder text-primary">
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Store</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    @foreach ($usersByStore as $storeName => $users)
                        <tr>
                            <td colspan="5" class="fw-bold text-center text-secondary">{{ $storeName }}</td>
                        </tr>
                        @foreach ($users as $user)
                            <tr class="text-success fw-bold">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->usertype }}</td>
                                <td>{{ $user->store ? $user->store->name : 'No store' }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#usersTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    @endif
    @if (!in_array(Auth::user()->usertype, ['devadmin', 'admin']))
    <div class="alert alert-warning col-auto p-2 fw-bold">
        You do not have the necessary permissions to perform this action.
    </div>
    @endif


</x-app-layout>
