@extends('layouts.main')

@section('title', 'Create Project')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<h2>Create New Project</h2>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Project Name</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
        {{-- Display server-side validation error --}}
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        {{-- Display server-side validation error --}}
        @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Create Project</button>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/project-validation.js') }}"></script>
@endpush