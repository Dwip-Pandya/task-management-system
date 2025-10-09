<div class="bg-light border-end vh-100 p-3" style="width: 220px;">
    <h6 class="text-secondary">Menu</h6>
    <ul class="nav flex-column">
        @php
        $role = $user->role->name ?? 'user';
        @endphp

        @if ($role === 'admin')
        <li class="nav-item"><a href="/admin/dashboard" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/admin/users" class="nav-link">Manage Users</a></li>
        <li class="nav-item"><a href="/admin/tasks" class="nav-link">Tasks</a></li>
        <li class="nav-item"><a href="/admin/projects" class="nav-link">Projects</a></li>
        <li class="nav-item"><a href="/admin/reports" class="nav-link">Reports</a></li>
        <li class="nav-item"><a href="/admin/calendar" class="nav-link">Calendar</a></li>

        @elseif ($role === 'project manager')
        <li class="nav-item"><a href="/projectmanager/dashboard" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/projectmanager/projects" class="nav-link">Projects</a></li>
        <!-- <li class="nav-item"><a href="/projectmanager/tasks" class="nav-link">Tasks</a></li> -->
        <!-- <li class="nav-item"><a href="/projectmanager/calendar" class="nav-link">Calendar</a></li> -->

        @elseif ($role === 'project member')
        <li class="nav-item"><a href="/projectmember/dashboard" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/projectmember/tasks" class="nav-link">Tasks</a></li>
        <!-- <li class="nav-item"><a href="/projectmember/calendar" class="nav-link">Calendar</a></li> -->

        @else
        <li class="nav-item"><a href="/user/dashboard" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/user/tasks" class="nav-link">My Tasks</a></li>
        <li class="nav-item"><a href="/user/calendar" class="nav-link">Calendar</a></li>
        @endif
    </ul>
</div>