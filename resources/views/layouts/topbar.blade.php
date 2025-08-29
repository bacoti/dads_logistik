<!-- Top Navigation Bar -->
<div class="bg-white/90 backdrop-blur-md shadow-lg border-b border-gray-200 sticky top-0 z-40">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Left Side - Toggle Button & Breadcrumb -->
        <div class="flex items-center space-x-4">
            <!-- Sidebar Toggle -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                <i class="fas fa-bars text-lg" x-show="sidebarOpen"></i>
                <i class="fas fa-chevron-right text-lg" x-show="!sidebarOpen"></i>
            </button>

            <!-- Breadcrumb -->
            <div class="hidden md:flex items-center space-x-2 text-sm">
                <i class="fas fa-home text-gray-400"></i>
                <span class="text-gray-600">{{ ucfirst(Auth::user()->role) }}</span>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-red-600 font-semibold">
                    @if(request()->routeIs('*.dashboard'))
                        Dashboard
                    @elseif(request()->routeIs('*.transactions.*'))
                        Transaksi
                    @elseif(request()->routeIs('*.monthly-reports.*'))
                        Laporan Bulanan
                    @elseif(request()->routeIs('*.loss-reports.*'))
                        Laporan Kehilangan
                    @elseif(request()->routeIs('*.mfo-requests.*'))
                        Pengajuan MFO
                    @elseif(request()->routeIs('*.po-materials.*'))
                        PO Materials
                    @elseif(request()->routeIs('*.master-data.*'))
                        Master Data
                    @else
                        {{ ucfirst(last(explode('.', Route::currentRouteName()))) }}
                    @endif
                </span>
            </div>
        </div>

        <!-- Center - Search Bar -->
        <div class="hidden lg:flex flex-1 max-w-lg mx-8">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                       placeholder="Cari data, laporan, atau fitur..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <kbd class="inline-flex items-center px-2 py-1 border border-gray-200 rounded text-xs font-sans font-medium text-gray-400">
                        Ctrl K
                    </kbd>
                </div>
            </div>
        </div>

        <!-- Right Side - Actions & Profile -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Search Button -->
            <button class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110">
                <i class="fas fa-search"></i>
            </button>

            <!-- Quick Actions -->
            <div class="hidden sm:flex items-center space-x-2">
                <!-- Notifications -->
                <div class="relative" x-data="notificationDropdown()">
                    <button @click="toggleDropdown()"
                            class="p-2 rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        <i class="fas fa-bell"></i>
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount"
                              class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse notification-count"></span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="isOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="isOpen = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 max-h-96 overflow-hidden">

                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('notifications.index') }}" class="text-xs text-red-600 hover:text-red-700">
                                    Lihat Semua
                                </a>
                                <button @click="markAllAsRead()" class="text-xs text-gray-500 hover:text-gray-700">
                                    Tandai Dibaca
                                </button>
                            </div>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-72 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="text-center py-8">
                                    <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                </div>
                            </template>

                            <template x-for="notification in notifications" :key="notification.id">
                                <div class="border-b border-gray-50 last:border-b-0">
                                    <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer"
                                         :class="!notification.read_at ? 'bg-blue-50' : ''"
                                         @click="handleNotificationClick(notification)">
                                        <div class="flex items-start space-x-3">
                                            <i :class="notification.data.icon || 'fas fa-bell'"
                                               class="text-sm mt-1 flex-shrink-0"
                                               :class="getNotificationIconColor(notification.data.type)"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900"
                                                   x-text="notification.data.title"
                                                   :class="!notification.read_at ? 'font-semibold' : ''"></p>
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2"
                                                   x-text="notification.data.message"></p>
                                                <p class="text-xs text-gray-400 mt-1"
                                                   x-text="formatTime(notification.created_at)"></p>
                                            </div>
                                            <template x-if="!notification.read_at">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div x-show="notifications.length > 0" class="px-4 py-2 border-t border-gray-100 bg-gray-50">
                            <a href="{{ route('notifications.index') }}"
                               class="block text-center text-sm text-red-600 hover:text-red-700 font-medium">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <button class="p-2 rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <i class="fas fa-envelope"></i>
                </button>

                <!-- Quick Add -->
                @if(auth()->user()->role === 'admin')
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="p-2 rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                            <i class="fas fa-plus"></i>
                        </button>

                        <!-- Quick Add Dropdown -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50">
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-plus-circle w-4 h-4 mr-3"></i>
                                Transaksi Baru
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-file-plus w-4 h-4 mr-3"></i>
                                Laporan Baru
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-box w-4 h-4 mr-3"></i>
                                Material Baru
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <!-- Profile Avatar -->
                    <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-white text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>

                    <!-- User Info -->
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>

                    <!-- Dropdown Arrow -->
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="open = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50">

                    <!-- User Info Card -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white text-lg font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize">
                                        <i class="fas fa-circle text-green-500 mr-1 text-xs"></i>
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fas fa-user-edit w-4 h-4 mr-3"></i>
                            Edit Profile
                        </a>

                        <a href="#"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fas fa-cog w-4 h-4 mr-3"></i>
                            Pengaturan
                        </a>

                        <a href="#"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fas fa-question-circle w-4 h-4 mr-3"></i>
                            Bantuan
                        </a>

                        <div class="border-t border-gray-100 my-2"></div>

                        <!-- Theme Switcher -->
                        <div class="px-4 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-palette w-4 h-4 mr-3 text-gray-600"></i>
                                    <span class="text-sm text-gray-700">Dark Mode</span>
                                </div>
                                <button class="relative inline-flex h-5 w-9 items-center rounded-full bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform translate-x-1"></span>
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-2"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        isOpen: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadNotifications();
            // Refresh notifications every 30 seconds
            setInterval(() => this.loadNotifications(), 30000);
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.loadNotifications();
            }
        },

        async loadNotifications() {
            try {
                const response = await fetch('/notifications/recent');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Failed to load notifications:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    this.notifications.forEach(notification => {
                        notification.read_at = new Date().toISOString();
                    });
                    this.unreadCount = 0;
                }
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification && !notification.read_at) {
                        notification.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                }
            } catch (error) {
                console.error('Failed to mark as read:', error);
            }
        },

        handleNotificationClick(notification) {
            // Mark as read if unread
            if (!notification.read_at) {
                this.markAsRead(notification.id);
            }

            // Navigate to action URL if available
            if (notification.data.action_url) {
                window.location.href = notification.data.action_url;
            }
        },

        getNotificationIconColor(type) {
            switch(type) {
                case 'success': return 'text-green-500';
                case 'error': return 'text-red-500';
                case 'warning': return 'text-yellow-500';
                case 'info': return 'text-blue-500';
                default: return 'text-gray-500';
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Baru saja';
            if (diffMins < 60) return `${diffMins} menit yang lalu`;
            if (diffHours < 24) return `${diffHours} jam yang lalu`;
            if (diffDays < 7) return `${diffDays} hari yang lalu`;

            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
            });
        }
    }
}
</script>
