<!-- Notification Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas" aria-labelledby="notificationOffcanvasLabel">
    <div class="offcanvas-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="offcanvas-title" id="notificationOffcanvasLabel">
            <i class="bi bi-bell-fill me-2"></i> Notifications
        </h5>
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="resizeOffcanvasBtn" title="Resize">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" />
                </svg>
            </button>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>

    <!-- Body -->
    <div class="offcanvas-body p-0">
        <ul class="list-group list-group-flush" id="notificationList">
            <li class="list-group-item text-center py-3 text-muted">Loading...</li>
        </ul>
    </div>
</div>