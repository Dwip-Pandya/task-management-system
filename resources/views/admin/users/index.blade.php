@extends('layouts.main')

@section('title', 'Manage Users')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/role.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>User Management</h2>
    @include('partials.Breadcrumbs')
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Create User</a>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="{{ $search ?? '' }}">
        <button class="btn btn-outline-primary">Search</button>
    </form>

    @include('partials.flash-messages')

    {{-- Users Table --}}
    <form method="POST" action="{{ route('users.bulkAction') }}">
        @csrf
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                <tr>
                    <td>
                        <input type="checkbox" name="user_ids[]" value="{{ $u->id }}">
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ ucfirst($u->role->name ?? 'N/A') }}</td>
                    <td>
                        @if($u->trashed())
                        <span class="badge alert-danger">Deleted</span>
                        @else
                        <span class="badge alert-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info swal-switch-user"
                            data-url="{{ url('admin/users/' . $u->id . '/switch') }}">
                            Switch to user
                        </button>
                        <a href="#"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#rolePermissionOffcanvas-{{ $u->id }}">
                            Update Role Permission
                        </a>
                        @if(!$u->trashed())
                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @if($u->id !== $user->id)
                        <button type="button" class="btn btn-sm btn-danger swal-delete-user"
                            data-userid="{{ $u->id }}">
                            Delete
                        </button>
                        @endif
                        @else
                        <form action="{{ route('users.restore', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button"
                                class="btn btn-sm btn-success swal-restore-user"
                                data-userid="{{ $u->id }}">
                                Restore
                            </button>
                        </form>
                        @endif
                        @include('partials.role-permission', [
                        'user' => $u,
                        'modules' => $modules,
                        'rolePermissions' => $rolePermissions,
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <button type="button" class="btn btn-danger mt-2 deleted-user swal-bulk-delete">
            Bulk Action
        </button>

    </form>
</div>
</div>
<script>
    document.getElementById('select-all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
        checkboxes.forEach(cb => cb.checked = event.target.checked);
    });
</script>
@endsection