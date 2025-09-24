<!DOCTYPE html>
<html>

<head>
    <title>Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Projects</h2>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
            </div>

            <div class="row">
                @forelse($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            {{-- Project Capsule --}}
                            <span class="badge bg-info text-dark mb-2">Project</span>
                            <h5 class="card-title">{{ $project->name }}</h5>
                            <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                            <p class="text-muted"><small>Created: {{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}</small></p>
                        </div>
                        <div class="card-footer d-flex">
                            <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-info btn-sm me-1">View</a>
                            <a href="{{ route('projects.edit', $project->project_id) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p>No projects found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>