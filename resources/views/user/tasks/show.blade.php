<!DOCTYPE html>
<html>

<head>
    <title>Task Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        {{-- Sidebar --}}
        @include('partials.sidebar', ['user' => $user])

        {{-- Main Content --}}
        <div class="flex-grow-1 p-4">
            <h2>Task Details</h2>
            <hr>

            <div class="card mb-4 bg-dark">
                <div class="card-body">
                    <h4>{{ $task->title }}</h4>
                    <p>{{ $task->description }}</p>
                    <p><strong>Assigned By:</strong> {{ $task->assigned_by_name ?? 'N/A' }}
                        @if(isset($task->assigned_by_role))
                        ({{ ucfirst($task->assigned_by_role) }})
                        @endif
                    </p>
                    <p><strong>Status:</strong>
                        <select class="form-select form-select-sm change-status" data-id="{{ $task->task_id }}">
                            @foreach (DB::table('statuses')->get() as $s)
                            <option class="text-dark" value="{{ $s->status_id }}"
                                {{ $s->status_id == $task->status_id ? 'selected' : '' }}>
                                {{ ucfirst($s->name) }}
                            </option>
                            @endforeach
                        </select>
                    </p>
                    <p><strong>Priority:</strong> {{ ucfirst($task->priority_name) }}</p>
                </div>
            </div>

            {{-- Comments Section --}}
            <h5>Comments</h5>

            {{-- Add Comment Form --}}
            <form action="{{ route('comments.store') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                <textarea name="message" class="form-control mb-2" rows="2" placeholder="Write a comment..." required></textarea>
                <button class="btn btn-primary btn-sm">Post Comment</button>
            </form>

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
                    <p><strong>{{ $comment->name }} ({{ ucfirst($comment->role) }})</strong>
                        <small class="text-muted">{{ $comment->created_at }}</small>
                    </p>
                    <p>{{ $comment->message }}</p>

                    {{-- Reply Form --}}
                    <form action="{{ route('comments.store') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                        <input type="hidden" name="parent_id" value="{{ $comment->comment_id }}">
                        <textarea name="message" class="form-control mb-1" rows="1" placeholder="Reply..." required></textarea>
                        <button class="btn btn-secondary btn-sm">Reply</button>
                    </form>

                    {{-- Display Replies --}}
                    @if(!empty($comment->replies))
                    <div class="ms-4 mt-2">
                        @foreach($comment->replies as $reply)
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
    </div>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Optional: AJAX for Status Update --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.change-status').change(function() {
            let status_id = $(this).val();
            let task_id = $(this).data('id');

            $.post('/user/tasks/' + task_id + '/update-status', {
                status_id: status_id
            }, function(res) {
                if (res.success) {
                    alert('Status updated successfully');
                } else {
                    alert('Error updating status');
                }
            });
        });
    </script>

</body>

</html>