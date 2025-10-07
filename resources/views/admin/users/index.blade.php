<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="flex-grow-1 p-4">
            <h2>User Management</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Create User</a>

            {{-- Search Form --}}
            <form method="GET" action="{{ route('users.index') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="{{ $search ?? '' }}">
                <button class="btn btn-outline-primary">Search</button>
            </form>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Bulk delete form --}}
            <form method="POST" action="{{ route('users.bulk-delete') }}">
                @csrf
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                        <tr>
                            <td>
                                @if($u->id !== $user->id)
                                <input type="checkbox" name="user_ids[]" value="{{ $u->id }}">
                                @endif
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role->name ?? 'N/A') }}</td>
                            <td>
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @if($u->id !== $user->id)
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <button type="submit" class="btn btn-danger mt-2 mb-4" onclick="return confirm('Are you sure you want to delete selected users?')">Delete Selected</button>
            </form>

            {{-- Deleted Users --}}
            <h4 class="mt-5">Deleted Users</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deletedUsers as $deletedUser)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $deletedUser->name }}</td>
                        <td>{{ $deletedUser->email }}</td>
                        <td>{{ ucfirst($deletedUser->role->name ?? 'N/A') }}</td>
                        <td>
                            <form action="{{ route('users.restore', $deletedUser->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Restore this user?')">Restore</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No deleted users.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Select All Checkbox Script --}}
    <script>
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>

    @include('partials.footer')

</body>

</html>