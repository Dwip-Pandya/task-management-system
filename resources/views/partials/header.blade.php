<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
        <span class="navbar-brand">
            {{ Auth::user()->name ?? 'Guest' }}
            <small class="d-block" style="font-size: 0.8rem;">
                {{ ucfirst(Auth::user()->role->name ?? 'guest') }}
            </small>
        </span>

        @if (session('is_deactivated'))
        @include('partials.deactivate-message')
        @endif

        <div class="d-flex">
            @if(session()->has('admin_id'))
            <a href="{{ route('users.switchBack') }}"
                class="btn text-light swal-switch-back float-end"
                data-title="Return to your admin account?"
                data-confirm="Yes, switch back"
                data-cancel="Cancel">
                Switch Back to Admin
            </a>
            @endif

            <!-- Notification Button -->
            <button class="btn btn-outline-light btn-sm position-relative me-2" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas" aria-controls="notificationOffcanvas">
                <i class="bi bi-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    id="notificationCount">0</span>
            </button>

            &nbsp;
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>