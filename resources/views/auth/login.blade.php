@extends('layouts.auth-main-layout')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm glass-card">
            <div class="card-header bg-success text-white glass-header">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body glass-body">

                @include('partials.flash-messages')

                <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label glass-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control glass-input">
                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label glass-label">Password</label>
                        <input type="password" name="password" class="form-control glass-input">
                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100 glass-btn glass-btn-success">Login</button>
                </form>

                <p class="mt-3 text-center glass-text">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="glass-link">Register</a>
                </p>

                <div class="d-flex justify-content-center mt-3">
                    <a href="{{ route('auth.google') }}" class="btn btn-danger glass-btn glass-btn-danger">
                        <i class="bi bi-google"></i> Login with Google
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection