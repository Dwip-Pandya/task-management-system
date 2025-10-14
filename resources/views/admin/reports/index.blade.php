@extends('layouts.main')

@section('title', 'Reports')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/reports.css') }}">
@endpush

@section('content')
<!-- Middle Section -->
<div class="container-fluid">
    <h2 class="mb-4 mt-3">All Tasks Report</h2>
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
            <option value="{{ $u->id }}" {{ request('assigned_to') == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
            </option>
            @endforeach
        </select>

        <input type="date" name="from_date" value="{{ request('from_date') }}">
        <input type="date" name="to_date" value="{{ request('to_date') }}">

        <!-- Column Selection -->
        <div class="mt-3 mb-2">
            <strong>Select Columns:</strong><br>
            @foreach($allColumns as $key => $label)
            <label class="me-3">
                <input type="checkbox" name="columns[]" value="{{ $key }}"
                    {{ in_array($key, $selectedColumns) ? 'checked' : '' }}
                    onchange="this.form.submit()">
                {{ $label }}
            </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Reset</a>
    </form>


    <!-- Export Buttons -->
    <!-- <div class="mb-3">
        <a href="{{ route('admin.reports.export', ['format' => 'pdf'] + request()->query()) }}" class="btn btn-danger deleted-user">Export PDF</a>
        <a href="{{ route('admin.reports.export', ['format' => 'excel'] + request()->query()) }}" class="btn btn-success deleted-user">Export Excel</a>
        <a href="{{ route('admin.reports.export', ['format' => 'word'] + request()->query()) }}" class="btn btn-primary deleted-user">Export Word</a>
        <a href="{{ route('admin.reports.export', ['format' => 'ppt'] + request()->query()) }}" class="btn btn-warning deleted-user">Export PPT</a>
    </div> -->
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
                    <th>Sr No</th>
                    @foreach($selectedColumns as $col)
                    <th>{{ $allColumns[$col] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @foreach($selectedColumns as $col)
                    <td>
                        @if(in_array($col, ['created_at', 'due_date']) && $t->$col)
                        {{ \Carbon\Carbon::parse($t->$col)->format('d-m-Y') }}
                        @else
                        {{ $t->$col ?? '-' }}
                        @endif
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) + 1 }}" class="text-center text-muted">No tasks found</td>
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
@endsection