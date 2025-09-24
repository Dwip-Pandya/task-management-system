<!DOCTYPE html>
<html>

<head>
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="container mt-4">
            <h2>Edit Project</h2>

            <form action="{{ route('projects.update', $project->project_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Project Name</label>
                    <input type="text" name="name" id="name" value="{{ $project->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control">{{ $project->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>