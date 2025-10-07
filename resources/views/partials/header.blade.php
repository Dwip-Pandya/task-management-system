<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
        <span class="navbar-brand">
            {{ Auth::user()->name ?? 'Guest' }}
            <small class="d-block" style="font-size: 0.8rem;">
                {{ ucfirst(Auth::user()->role->name ?? 'guest') }}
            </small>
        </span>

        <div class="d-flex">
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>