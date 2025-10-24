@extends('layouts.main')

@section('title', 'Create Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Create New Project</h2>
    @include('partials.Breadcrumbs')

    @if($permissions['can_add'])
    <form action="{{ route('projects.store') }}" method="POST" id="projectForm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary deleted-user">Create Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    @else
    <div class="alert alert-danger mt-3">
        You do not have permission to create new projects.
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-2">Back to Projects</a>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/project-validation.js') }}"></script>
@endpush