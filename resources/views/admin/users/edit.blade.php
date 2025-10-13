@extends('layouts.main')

@section('title', 'Edit User')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/task.css') }}">
@endpush

@section('content')
<div class="flex-grow-1 p-4">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $editUser->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $editUser->name) }}" >
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $editUser->email) }}" >
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password (leave blank if not changing)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" >
                <option class="text-dark" value="">-- Select Role --</option>
                @foreach ($roles as $role)
                <option class="text-dark" value="{{ $role->id }}" {{ old('role_id', $editUser->role_id) == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>


        <button class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/user-validation.js') }}"></script>
@endpush