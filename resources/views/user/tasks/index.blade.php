<!DOCTYPE html>
<html>

<head>
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="container mt-3">
            <h3>My Tasks</h3>

            <form method="GET" class="row g-2 mb-4">
                {{-- Status --}}
                <div class="col-md-3">
                    <select name="status_id" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $s)
                        <option value="{{ $s->status_id }}" {{ $request->status_id == $s->status_id ? 'selected' : '' }}>
                            {{ ucfirst($s->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Priority --}}
                <div class="col-md-3">
                    <select name="priority_id" class="form-select">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $p)
                        <option value="{{ $p->priority_id }}" {{ $request->priority_id == $p->priority_id ? 'selected' : '' }}>
                            {{ ucfirst($p->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Due Date --}}
                <div class="col-md-3">
                    <input type="date" name="due_date" class="form-control" value="{{ $request->due_date ?? '' }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="row">
                @foreach($tasks as $t)
                <div class="col-md-4 mb-3">
                    <div class="card shadow border-info">
                        <div class="card-body">
                            <h5>{{ $t->title }}</h5>
                            <span class="badge bg-primary">{{ ucfirst($t->status_name) }}</span>
                            <p>{{ Str::limit($t->description, 80) }}</p>
                            <p><strong>Priority:</strong> {{ ucfirst($t->priority_name) }}</p>
                            <p><strong>Due:</strong> {{ $t->due_date }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('user.tasks.show', $t->task_id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('user.tasks.show', $t->task_id) }}" class="btn btn-light btn-sm">Comment</a>
                            <a href="{{ route('user.tasks.edit', $t->task_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('user.tasks.destroy', $t->task_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this task?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>