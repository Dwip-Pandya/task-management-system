@extends('layouts.main')

@section('title', 'Edit Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Edit Project</h2>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $project->name) }}"
                class="form-control @error('name') is-invalid @enderror">
            {{-- Server-side validation error --}}
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
            {{-- Server-side validation error --}}
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary deleted-user">Update Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/project-validation.js') }}"></script>
@endpush