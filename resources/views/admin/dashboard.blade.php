<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">


    {{-- Header --}}
    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        {{-- Sidebar --}}
        @include('partials.sidebar', ['user' => $user])

        {{-- Main Content --}}
        <div class="flex-grow-1" id="dashboard-main">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>{{ $user->role === 'admin' ? 'Admin Dashboard' : 'Dashboard' }}</h2>

                {{-- User Filter Dropdown --}}
                <form method="GET" class="d-flex">
                    <select name="assigned_to" class="form-select me-2">
                        <option value="">All Users</option>
                        @foreach($usersList as $u)
                        <option value="{{ $u->user_id }}" {{ $request->assigned_to == $u->user_id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Project Dropdown --}}
                    <select name="project_id" class="form-select me-2">
                        <option value="">All Projects</option>
                        @foreach($projectsList as $p)
                        <option value="{{ $p->project_id }}" {{ $request->project_id == $p->project_id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>

            @php
            $statuses = ['pending', 'in_progress', 'completed', 'on_hold'];
            @endphp

            <div id="horizontal-cards-container">
                @foreach($statuses as $status)
                <div class="glass-status-card" data-status="{{ $status }}">
                    <div class="glass-card-header status-{{ str_replace('_', '-', $status) }}">
                        <span class="status-title">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                        <div class="status-count">{{ count($tasksByStatus[$status]) }} tasks</div>
                    </div>

                    <div class="glass-card-body">
                        @foreach($tasksByStatus[$status] as $task)
                        <div class="task-item">
                            <p class="task-title">{{ $task->title }}</p>
                            @if($user->role === 'admin')
                            <p class="task-assigned">Assigned To: <span class="assigned-name">{{ $task->assigned_user_name ?? 'Unassigned' }}</span></p>
                            @endif
                            <a href="{{ route('tasks.index') }}" class="glass-btn">View</a>
                        </div>
                        @endforeach

                        @if($tasksByStatus[$status]->isEmpty())
                        <p class="empty-state">No tasks</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('partials.footer')

</body>


</html>