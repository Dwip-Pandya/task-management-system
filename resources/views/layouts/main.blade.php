<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Task Manager')</title>

    {{-- Global CSS --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/partials.css') }}">

    {{-- Page-Specific CSS --}}
    @stack('styles')
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    {{-- Header --}}
    @include('partials.header', ['user' => $user ?? null])

    <div class="d-flex flex-grow-1">
        {{-- Sidebar --}}
        @include('partials.sidebar', ['user' => $user ?? null])

        {{-- Main Dynamic Page Content --}}
        @yield('content')
    </div>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Global JS --}}
    <!-- @if(session('is_deactivated'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("button, input[type=submit],a.btn-primary, a.btn-danger, a.btn-warning, a.btn-info").forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = "0.5";
                btn.style.pointerEvents = "none";
            });
        });
    </script>
    @endif -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Page-Specific JS --}}
    @stack('scripts')
</body>

</html>