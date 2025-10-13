@extends('layouts.main')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
<div class="flex-grow-1" id="dashboard-main">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Admin Dashboard</h2>

        {{-- User Filter Dropdown --}}
        <form method="GET" class="d-flex">
            <select name="assigned_to" class="form-select me-2">
                <option value="">All Users</option>
                @foreach($usersList as $u)
                <option value="{{ $u->id }}" {{ $request->assigned_to == $u->id ? 'selected' : '' }}>
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

            <button type="submit" class="btn btn-primary deleted-user">Filter</button>
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
                    <p class="task-assigned">Assigned To: <span class="assigned-name">{{ $task->assigned_user_name ?? 'Unassigned' }}</span></p>
                    <a href="{{ route('tasks.index') }}" class="glass-btn btn-warning">View</a>
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

@if(session('force_password_change'))
<div class="modal fade show" id="forcePasswordModal" tabindex="-1" style="display:block;" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.updatePassword') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Change Default Password</h5>
                </div>
                <div class="modal-body">
                    <p>You are using the default admin password. Please change it.</p>
                    <input type="password" name="password" class="form-control mb-2" placeholder="New Password" required>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modalEl = document.getElementById('forcePasswordModal');
        var modal = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    });
</script>
@endif

@endsection