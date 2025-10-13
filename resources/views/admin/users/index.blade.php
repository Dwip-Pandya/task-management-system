@extends('layouts.main')

@section('title', 'Manage Users')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>User Management</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Create User</a>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="{{ $search ?? '' }}">
        <button class="btn btn-outline-primary">Search</button>
    </form>

    @include('partials.flash-messages')

    {{-- Users --}}
    <form method="POST" action="{{ route('users.bulkDelete') }}">
        @csrf
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
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
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="deleteUser('{{ $u->id }}')">
                            Delete
                        </button>
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

        <button type="submit" class="btn btn-danger mt-2"
            onclick="return confirm('Are you sure you want to delete selected users?')">Bulk Delete</button>
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
<script>
    document.getElementById('select-all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
        checkboxes.forEach(cb => cb.checked = event.target.checked);
    });

    function deleteUser(userId) {
        if (!confirm('Delete this user?')) return;

        // Create a form dynamically
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/' + userId; // Your destroy route

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection