<!DOCTYPE html>
<html>

<head>
    <title>Project Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        @include('partials.sidebar', ['user' => $user])

        <div class="container mt-4">
            <h2>Project Details</h2>

            <div class="card card-1 mt-3">
                <div class="card-body">
                    <h4 class="card-title">{{ $project->name }}</h4>
                    <p class="card-text"><strong>Description:</strong> {{ $project->description }}</p>
                    <p class="card-text"><strong>Created At:</strong> {{ $project->created_at->format('d M, Y H:i') }}</p>
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>