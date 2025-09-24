<!DOCTYPE html>
<html>

<head>
    <title>Manage Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Tasks</h3>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Create Task</a>
            </div>

            <form method="GET" class="row g-2 mb-4">
                <form method="GET" class="row g-2 mb-4">
                    {{-- User Select --}}
                    @if($user->role === 'admin')
                    <div class="col-md-2">
                        <select name="assigned_to" class="form-select">
                            <option class="text-dark" value="">All Users</option>
                            @foreach($usersList as $u)
                            <option class="text-dark" value="{{ $u->user_id }}" {{ $request->assigned_to == $u->user_id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="assigned_to" value="{{ $request->assigned_to ?? $user->user_id }}">
                    @endif

                    {{-- Status --}}
                    <div class="col-md-2">
                        <select name="status_id" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $s)
                            <option class="text-dark" value="{{ $s->status_id }}" {{ $request->status_id == $s->status_id ? 'selected' : '' }}>
                                {{ ucfirst($s->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Priority --}}
                    <div class="col-md-2">
                        <select name="priority_id" class="form-select">
                            <option value="">All Priorities</option>
                            @foreach($priorities as $p)
                            <option class="text-dark" value="{{ $p->priority_id }}" {{ $request->priority_id == $p->priority_id ? 'selected' : '' }}>
                                {{ ucfirst($p->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Project Filter --}}
                    <div class="col-md-2">
                        <select name="project_id" class="form-select">
                            <option class="text-dark">All Projects</option>
                            @foreach($projects as $p)
                            <option class="text-dark" value="{{ $p->project_id }}" {{ $request->project_id == $p->project_id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-2">
                        <input type="date" name="due_date" class="form-control" value="{{ $request->due_date ?? '' }}">
                    </div>

                    <div class="col-md-2 mt-2">
                        <button type="submit" class="btn btn-primary w-100 filter">Filter</button>
                    </div>
                </form>


                <div class="row">
                    @foreach ($tasks as $t)
                    @php
                    // Determine card border color based on status
                    $borderClass = match($t->status_name) {
                    'pending' => 'border-warning',
                    'in_progress' => 'border-primary',
                    'completed' => 'border-success',
                    'on_hold' => 'border-secondary',
                    default => 'border-dark',
                    };
                    $badgeClass = match($t->status_name) {
                    'pending' => 'bg-warning text-dark',
                    'in_progress' => 'bg-primary',
                    'completed' => 'bg-success',
                    'on_hold' => 'bg-secondary',
                    default => 'bg-dark',
                    };
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card shadow {{ $borderClass }}">
                            <div class="card-body">
                                {{-- Project Capsule --}}
                                @if($t->project_name)
                                <span class="badge bg-info text-dark mb-2">{{ $t->project_name }}</span>
                                @endif
                                <h5 class="card-title">{{ $t->title }}</h5>
                                <span class="badge {{ $badgeClass }} mb-2">{{ ucfirst($t->status_name) }}</span>
                                <p class="card-text">{{ Str::limit($t->description, 100) }}</p>

                                <!-- Assigned User -->
                                <p><strong>Assigned To:</strong>
                                    <select class="form-select form-select-sm change-assigned"
                                        data-id="{{ $t->task_id }}">
                                        <option class="text-dark" value="">Unassigned</option>
                                        @foreach (DB::table('tbl_user')->get() as $u)
                                        <option class="text-dark" value="{{ $u->user_id }}"
                                            {{ $u->user_id == $t->assigned_to ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </p>

                                <!-- Status -->
                                <p><strong>Status:</strong>
                                    <select class="form-select form-select-sm change-status"
                                        data-id="{{ $t->task_id }}">
                                        @foreach (DB::table('statuses')->get() as $s)
                                        <option class="text-dark" value="{{ $s->status_id }}"
                                            {{ $s->status_id == $t->status_id ? 'selected' : '' }}>
                                            {{ ucfirst($s->name) }}
                                        </option>
                                        @endforeach
                                    </select>

                                </p>

                                <!-- Priority -->
                                <p><strong>Priority:</strong>
                                    <select class="form-select form-select-sm change-priority"
                                        data-id="{{ $t->task_id }}">
                                        @foreach (DB::table('priorities')->get() as $p)
                                        <option class="text-dark" value="{{ $p->priority_id }}"
                                            {{ $p->priority_id == $t->priority_id ? 'selected' : '' }}>
                                            {{ ucfirst($p->name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </p>

                                <p class="card-text"><strong>Due Date:</strong> {{ $t->due_date }}</p>
                            </div>

                            <div class="card-footer d-flex">
                                <a href="{{ route('tasks.show', $t->task_id) }}" class="btn btn-info btn-sm">Details</a>&nbsp;
                                <a href="{{ route('tasks.show', $t->task_id) }}" class="btn btn-light btn-sm">comment</a>&nbsp;
                                <a href="{{ route('tasks.edit', $t->task_id) }}" class="btn btn-warning btn-sm">Edit</a>&nbsp;
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
        </div>
    </div>

    @include('partials.footer')

    <script>
        // AJAX for Assigned User
        $('.change-assigned').change(function() {
            let id = $(this).data('id');
            let assigned_to = $(this).val();
            $.post("{{ url('/tasks') }}/" + id + "/update-assigned", {
                _token: "{{ csrf_token() }}",
                assigned_to: assigned_to
            });
        });

        // AJAX for Status
        $('.change-status').change(function() {
            let id = $(this).data('id');
            let status_id = $(this).val();
            $.post("{{ url('/tasks') }}/" + id + "/update-status", {
                _token: "{{ csrf_token() }}",
                status_id: status_id
            });
        });

        // AJAX for Priority
        $('.change-priority').change(function() {
            let id = $(this).data('id');
            let priority_id = $(this).val();
            $.post("{{ url('/tasks') }}/" + id + "/update-priority", {
                _token: "{{ csrf_token() }}",
                priority_id: priority_id
            });
        });
    </script>

</body>

</html>