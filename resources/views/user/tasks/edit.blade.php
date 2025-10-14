@extends('layouts.main')

@section('title', 'Edit Task')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Edit Task</h2>
    @include('partials.Breadcrumbs')

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('user.tasks.update', $task->task_id) }}" method="POST" id="taskForm">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="form-control">
            @error('title')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $task->description) }}</textarea>
            @error('description')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Project --}}
        <div class="mb-3">
            <label class="form-label">Project</label>
            <select name="project_id" class="form-select">
                <option class="text-black" value="">-- Select Project --</option>
                @foreach($projectsList as $p)
                <option class="text-black" value="{{ $p->project_id }}" {{ old('project_id', $task->project_id) == $p->project_id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
            @error('project_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select">
                <option class="text-black" value="">-- Select Status --</option>
                @foreach(DB::table('statuses')->get() as $s)
                <option class="text-black" value="{{ $s->status_id }}" {{ old('status_id', $task->status_id) == $s->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
            @error('status_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Due Date --}}
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date) }}">
            @error('due_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary deleted-user">Update Task</button>
        <a href="{{ route('user.tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/task-validation.js') }}"></script>
@endpush