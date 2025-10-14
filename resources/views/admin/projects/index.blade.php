@extends('layouts.main')

@section('title', 'Projects')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>Projects</h2>
    @include('partials.Breadcrumbs')
    <div class="d-flex justify-content-between align-items-center">
        <div><input type="hidden" name=""></div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New Project</a>
    </div>

    {{-- Filter by Creator Role --}}
    <form method="GET" class="mb-4 row g-2 align-items-center">
        <div class="col-auto">
            <select name="creator_role" class="form-select">
                <option class="text-black" value="">All Roles</option>
                <option class="text-dark" value="1" {{ $request->creator_role == 1 ? 'selected' : '' }}>Admin</option>
                <option class="text-dark" value="4" {{ $request->creator_role == 4 ? 'selected' : '' }}>Project Manager</option>
            </select>
        </div>

        {{-- New: Filter by creator --}}
        <div class="col-auto">
            <select name="created_by" class="form-select">
                <option class="text-dark" value="">Creators</option>
                @foreach($creators as $creator)
                <option class="text-dark" value="{{ $creator->id }}" {{ $request->created_by == $creator->id ? 'selected' : '' }}>
                    {{ $creator->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <div class="total-projects">
        <h4 class="text-secondary">Total: {{ $projects->count() }} Projects</h4>
    </div>
    @include('partials.flash-messages')
    <div class="row">
        @forelse($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    {{-- Project Capsule --}}
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                    <p class="text-muted"><small>Created: {{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}</small></p>

                    {{-- Display creator role & name --}}
                    @if($project->creator_name)
                    <p><strong>Created By:</strong> {{ $project->creator_name }}
                        ({{ $project->creator_role_id == 1 ? 'Admin' : 'Project Manager' }})</p>
                    @endif
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
@endsection