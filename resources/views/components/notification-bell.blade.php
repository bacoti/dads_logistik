<div class="nav-item dropdown" id="notification-dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" id="notification-bell">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="notification-count" style="display: none;">0</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-dropdown-menu">
        <span class="dropdown-item dropdown-header" id="notification-header">
            <span id="notification-total">0</span> Notifikasi
        </span>
        <div class="dropdown-divider"></div>

        <div id="notification-list">
            <!-- Notifications will be loaded here -->
        </div>

        <div class="dropdown-divider"></div>
        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});

function loadNotifications() {
    fetch('/notifications/recent')
        .then(response => response.json())
        .then(data => {
            updateNotificationUI(data);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationUI(data) {
    const count = data.unread_count;
    const countBadge = document.getElementById('notification-count');
    const totalSpan = document.getElementById('notification-total');
    const notificationList = document.getElementById('notification-list');

    // Update count badge
    if (count > 0) {
        countBadge.textContent = count > 99 ? '99+' : count;
        countBadge.style.display = 'inline';
    } else {
        countBadge.style.display = 'none';
    }

    // Update total count
    totalSpan.textContent = data.notifications.length;

    // Update notification list
    notificationList.innerHTML = '';

    if (data.notifications.length > 0) {
        data.notifications.forEach(notification => {
            const item = createNotificationItem(notification);
            notificationList.appendChild(item);
        });
    } else {
        const emptyItem = document.createElement('div');
        emptyItem.className = 'dropdown-item text-center text-muted py-3';
        emptyItem.innerHTML = '<i class="fas fa-bell-slash"></i><br><small>Tidak ada notifikasi</small>';
        notificationList.appendChild(emptyItem);
    }
}

function createNotificationItem(notification) {
    const item = document.createElement('a');
    item.href = notification.data.action_url || '#';
    item.className = `dropdown-item ${notification.read_at ? '' : 'bg-light'}`;
    item.onclick = () => markNotificationAsRead(notification.id);

    const icon = notification.data.icon || 'fas fa-bell';
    const type = notification.data.type || 'info';
    const title = notification.data.title || 'Notifikasi';
    const message = notification.data.message || 'Pesan notifikasi';
    const timeAgo = new Date(notification.created_at).toLocaleString('id-ID');

    item.innerHTML = `
        <i class="${icon} mr-2 text-${type}"></i>
        <div class="d-inline-block">
            <strong>${title}</strong><br>
            <small class="text-muted">${message.length > 50 ? message.substring(0, 50) + '...' : message}</small><br>
            <small class="text-muted"><i class="fas fa-clock"></i> ${timeAgo}</small>
            ${notification.read_at ? '' : '<span class="badge badge-primary badge-sm ml-1">Baru</span>'}
        </div>
    `;

    return item;
}

function markNotificationAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh notifications after marking as read
            setTimeout(loadNotifications, 500);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<style>
.dropdown-menu-lg {
    width: 350px;
    max-height: 400px;
    overflow-y: auto;
}

.dropdown-item {
    white-space: normal;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f4f4f4;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background-color: rgba(0,0,0,.05);
}

#notification-count {
    font-size: 0.7rem;
    padding: 0.25rem 0.4rem;
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
}
</style>
