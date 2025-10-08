@extends('layouts.main')

@section('title', 'Task Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <h4>Task Details</h4>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Status:</strong> {{ ucfirst($task->status_name) }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($task->priority_name) }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

    <hr>
    <h5>Comments</h5>

    @if($user->role_id === 1 || $user->id == $task->assigned_to)
    <form action="{{ route('comments.store') }}" method="POST" class="mb-3">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->task_id }}">
        <textarea name="message" class="form-control mb-2" rows="2" placeholder="Write a comment..." required></textarea>
        <button class="btn btn-primary btn-sm">Post Comment</button>
        <a href="{{ route('projectmanager.tasks.index') }}" class="btn btn-secondary btn-sm">Back to Tasks</a>
    </form>
    @endif

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