<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    {{-- Header --}}
    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        {{-- Sidebar --}}
        @include('partials.sidebar', ['user' => $user])

        {{-- Main Content --}}
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
    </div>

    {{-- Footer --}}
    @include('partials.footer')

</body>

</html>