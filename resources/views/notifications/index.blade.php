<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg px-6 py-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl mb-2">
                        <i class="fas fa-bell"></i> Notifikasi
                    </h2>
                    <p class="text-blue-100 text-sm">
                        Kelola dan lihat semua notifikasi Anda
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="markAllAsRead()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-check-double mr-2"></i>Tandai Semua Terbaca
                    </button>
                    <button onclick="clearAllNotifications()" class="bg-red-500/80 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i>Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Notifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Notifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Unread Notifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-bell-exclamation text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Belum Dibaca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->unreadNotifications()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Read Notifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Sudah Dibaca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notifications->total() - auth()->user()->unreadNotifications()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-blue-600"></i>Daftar Notifikasi
                </h3>
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $notifications->count() }} dari {{ $notifications->total() }} notifikasi
                </div>
            </div>
        </div>

        @if($notifications->count() > 0)
            <!-- Notifications List -->
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-all duration-200 {{ $notification->unread() ? 'bg-blue-50/30 border-l-4 border-blue-500' : '' }}"
                         id="notification-{{ $notification->id }}">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->unread() ? 'bg-blue-100' : 'bg-gray-100' }}">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} {{ $notification->unread() ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 {{ $notification->unread() ? 'font-bold' : '' }}">
                                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                                            </h4>
                                            @if($notification->unread())
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Baru
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ ($notification->data['type'] ?? 'info') === 'success' ? 'bg-green-100 text-green-800' :
                                                   (($notification->data['type'] ?? 'info') === 'error' ? 'bg-red-100 text-red-800' :
                                                   (($notification->data['type'] ?? 'info') === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} mr-1"></i>
                                                {{ ucfirst($notification->data['type'] ?? 'info') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2 leading-relaxed">
                                            {{ $notification->data['message'] ?? 'Pesan notifikasi' }}
                                        </p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $notification->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(isset($notification->data['action_url']))
                                            <a href="{{ $notification->data['action_url'] }}"
                                               onclick="markAsRead('{{ $notification->id }}')"
                                               class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @endif

                                        @if($notification->unread())
                                            <button onclick="markAsRead('{{ $notification->id }}')"
                                                    class="inline-flex items-center px-3 py-1 border border-green-300 text-xs leading-4 font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-all duration-200">
                                                <i class="fas fa-check mr-1"></i> Tandai Dibaca
                                            </button>
                                        @endif

                                        <button onclick="deleteNotification('{{ $notification->id }}')"
                                                class="inline-flex items-center px-3 py-1 border border-red-300 text-xs leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-all duration-200">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada notifikasi</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Semua notifikasi akan ditampilkan di sini. Saat ini Anda tidak memiliki notifikasi yang perlu ditangani.
                </p>
                <div class="mt-6">
                    <a href="{{ url()->previous() }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        @endif
    </div>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function markAsRead(notificationId) {
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
            const element = document.getElementById(`notification-${notificationId}`);
            // Remove unread styling
            element.classList.remove('bg-blue-50/30', 'border-l-4', 'border-blue-500');
            // Remove "Baru" badge
            const badge = element.querySelector('.bg-blue-100.text-blue-800');
            if (badge && badge.textContent.trim() === 'Baru') {
                badge.remove();
            }
            // Remove "Tandai Dibaca" button
            const markButton = element.querySelector('button[onclick*="markAsRead"]');
            if (markButton) {
                markButton.remove();
            }
            // Update icon color
            const icon = element.querySelector('.w-10.h-10');
            if (icon) {
                icon.classList.remove('bg-blue-100');
                icon.classList.add('bg-gray-100');
                const iconElement = icon.querySelector('i');
                if (iconElement) {
                    iconElement.classList.remove('text-blue-600');
                    iconElement.classList.add('text-gray-500');
                }
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    Swal.fire({
        title: 'Tandai Semua Terbaca?',
        text: 'Semua notifikasi akan ditandai sebagai telah dibaca',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Tandai Semua!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/notifications/mark-all-read', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all unread styling
                    document.querySelectorAll('[id^="notification-"]').forEach(element => {
                        element.classList.remove('bg-blue-50/30', 'border-l-4', 'border-blue-500');
                        // Remove "Baru" badges
                        const badge = element.querySelector('.bg-blue-100.text-blue-800');
                        if (badge && badge.textContent.trim() === 'Baru') {
                            badge.remove();
                        }
                        // Remove "Tandai Dibaca" buttons
                        const markButton = element.querySelector('button[onclick*="markAsRead"]');
                        if (markButton) {
                            markButton.remove();
                        }
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Semua notifikasi telah ditandai sebagai terbaca',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memproses permintaan',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            });
        }
    });
}

function deleteNotification(notificationId) {
    Swal.fire({
        title: 'Hapus Notifikasi?',
        text: 'Notifikasi yang dihapus tidak dapat dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const element = document.getElementById(`notification-${notificationId}`);
                    element.style.opacity = '0';
                    element.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        element.remove();
                        // Check if no notifications left
                        const remainingNotifications = document.querySelectorAll('[id^="notification-"]');
                        if (remainingNotifications.length === 0) {
                            location.reload();
                        }
                    }, 300);

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: 'Notifikasi berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menghapus notifikasi',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            });
        }
    });
}

function clearAllNotifications() {
    Swal.fire({
        title: 'Hapus Semua Notifikasi?',
        text: 'Semua notifikasi akan dihapus secara permanen dan tidak dapat dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'rounded-lg',
            cancelButton: 'rounded-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/notifications', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Semua notifikasi telah dihapus',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menghapus semua notifikasi',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            });
        }
    });
}

// Add smooth transitions
document.addEventListener('DOMContentLoaded', function() {
    // Add transition classes to all notification items
    const notifications = document.querySelectorAll('[id^="notification-"]');
    notifications.forEach(notification => {
        notification.style.transition = 'all 0.3s ease';
    });
});
</script>
</x-app-layout>
