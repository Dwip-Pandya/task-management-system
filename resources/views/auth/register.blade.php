@extends('layouts.auth-main-layout')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm glass-card">
            <div class="card-header bg-primary text-white glass-header">
                <h4 class="mb-0">Register</h4>
            </div>
            <div class="card-body glass-body">

                {{-- Success message --}}
                @if(session('success'))
                <div class="alert alert-success glass-alert">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label glass-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control glass-input">
                        @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label glass-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control glass-input">
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label glass-label">Password</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control glass-input">
                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label glass-label">Confirm Password</label>
                        <input type="password" name="password_confirmation"  class="form-control glass-input">
                    </div>

                    <div class="mb-3">
                        <label class="form-label glass-label">Role</label>
                        <select name="role_id" class="form-select glass-input">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 glass-btn glass-btn-primary">Register</button>
                </form>

                <p class="mt-3 text-center glass-text">
                    Already have an account?
                    <a href="{{ route('login') }}" class="glass-link">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection