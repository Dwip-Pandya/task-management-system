@extends('layouts.main')

@section('title', 'Task Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Task Details</h2>
    @include('partials.Breadcrumbs')


    <div class="card mb-4 bg-dark text-white mt-4">
        <div class="card-body">
            <h4>{{ $task->title }}</h4>
            <p>{{ $task->description }}</p>
            <p><strong>Assigned By:</strong> {{ $task->assigned_by_name ?? 'N/A' }}
                @if(isset($task->assigned_by_role))
                ({{ ucfirst($task->assigned_by_role) }})
                @endif
            </p>
            <p><strong>Priority:</strong> {{ ucfirst($task->priority_name) }}</p>
        </div>
    </div>

    <!-- combined comment and status update -->
    <h5>Post Comment & Update Status</h5>

    @include('partials.flash-messages')

    <form action="{{ route('comments.storeWithStatus') }}" method="POST" class="mb-3 comment-form">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->task_id }}">

        {{-- Status Dropdown --}}
        <p><strong>Status:</strong>
            <select name="status_id" class="form-select form-select-sm text-dark">
                @foreach (DB::table('statuses')->get() as $s)
                <option class="text-dark" value="{{ $s->status_id }}" {{ $s->status_id == $task->status_id ? 'selected' : '' }}>
                    {{ ucfirst($s->name) }}
                </option>
                @endforeach
            </select>
        </p>

        <textarea name="message" class="form-control mb-2" rows="2" placeholder="Write a comment...">{{ old('message') }}</textarea>
        @error('message')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <button class="btn btn-primary btn-sm deleted-user">Update</button>
    </form>

    {{-- Build Comments Tree --}}
    @php
    $commentsTree = [];
    foreach ($comments as $c) {
    if ($c->parent_id === null) {
    $c->replies = [];
    $commentsTree[$c->comment_id] = $c;
    }
    }
    foreach ($comments as $c) {
    if ($c->parent_id !== null && isset($commentsTree[$c->parent_id])) {
    $commentsTree[$c->parent_id]->replies[] = $c;
    }
    }
    @endphp

    {{-- Display Comments --}}
    @foreach ($commentsTree as $comment)
    <div class="card mb-2">
        <div class="card-body">
            <p><strong>{{ $comment->name }} ({{ ucfirst($comment->role) }})</strong>
                <small class="text-muted">{{ $comment->created_at }}</small>
            </p>
            <p>{{ $comment->message }}</p>

            {{-- Reply Form --}}
            <form action="{{ route('comments.storeWithStatus') }}" method="POST" class="mt-2 comment-form">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->comment_id }}">
                <textarea name="message" class="form-control mb-1" rows="1" placeholder="Reply...">{{ old('message') }}</textarea>
                @error('message')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <button class="btn btn-secondary btn-sm">Reply</button>
            </form>

            {{-- Replies --}}
            @if (!empty($comment->replies))
            <div class="ms-4 mt-2">
                @foreach ($comment->replies as $reply)
                <div class="card mb-1 p-2">
                    <p><strong>{{ $reply->name }} ({{ ucfirst($reply->role) }})</strong>
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

@push('scripts')
<script src="{{ asset('assets/js/comment-validation.js') }}"></script>
@endpush