@extends('layouts.main')

@section('title', 'Create Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<h2>Create New Project</h2>

<form action="{{ route('projectmanager.projects.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Project Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="4" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Create Project</button>
    <a href="{{ route('projectmanager.projects.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection