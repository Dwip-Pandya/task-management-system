@extends('layouts.main')

@section('title', 'Edit task')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Edit Task</h2>
    <hr>

    <form action="{{ route('user.tasks.update', $task->task_id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="form-control" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Project</label>
            <select name="project_id" class="form-select" required>
                <option value="">Select Project</option>
                @foreach($projectsList as $p)
                <option value="{{ $p->project_id }}" {{ $task->project_id == $p->project_id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
        </div>


        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
                @foreach(DB::table('statuses')->get() as $s)
                <option class="text-dark" value="{{ $s->status_id }}"
                    {{ $task->status_id == $s->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Due Date --}}
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Task</button>
        <a href="{{ route('user.tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection