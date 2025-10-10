@extends('layouts.main')

@section('title', 'Project Manager Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
<div class="flex-grow-1" id="dashboard-main">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Project Manager Dashboard</h2>

        {{-- Filter Form --}}
        <form method="GET" class="d-flex">
            <select name="assigned_to" class="form-select me-2">
                <option value="">All Users</option>
                @foreach($usersList as $u)
                <option value="{{ $u->id }}" {{ $request->assigned_to == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
                @endforeach
            </select>

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
                    <p class="task-project">Project: {{ $task->project_name }}</p>
                    <p class="task-assigned">Assigned To: <span class="assigned-name">{{ $task->assigned_user_name ?? 'Unassigned' }}</span></p>
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