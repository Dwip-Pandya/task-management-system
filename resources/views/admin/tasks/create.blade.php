@extends('layouts.main')

@section('title', 'Create Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Create Task</h2>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select">
                <option value="">-- Select User --</option>
                @foreach($users as $u)
                <option class="text-dark" value="{{ $u->id }}">{{ $u->name }} ({{ $u->role_name }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select name="project_id" id="project_id" class="form-select" required>
                <option value="">-- Select Project --</option>
                @foreach($projects as $project)
                <option class="text-dark" value="{{ $project->project_id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
                <option value="">-- Select Status --</option>
                @foreach($statuses as $s)
                <option class="text-dark" value="{{ $s->status_id }}">{{ ucfirst($s->name) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority_id" class="form-select" required>
                @foreach($priorities as $p)
                <option class="text-dark" value="{{ $p->priority_id }}">{{ ucfirst($p->name) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tag</label>
            <select name="tag_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($tags as $t)
                <option class="text-dark" value="{{ $t->tag_id }}">{{ ucfirst($t->name) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control">
        </div>

        <button class="btn btn-success">Create</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection