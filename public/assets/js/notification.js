document.addEventListener("DOMContentLoaded", function () {
    const notificationCountEl = document.getElementById('notificationCount');
    const notificationListEl = document.getElementById('notificationList');
    const notificationButton = document.getElementById('notificationButton');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    notificationCountEl.style.display = 'none';

    let notifications = [];

    // Auto-detect role and endpoint
    let baseUrl = window.location.pathname.includes('/admin/') ?
        '/admin/notifications' :
        window.location.pathname.includes('/projectmanager/') ?
            '/projectmanager/notifications' :
            window.location.pathname.includes('/projectmember/') ?
                '/projectmember/notifications' :
                '/user/notifications';

    // Fetch notifications
    function fetchNotifications() {
        fetch(baseUrl)
            .then(res => res.json())
            .then(data => {
                notifications = Array.isArray(data) ? data : [];
                renderNotifications();
            })
            .catch(err => console.error("Error fetching notifications:", err));
    }

    // Render notifications
    function renderNotifications() {
        notificationListEl.innerHTML = '';

        let unreadCount = 0;

        notifications.forEach(n => {
            if (!n.is_read) unreadCount++;

            const li = document.createElement('li');
            li.className = `list-group-item d-flex justify-content-between align-items-start notification-card ${n.is_read ? 'bg-light text-muted' : ''}`;
            li.dataset.id = n.id;

            li.innerHTML = `
            <div class="notification-content me-2" style="cursor:pointer; border-left: ${n.is_read ? 'none' : '2px solid #dc3545'}; padding-left: 8px;">
                <div class="fw-bold ${n.is_read ? 'text-secondary' : 'text-dark'} mb-1">${n.title}</div>
                <small class="text-muted d-block mb-1">${n.message}</small>
                <small class="text-dark fw-semibold">${new Date(n.created_at).toLocaleString()}</small>
            </div>
            <div class="d-flex flex-column align-items-end">
                <button class="btn btn-sm btn-outline-success mark-read-btn mb-1" title="Mark as read" ${n.is_read ? 'disabled' : ''}>
                    <i class="bi bi-check-lg"></i>
                </button>
                <!-- <button class="btn btn-sm btn-outline-danger remove-notification" title="Remove">
                    <i class="bi bi-x-lg"></i>
                </button> -->
            </div>
        `;

            if (n.is_read) {
                li.style.opacity = "0.6";
                li.style.pointerEvents = "none";
            }

            notificationListEl.appendChild(li);
        });

        // Show red badge with count if unread exist
        if (unreadCount > 0) {
            notificationCountEl.style.display = 'inline';
            notificationCountEl.textContent = unreadCount > 9 ? '9+' : unreadCount;
        } else {
            notificationCountEl.style.display = 'none';
        }
    }


    // Mark as read
    notificationListEl.addEventListener('click', function (e) {
        const card = e.target.closest('.notification-card');
        if (!card) return;
        const id = card.dataset.id;

        // Mark as read
        if (e.target.closest('.mark-read-btn')) {
            fetch(`${baseUrl}/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.ok ? res.json() : Promise.reject('Failed'))
                .then(() => {
                    const notif = notifications.find(n => n.id == id);
                    if (notif) notif.is_read = true;

                    card.classList.remove('bg-white', 'new');
                    card.classList.add('bg-light', 'read');
                    renderNotifications();
                })
                .catch(err => console.error('Error marking as read:', err));
        }
    });
    // Initial fetch
    fetchNotifications();
    // Poll every 30s
    setInterval(fetchNotifications, 30000);
});
document.addEventListener("DOMContentLoaded", () => {
    const offcanvas = document.getElementById("notificationOffcanvas");
    const resizeBtn = document.getElementById("resizeOffcanvasBtn");
    const icon = resizeBtn.querySelector("i");

    resizeBtn.addEventListener("click", () => {
        if (offcanvas.classList.contains("fullscreen")) {
            // Reset to default (25%)
            offcanvas.classList.remove("fullscreen", "medium");
            icon.className = "bi bi-arrows-angle-expand";
        } else if (offcanvas.classList.contains("medium")) {
            // Go fullscreen
            offcanvas.classList.add("fullscreen");
            offcanvas.classList.remove("medium");
            icon.className = "bi bi-arrows-collapse";
        } else {
            // Go to 50%
            offcanvas.classList.add("medium");
            icon.className = "bi bi-arrows-expand";
        }
    });
});