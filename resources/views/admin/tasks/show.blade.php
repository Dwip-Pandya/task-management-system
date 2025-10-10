@extends('layouts.main')

@section('title', 'Task Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <h4>Task Details</h4>
    <p><strong>Project:</strong> {{ $task->project_name }}</p>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Status:</strong> {{ ucfirst($task->status_name) }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($task->priority_name) }}</p>

    <hr>
    <h5>Comments</h5>

    {{-- Comment Form --}}
    @if($user->role_id === 1 || $user->id == $task->assigned_to)
    <form action="{{ route('comments.store') }}" method="POST" class="mb-3">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->task_id }}">
        <textarea name="message" class="form-control mb-2" rows="2" placeholder="Write a comment..." required>{{ old('message') }}</textarea>
        @error('message')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <button class="btn btn-primary btn-sm">Post Comment</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </form>
    @endif

    {{-- Build Comments Tree --}}
    @php
    $commentsTree = [];
    foreach($comments as $c) {
    if($c->parent_id === null) {
    $c->replies = [];
    $commentsTree[$c->comment_id] = $c;
    }
    }
    foreach($comments as $c) {
    if($c->parent_id !== null && isset($commentsTree[$c->parent_id])) {
    $commentsTree[$c->parent_id]->replies[] = $c;
    }
    }
    @endphp

    {{-- Display Comments --}}
    @foreach($commentsTree as $comment)
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>{{ $comment->name }} ({{ ucfirst($comment->role_name) }})</strong>
                <small class="text-muted">{{ $comment->created_at }}</small>
            </p>
            <p>{{ $comment->message }}</p>

            {{-- Reply Form --}}
            @if($user->role_id === 1 || $user->user_id == $task->assigned_to)
            <form action="{{ route('comments.store') }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->comment_id }}">
                <textarea name="message" class="form-control mb-1" rows="1" placeholder="Reply..." required></textarea>
                <button class="btn btn-secondary btn-sm">Reply</button>
            </form>
            @endif

            {{-- Display Replies --}}
            @if(!empty($comment->replies))
            <div class="ms-4 mt-2">
                @foreach($comment->replies as $reply)
                <div class="card mb-1 p-2">
                    <p><strong>{{ $reply->name }} ({{ ucfirst($reply->role_name) }})</strong>
                        <small class="text-muted">{{ $reply->created_at }}</small>
                    </p>
                    <p>{{ $reply->message }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection