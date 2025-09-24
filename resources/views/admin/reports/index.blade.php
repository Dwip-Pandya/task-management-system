<!DOCTYPE html>
<html>

<head>
    <title>Reports - All Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <!-- Middle Section -->
        <div class="container-fluid p-4">
            <h2 class="mb-4">All Tasks Report</h2>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.reports.index') }}" class="report-filters mb-4">
                <select name="project_id">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                    <option value="{{ $p->project_id }}" {{ request('project_id') == $p->project_id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>

                <select name="status_id">
                    <option value="">All Status</option>
                    @foreach($statuses as $s)
                    <option value="{{ $s->status_id }}" {{ request('status_id') == $s->status_id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                    @endforeach
                </select>

                <select name="priority_id">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $pr)
                    <option value="{{ $pr->priority_id }}" {{ request('priority_id') == $pr->priority_id ? 'selected' : '' }}>
                        {{ $pr->name }}
                    </option>
                    @endforeach
                </select>

                <select name="assigned_to">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                    <option value="{{ $u->user_id }}" {{ request('assigned_to') == $u->user_id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                    @endforeach
                </select>

                <input type="date" name="from_date" value="{{ request('from_date') }}">
                <input type="date" name="to_date" value="{{ request('to_date') }}">

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Reset</a>
            </form>


            <!-- Export Buttons -->
            <div class="mb-3">
                <a href="{{ route('admin.reports.export', ['format' => 'pdf'] + request()->query()) }}" class="btn btn-danger">Export PDF</a>
                <a href="{{ route('admin.reports.export', ['format' => 'excel'] + request()->query()) }}" class="btn btn-success">Export Excel</a>
                <a href="{{ route('admin.reports.export', ['format' => 'word'] + request()->query()) }}" class="btn btn-primary">Export Word</a>
                <a href="{{ route('admin.reports.export', ['format' => 'ppt'] + request()->query()) }}" class="btn btn-warning">Export PPT</a>
            </div>



            <div class="table-responsive">
                <table class="reports-table table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Tag</th>
                            <th>Assigned To</th>
                            <th>Created At</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->description }}</td>
                            <td>{{ $t->project_name ?? '-' }}</td>
                            <td>{{ $t->status_name ?? '-' }}</td>
                            <td>{{ $t->priority_name ?? '-' }}</td>
                            <td>{{ $t->tag_name ?? '-' }}</td>
                            <td>{{ $t->assigned_user_name ?? '-' }}</td>
                            <td>{{ $t->created_at ? \Carbon\Carbon::parse($t->created_at)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('d-m-Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No tasks found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination-wrapper" class="d-flex justify-content-end mt-3">
                {{ $tasks->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>