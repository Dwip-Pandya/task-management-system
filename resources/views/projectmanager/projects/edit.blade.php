@extends('layouts.main')

@section('title', 'Edit Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Edit Project</h2>

    <form action="{{ route('projectmanager.projects.update', $project) }}" method="POST">
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
        <a href="{{ route('projectmanager.projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection