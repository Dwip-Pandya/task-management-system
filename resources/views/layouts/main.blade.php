<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- csrf token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Task Manager')</title>

    {{-- Global CSS --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/partials.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/breadcrumb.css') }}">

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
    @if(session('is_deactivated'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".deleted-user").forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = "0.5";
                btn.style.pointerEvents = "none";
            });
        });
    </script>
    @endif
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- jQuery Validation -->
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/js/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Custom sweet alerts -->
    <script src="{{ asset('assets/js/sweet-alerts.js') }}"></script>


    {{-- Page-Specific JS --}}
    @stack('scripts')
</body>

</html>