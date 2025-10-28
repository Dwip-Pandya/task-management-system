<!-- Notification Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas" aria-labelledby="notificationOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="notificationOffcanvasLabel">
            <i class="bi bi-bell-fill me-2"></i> Notifications
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Tabs -->
    <div class="offcanvas-header border-bottom d-flex justify-content-between align-items-center px-3">
        <div class="btn-group w-100">
            <button id="tabNew" class="btn btn-primary btn-sm w-50 active">Unread</button>
            <button id="tabAll" class="btn btn-outline-secondary btn-sm w-50">Previous</button>
        </div>
    </div>

    <!-- Body -->
    <div class="offcanvas-body p-0">
        <ul class="list-group list-group-flush" id="notificationList">
            <li class="list-group-item text-center py-3 text-muted">Loading...</li>
        </ul>
    </div>
</div>