@extends('layouts.main')

@section('title', 'Project Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/task.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <h2>Project Details</h2>
    @include('partials.Breadcrumbs')

    @if($permissions['can_view'])

    <div class="card card-1 mt-3 text-light">
        <div class="card-body">
            <h4 class="card-title">{{ $project->name }}</h4>
            <p class="card-text"><strong>Description:</strong> {{ $project->description }}</p>
            <p class="card-text"><strong>Created At:</strong> {{ $project->created_at->format('d M, Y H:i') }}</p>

            {{-- Display creator info --}}
            @if($creator)
            <p class="card-text"><strong>Created By:</strong> {{ $creator->name }}
                ({{ $creator->role_id == 1 ? 'Admin' : 'Project Manager' }})</p>
            @endif

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    @else
    <div class="alert alert-danger mt-3">
        You do not have permission to edit this project.
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-2">Back to Projects</a>
    @endif
</div>
@endsection