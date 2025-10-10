@extends('layouts.main')

@section('title', 'Project Member Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
<div class="flex-grow-1" id="dashboard-main">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="me-3">Project Member Dashboard</h2>

        <form method="GET" class="d-flex">
            {{-- Project Filter --}}
            <select name="project_id" class="form-select me-2">
                <option class="text-dark" value="">All Projects</option>
                @foreach($projectsList as $p)
                <option value="{{ $p->project_id }}" {{ $request->project_id == $p->project_id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select name="status" class="form-select me-2">
                <option value="">All Statuses</option>
                @foreach(['pending','in_progress','completed','on_hold'] as $s)
                <option value="{{ $s }}" {{ $request->status == $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
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
                    <p class="task-project">Project: {{ $task->project_name }}</p>
                    <a href="{{ route('projectmember.tasks.show', $task->task_id) }}" class="glass-btn">View</a>
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
@endsection