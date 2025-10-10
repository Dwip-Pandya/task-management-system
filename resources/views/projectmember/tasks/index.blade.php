@extends('layouts.main')

@section('title', 'Manage Tasks')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Tasks</h3>
    </div>

    <form method="GET" class="row g-2 mb-4">
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

        {{-- Project --}}
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
            <input type="date" name="due_date" class="form-control" value="{{ $request->due_date }}">
        </div>

        {{-- Filter Button --}}
        <div class="col-md-2 mt-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row">
        @foreach ($tasks as $t)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-primary">
                <div class="card-body">
                    @if($t->project_name)
                    <span class="badge bg-info text-dark mb-2">{{ $t->project_name }}</span>
                    @endif
                    <h5 class="card-title">{{ $t->title }}</h5>
                    <span class="badge bg-secondary mb-2">{{ ucfirst($t->status_name) }}</span>
                    <p class="card-text">{{ Str::limit($t->description, 100) }}</p>
                    <p><strong>Due Date:</strong> {{ $t->due_date }}</p>
                </div>
                <div class="card-footer d-flex">
                    <a href="{{ route('projectmember.tasks.show', $t->task_id) }}" class="btn btn-info btn-sm">Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection