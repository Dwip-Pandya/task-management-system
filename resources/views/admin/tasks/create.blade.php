@extends('layouts.main')

@section('title', 'Create Task')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/task-validation.js') }}"></script>
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Create Task</h2>
    <form action="{{ route('tasks.store') }}" method="POST" id="taskForm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select">
                <option class value="">-- Select User --</option>
                @foreach($users as $u)
                <option class="text-dark" value="{{ $u->id }}" {{ old('assigned_to') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->role_name }})
                </option>
                @endforeach
            </select>
            @error('assigned_to')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Project</label>
            <select name="project_id" class="form-select">
                <option value="">-- Select Project --</option>
                @foreach($projects as $project)
                <option class="text-dark" value="{{ $project->project_id }}" {{ old('project_id') == $project->project_id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
                @endforeach
            </select>
            @error('project_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select">
                <option value="">-- Select Status --</option>
                @foreach($statuses as $s)
                <option class="text-dark" value="{{ $s->status_id }}" {{ old('status_id') == $s->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
            @error('status_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority_id" class="form-select">
                @foreach($priorities as $p)
                <option class="text-dark" value="{{ $p->priority_id }}" {{ old('priority_id') == $p->priority_id ? 'selected' : '' }}>
                    {{ ucfirst($p->name) }}
                </option>
                @endforeach
            </select>
            @error('priority_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tag</label>
            <select name="tag_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($tags as $t)
                <option class="text-dark" value="{{ $t->tag_id }}" {{ old('tag_id') == $t->tag_id ? 'selected' : '' }}>
                    {{ ucfirst($t->name) }}
                </option>
                @endforeach
            </select>
            @error('tag_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
            @error('due_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/task-validation.js') }}"></script>
@endpush