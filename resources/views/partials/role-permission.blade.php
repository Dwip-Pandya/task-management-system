@php
$offcanvasId = 'rolePermissionOffcanvas-' . $user->id;
$permissions = $rolePermissions[$user->role_id] ?? collect();
@endphp

<div class="offcanvas offcanvas-end role-permission-offcanvas" tabindex="-1" id="{{ $offcanvasId }}">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Role Permissions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <div class="user-info mb-3">
            <p><strong>User:</strong> {{ $user->name }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role->name ?? 'N/A') }}</p>
        </div>

        <form method="GET" action="{{ route('admin.users.rolePermissions', $user->id) }}">
            @csrf
            <div class="permissions-table-wrapper">
                <table class="table permissions-table table-bordered">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>View</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module)
                        @php
                        $perm = $permissions->firstWhere('module_name', $module);
                        @endphp
                        <tr>
                            <td>{{ ucfirst($module) }}</td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][can_view]" {{ $perm && $perm->can_view ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][can_add]" {{ $perm && $perm->can_add ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][can_edit]" {{ $perm && $perm->can_edit ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][can_delete]" {{ $perm && $perm->can_delete ? 'checked' : '' }}></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save Permissions</button>
        </form>
    </div>
</div>