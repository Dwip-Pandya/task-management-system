@extends('layouts.main')

@section('title', 'Edit Task')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Edit Task</h2>
    <form action="{{ route('projectmanager.tasks.update', $task->task_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="{{ $task->title }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $task->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select">
                <option value="">-- Select User --</option>
                @foreach($users as $u)
                <option class="text-dark" value="{{ $u->id }}" {{ $task->assigned_to == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->role_name ?? '' }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select name="project_id" id="project_id" class="form-select" required>
                @foreach($projects as $project)
                <option value="{{ $project->project_id }}" {{ $task->project_id == $project->project_id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
                @foreach($statuses as $s)
                <option class="text-dark" value="{{ $s->status_id }}" {{ $task->status_id == $s->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority_id" class="form-select" required>
                @foreach($priorities as $p)
                <option class="text-dark" value="{{ $p->priority_id }}" {{ $task->priority_id == $p->priority_id ? 'selected' : '' }}>
                    {{ ucfirst($p->name) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tag</label>
            <select name="tag_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($tags as $t)
                <option class="text-dark" value="{{ $t->tag_id }}" {{ $task->tag_id == $t->tag_id ? 'selected' : '' }}>
                    {{ ucfirst($t->name) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" value="{{ $task->due_date }}" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('projectmanager.tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection