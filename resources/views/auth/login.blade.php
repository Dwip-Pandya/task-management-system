<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="bg-light">
    <div class="animated-bg"></div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm glass-card">
                    <div class="card-header bg-success text-white glass-header">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body glass-body">

                        @if ($errors->any())
                        <div class="alert alert-danger glass-alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success glass-alert">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label glass-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control glass-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label glass-label">Password</label>
                                <input type="password" name="password" class="form-control glass-input" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 glass-btn glass-btn-success">Login</button>
                        </form>

                        <p class="mt-3 text-center glass-text">
                            Don't have an account? <a href="{{ route('register') }}" class="glass-link">Register</a>
                        </p>

                        <!-- login with google -->
                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('auth.google') }}" class="btn btn-danger glass-btn glass-btn-danger">
                                <i class="bi bi-google"></i> Login with Google
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>