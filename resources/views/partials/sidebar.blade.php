<div class="bg-light border-end vh-100 p-3" style="width: 220px;">
    <h6 class="text-secondary">Menu</h6>
    <ul class="nav flex-column">
        @if ($user->role === 'admin')
        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
            <a href="/admin/users" class="nav-link">Manage Users</a>
        </li>
        <li class="nav-item">
            <a href="/admin/tasks" class="nav-link">Tasks</a>
        </li>
        <li class="nav-item">
            <a href="/admin/projects" class="nav-link">Projects</a>
        </li>
        <li class="nav-item">
            <a href="/admin/reports" class="nav-link">Reports</a>
        </li>
        <li class="nav-item">
            <a href="/admin/calendar" class="nav-link">Calendar</a>
        </li>
        @else
        <li class="nav-item">
            <a href="/user/dashboard" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
            <a href="/user/tasks" class="nav-link">My Tasks</a>
        </li>
        <li class="nav-item">
            <a href="/user/calendar" class="nav-link">Calendar</a>
        </li>

        @endif
    </ul>
</div>