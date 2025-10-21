document.addEventListener("DOMContentLoaded", function () {
    const notificationCountEl = document.getElementById('notificationCount');
    const notificationListEl = document.getElementById('notificationList');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        if (!notifications.length) {
            notificationListEl.innerHTML = `<li class="list-group-item text-center py-3 text-muted">No notifications</li>`;
            notificationCountEl.textContent = 0;
            return;
        }

        let unreadCount = 0;

        notifications.forEach(n => {
            if (!n.is_read) unreadCount++;

            const li = document.createElement('li');
            li.className = `list-group-item d-flex justify-content-between align-items-start notification-card ${n.is_read ? 'bg-light' : 'bg-white'}`;
            li.dataset.id = n.id;

            li.innerHTML = `
                <div class="notification-content me-2" style="cursor:pointer;">
                    <div class="fw-bold text-dark mb-1">${n.title}</div>
                    <small class="text-muted d-block mb-1">${n.message}</small>
                    <small class="text-dark fw-semibold">${new Date(n.created_at).toLocaleString()}</small>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <button class="btn btn-sm btn-outline-success mark-read-btn mb-1" title="Mark as read">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger remove-notification" title="Remove">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;

            notificationListEl.appendChild(li);
        });

        notificationCountEl.textContent = unreadCount;
    }

    // Handle clicks for remove and mark-read
    notificationListEl.addEventListener('click', function (e) {
        const card = e.target.closest('.notification-card');
        if (!card) return;
        const id = card.dataset.id;

        // Remove notification
        if (e.target.closest('.remove-notification')) {
            fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.ok ? res.json() : Promise.reject('Failed'))
                .then(() => {
                    notifications = notifications.filter(n => n.id != id);
                    renderNotifications();
                })
                .catch(err => console.error('Error removing notification:', err));
            return;
        }

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
                    renderNotifications();
                })
                .catch(err => console.error('Error marking as read:', err));
            return;
        }
    });

    // Initial fetch
    fetchNotifications();
    // Poll every 30s
    setInterval(fetchNotifications, 30000);
});