<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    {{-- Global CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partials.css') }}">

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-Specific JS --}}
    @stack('scripts')
</body>

</html>