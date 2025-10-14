@extends('layouts.main')

@section('title', 'My Tasks')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container mt-3">
    <h3>My Tasks</h3>
    @include('partials.Breadcrumbs')

    <form method="GET" class="row g-2 mb-4 mt-2">
        {{-- Status --}}
        <div class="col-md-2">
            <select name="status_id" class="form-select">
                <option class="text-dark" value="">All Statuses</option>
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
                <option class="text-dark" value="">All Priorities</option>
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
                <option class="text-dark" value="">All Projects</option>
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

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <div class="total-task">
        <h6 class="text-secondary">Total Task: {{ $tasks->count() }}</h6>
    </div>

    <div class="row">
        @foreach($tasks as $t)
        <div class="col-md-4 mb-3">
            <div class="card shadow border-info">
                <div class="card-body">
                    @if($t->project_name)
                    <span class="badge bg-info text-dark mb-1">{{ $t->project_name }}</span>
                    @endif
                    <h5>{{ $t->title }}</h5>
                    <span class="badge bg-primary">{{ ucfirst($t->status_name) }}</span>
                    <p>{{ Str::limit($t->description, 80) }}</p>
                    <p><strong>Priority:</strong> {{ ucfirst($t->priority_name) }}</p>
                    <p><strong>Due:</strong> {{ $t->due_date }}</p>
                    <p><strong>Assigned By:</strong> {{ $t->assigned_by_name ?? 'N/A' }}
                        @if(isset($t->assigned_by_role))
                        ({{ ucfirst($t->assigned_by_role) }})
                        @endif
                    </p>
                </div>
                @if($tasks->isEmpty())
                <div class="col-12 text-center mt-5">
                    <h5 class="text-danger">Unauthorized to view this task or no tasks assigned.</h5>
                </div>
                @endif
                <div class="card-footer">
                    <a href="{{ route('user.tasks.show', $t->task_id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('user.tasks.show', $t->task_id) }}" class="btn btn-light btn-sm">Comment</a>
                    <a href="{{ route('user.tasks.edit', $t->task_id) }}" class="btn btn-warning btn-sm">Edit</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection