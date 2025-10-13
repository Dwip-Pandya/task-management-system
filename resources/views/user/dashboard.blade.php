@extends('layouts.main')

@section('title', 'User Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
<div class="flex-grow-1" id="dashboard-main">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ $user->role === 'admin' ? 'Admin Dashboard' : 'Dashboard' }}</h2>

        {{-- User Filter Dropdown --}}
        <form method="GET" class="d-flex">
            <select name="assigned_to" class="form-select me-2">
                <option value="">My Tasks</option>
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
                    <p class="task-assigned">
                        {{ $task->description }}
                    </p>
                    <p class="task-assigned">Assigned By: <span class="assigned-name">{{ $task->assigned_by_name ?? 'Unassigned' }}</span></p>
                    {{-- Updated View Button --}}
                    <a href="{{ route('user.tasks.index') }}"
                        class="glass-btn view-task-btn"
                        data-assigned-to="{{ $task->assigned_to }}">
                        View
                    </a>
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

<!-- pass id to js  -->
<script>
    const currentUserId = {
        {
            json_encode($user - > id)
        }
    };
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.view-task-btn');

        viewButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const assignedTo = parseInt(this.dataset.assignedTo);

                if (assignedTo !== currentUserId) {
                    e.preventDefault(); // stop default redirect
                    if (confirm("You are not allowed to view tasks assigned to other users. Click OK to go to your tasks.")) {
                        window.location.href = this.href; // redirect to tasks page
                    }
                }
            });
        });
    });
</script>
@endsection