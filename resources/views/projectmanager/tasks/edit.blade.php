@extends('layouts.main')

@section('title', 'Edit Task')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Edit Task</h2>
    <form action="{{ route('projectmanager.tasks.update', $task->task_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}">
            @error('title')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
            @error('description')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select">
                <option class="text-dark" value="">-- Select User --</option>
                @foreach($users as $u)
                <option class="text-dark" value="{{ $u->id }}" {{ old('assigned_to', $task->assigned_to) == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->role->name ?? 'No Role' }})
                </option>
                @endforeach
            </select>
            @error('assigned_to')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select name="project_id" id="project_id" class="form-select">
                @foreach($projects as $project)
                <option class="text-dark" value="{{ $project->project_id }}" {{ old('project_id', $task->project_id) == $project->project_id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
                @endforeach
            </select>
            @error('project_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select">
                @foreach($statuses as $s)
                <option class="text-dark" value="{{ $s->status_id }}" {{ old('status_id', $task->status_id) == $s->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
            @error('status_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority_id" class="form-select">
                @foreach($priorities as $p)
                <option class="text-dark" value="{{ $p->priority_id }}" {{ old('priority_id', $task->priority_id) == $p->priority_id ? 'selected' : '' }}>
                    {{ ucfirst($p->name) }}
                </option>
                @endforeach
            </select>
            @error('priority_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tag</label>
            <select name="tag_id" class="form-select">
                <option class="text-dark" value="">-- None --</option>
                @foreach($tags as $t)
                <option class="text-dark" value="{{ $t->tag_id }}" {{ old('tag_id', $task->tag_id) == $t->tag_id ? 'selected' : '' }}>
                    {{ ucfirst($t->name) }}
                </option>
                @endforeach
            </select>
            @error('tag_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date) }}">
            @error('due_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('projectmanager.tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/task-validation.js') }}"></script>
@endpush