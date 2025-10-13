@extends('layouts.main')

@section('title', 'Task Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <h4>Task Details</h4>
    <p><strong>Project:</strong> {{ $task->project_name }}</p>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($task->priority_name) }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

    <hr>
    <h5>Status & Comments</h5>

    @if($user->role_id === 1 || $user->id == $task->assigned_to)

    @include('partials.flash-messages')

    <form action="{{ route('comments.storeWithStatus') }}" method="POST" class="mb-3 comment-form">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->task_id }}">

        <div class="mb-2">
            <select name="status_id" id="status_id" class="form-select form-select-sm text-dark">
                @foreach(DB::table('statuses')->get() as $s)
                <option class="text-dark" value="{{ $s->status_id }}" {{ $s->status_id == $task->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <textarea name="message" class="form-control mb-2" rows="2" placeholder="Write a comment...">{{ old('message') }}</textarea>
            @error('message')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-sm deleted-user">Update</button>
        <a href="{{ route('projectmember.tasks.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </form>
    @endif

    <hr>
    <h5>Comments</h5>
    @foreach($comments as $c)
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>{{ $c->name }} ({{ ucfirst($c->role_name) }})</strong>
                <small class="text-muted">{{ $c->created_at }}</small>
            </p>
            <p>{{ $c->message }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/comment-validation.js') }}"></script>
@endpush