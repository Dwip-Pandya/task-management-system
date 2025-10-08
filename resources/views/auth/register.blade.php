<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="bg-light">
    <div class="animated-bg"></div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm glass-card">
                    <div class="card-header bg-primary text-white glass-header">
                        <h4 class="mb-0">Register</h4>
                    </div>
                    <div class="card-body glass-body">

                        @if ($errors->any())
                        <div class="alert alert-danger glass-alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label glass-label">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control glass-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label glass-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control glass-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label glass-label">Password</label>
                                <input type="password" name="password" class="form-control glass-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label glass-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control glass-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label glass-label">Role</label>
                                <select name="role_id" class="form-select glass-input" required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 glass-btn glass-btn-primary">Register</button>
                        </form>

                        <p class="mt-3 text-center glass-text">
                            Already have an account? <a href="{{ route('login') }}" class="glass-link">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>