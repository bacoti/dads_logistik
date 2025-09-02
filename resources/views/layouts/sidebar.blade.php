<!-- Mobile Overlay -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden sidebar-overlay"></div>

<!-- Sidebar Navigation -->
<div class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out lg:translate-x-0"
     :class="{
         'w-64': sidebarOpen,
         'w-16': !sidebarOpen,
         '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
         'translate-x-0': sidebarOpen || window.innerWidth >= 1024
     }">
    <!-- Sidebar Background with Company Gradient -->
    <div class="flex flex-col h-full bg-gradient-to-b from-red-600 via-red-700 to-red-800 shadow-xl">
        <!-- Company Logo & Brand -->
        <div class="flex items-center justify-center p-4 border-b border-red-500/30" :class="sidebarOpen ? 'px-6 py-6' : 'px-2'">
            <div class="flex items-center space-x-4 transition-all duration-300">
                <div class="flex-shrink-0">
                    <!-- Logo Company dengan Image dan Fallback - DIPERBESAR -->
                    <div class="bg-white rounded-xl shadow-lg flex items-center justify-center transform transition-transform hover:scale-105 overflow-hidden"
                         :class="sidebarOpen ? 'w-14 h-14' : 'w-12 h-12'">
                        @if(file_exists(public_path('images/logos/logo-small.png')))
                            <img src="{{ asset('images/logos/logo-small.png') }}"
                                 alt="PT DADS Logo"
                                 :class="sidebarOpen ? 'w-12 h-12' : 'w-10 h-10'"
                                 class="object-contain transition-all duration-300"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="flex items-center justify-center transition-all duration-300"
                                 :class="sidebarOpen ? 'w-12 h-12' : 'w-10 h-10'"
                                 style="display: none;">
                                <i class="fas fa-truck text-red-600"
                                   :class="sidebarOpen ? 'text-2xl' : 'text-xl'"></i>
                            </div>
                        @else
                            <!-- Fallback Icon jika logo belum ada - DIPERBESAR -->
                            <i class="fas fa-truck text-red-600 transition-all duration-300"
                               :class="sidebarOpen ? 'text-2xl' : 'text-xl'"></i>
                        @endif
                    </div>
                </div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="flex flex-col">
                    <h1 class="text-white font-bold text-xl leading-tight">PT DADS</h1>
                    <p class="text-red-200 text-sm font-medium">Logistik</p>
                </div>
            </div>
        </div>

        <!-- User Info Card -->
        <div x-show="sidebarOpen" x-transition class="px-4 py-3 border-b border-red-500/30">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 transition-all hover:bg-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-white to-gray-200 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
                        <p class="text-red-200 text-xs capitalize flex items-center space-x-1">
                            <i class="fas fa-circle text-green-400 text-xs animate-pulse"></i>
                            <span>{{ Auth::user()->role }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-track-red-800 scrollbar-thumb-red-600" x-data="sidebarCategories()">
            @if(auth()->user()->role === 'admin')
                <!-- Admin Navigation -->
                <div x-show="sidebarOpen" class="px-3 py-2">
                    <p class="text-red-200 text-xs font-semibold uppercase tracking-wider">Admin Panel</p>
                </div>

                <!-- Dashboard - Always Visible -->
                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-red-600 shadow-lg transform scale-105' : 'text-red-100 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105' }}">
                    <div class="flex items-center justify-center w-6 h-6 mr-3">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-red-600' : 'text-red-200 group-hover:text-white' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="truncate">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                    @endif
                </a>

                <!-- 📊 DATA & LAPORAN KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Data & Laporan -->
                    <button @click="toggleCategory('reports')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-chart-bar text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Data & Laporan</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="categories.reports ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Data & Laporan -->
                    <div x-show="sidebarOpen && categories.reports" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- Data Transaksi -->
                        <a href="{{ route('admin.transactions.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-exchange-alt w-4 h-4 mr-3 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Data Transaksi</span>
                        </a>

                        <!-- Laporan Bulanan -->
                        <a href="{{ route('admin.monthly-reports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.monthly-reports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-chart-line w-4 h-4 mr-3 {{ request()->routeIs('admin.monthly-reports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Laporan Bulanan</span>
                        </a>

                        <!-- Laporan Kehilangan -->
                        <a href="{{ route('admin.loss-reports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.loss-reports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-exclamation-triangle w-4 h-4 mr-3 {{ request()->routeIs('admin.loss-reports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Laporan Kehilangan</span>
                        </a>

                        <!-- Pengajuan MFO -->
                        <a href="{{ route('admin.mfo-requests.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.mfo-requests.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-file-alt w-4 h-4 mr-3 {{ request()->routeIs('admin.mfo-requests.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Pengajuan MFO</span>
                        </a>
                    </div>
                </div>

                <!-- 📋 PURCHASE ORDER KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Purchase Order -->
                    <button @click="toggleCategory('po')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-clipboard-list text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Purchase Order</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="categories.po ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Purchase Order -->
                    <div x-show="sidebarOpen && categories.po" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- PO Materials -->
                        <a href="{{ route('admin.po-materials.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.po-materials.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-boxes w-4 h-4 mr-3 {{ request()->routeIs('admin.po-materials.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">PO Materials</span>
                        </a>

                        <!-- PO Transportasi -->
                        <a href="{{ route('admin.po-transports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.po-transports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-truck w-4 h-4 mr-3 {{ request()->routeIs('admin.po-transports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">PO Transportasi</span>
                        </a>
                    </div>
                </div>

                <!-- 🔧 SISTEM MANAGEMENT KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Sistem Management -->
                    <button @click="toggleCategory('system')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-cogs text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Sistem Management</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="categories.system ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Sistem Management -->
                    <div x-show="sidebarOpen && categories.system" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- Master Data -->
                        <a href="{{ route('admin.master-data.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.master-data.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-database w-4 h-4 mr-3 {{ request()->routeIs('admin.master-data.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Master Data</span>
                        </a>

                        <!-- Manajemen User -->
                        <a href="{{ route('admin.users.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-users w-4 h-4 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Manajemen User</span>
                        </a>

                        <!-- Manajemen Dokumen -->
                        <a href="{{ route('admin.documents.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.documents.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-file-upload w-4 h-4 mr-3 {{ request()->routeIs('admin.documents.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Manajemen Dokumen</span>
                        </a>
                    </div>
                </div>

            @elseif(auth()->user()->role === 'po')
                <!-- PO Navigation -->
                <div x-show="sidebarOpen" class="px-3 py-2">
                    <p class="text-red-200 text-xs font-semibold uppercase tracking-wider">PO Panel</p>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('po.dashboard') }}"
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('po.dashboard') ? 'bg-white text-red-600 shadow-lg transform scale-105' : 'text-red-100 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105' }}">
                    <div class="flex items-center justify-center w-6 h-6 mr-3">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('po.dashboard') ? 'text-red-600' : 'text-red-200 group-hover:text-white' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="truncate">Dashboard</span>
                    @if(request()->routeIs('po.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                    @endif
                </a>

                <!-- PO Materials -->
                <a href="{{ route('po.po-materials.index') }}"
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('po.po-materials.*') ? 'bg-white text-red-600 shadow-lg transform scale-105' : 'text-red-100 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105' }}">
                    <div class="flex items-center justify-center w-6 h-6 mr-3">
                        <i class="fas fa-boxes {{ request()->routeIs('po.po-materials.*') ? 'text-red-600' : 'text-red-200 group-hover:text-white' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="truncate">PO Materials</span>
                    @if(request()->routeIs('po.po-materials.*'))
                        <div class="ml-auto w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                    @endif
                </a>

                <!-- 📦 MATERIAL TRACKING KATEGORI -->
                <div class="space-y-1 mt-4">
                    <!-- Category Header - Material Tracking -->
                    <button @click="toggleCategory('materials')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-warehouse text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Material Tracking</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="categories.materials ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Material Tracking -->
                    <div x-show="sidebarOpen && categories.materials" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- Material Dashboard -->
                        <a href="{{ route('po.material-dashboard.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('po.material-dashboard.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-chart-bar w-4 h-4 mr-3 {{ request()->routeIs('po.material-dashboard.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Dashboard</span>
                        </a>

                        <!-- Material Receipt -->
                        <a href="{{ route('po.material-receipt.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('po.material-receipt.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-truck-loading w-4 h-4 mr-3 {{ request()->routeIs('po.material-receipt.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Penerimaan Material</span>
                        </a>

                        <!-- Material Usage -->
                        <a href="{{ route('po.material-usage.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('po.material-usage.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-hammer w-4 h-4 mr-3 {{ request()->routeIs('po.material-usage.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Penggunaan Material</span>
                        </a>

                        <!-- Material Stock -->
                        <a href="{{ route('po.material-stock.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('po.material-stock.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-layer-group w-4 h-4 mr-3 {{ request()->routeIs('po.material-stock.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Stock Material</span>
                        </a>
                    </div>
                </div>

            @else
                <!-- User Navigation -->
                <div x-show="sidebarOpen" class="px-3 py-2">
                    <p class="text-red-200 text-xs font-semibold uppercase tracking-wider">User Panel</p>
                </div>

                <!-- Dashboard - Always Visible -->
                <a href="{{ route('user.dashboard') }}"
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-white text-red-600 shadow-lg transform scale-105' : 'text-red-100 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105' }}">
                    <div class="flex items-center justify-center w-6 h-6 mr-3">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('user.dashboard') ? 'text-red-600' : 'text-red-200 group-hover:text-white' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="truncate">Dashboard</span>
                    @if(request()->routeIs('user.dashboard'))
                        <div class="ml-auto w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                    @endif
                </a>

                <!-- 📊 DATA & LAPORAN KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Data & Laporan -->
                    <button @click="toggleUserCategory('reports')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-chart-bar text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Data & Laporan</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="userCategories.reports ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Data & Laporan -->
                    <div x-show="sidebarOpen && userCategories.reports" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- Transaksi -->
                        <a href="{{ route('user.transactions.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.transactions.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-exchange-alt w-4 h-4 mr-3 {{ request()->routeIs('user.transactions.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Transaksi</span>
                        </a>

                        <!-- Laporan Bulanan -->
                        <a href="{{ route('user.monthly-reports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.monthly-reports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-chart-line w-4 h-4 mr-3 {{ request()->routeIs('user.monthly-reports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Laporan Bulanan</span>
                        </a>

                        <!-- Laporan Kehilangan -->
                        <a href="{{ route('user.loss-reports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.loss-reports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-exclamation-triangle w-4 h-4 mr-3 {{ request()->routeIs('user.loss-reports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Laporan Kehilangan</span>
                        </a>

                        <!-- Pengajuan MFO -->
                        <a href="{{ route('user.mfo-requests.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.mfo-requests.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-file-alt w-4 h-4 mr-3 {{ request()->routeIs('user.mfo-requests.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Pengajuan MFO</span>
                        </a>
                    </div>
                </div>

                <!-- 🚚 PURCHASE ORDER KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Purchase Order -->
                    <button @click="toggleUserCategory('transport')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-truck text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Purchase Order</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="userCategories.transport ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Purchase Order -->
                    <div x-show="sidebarOpen && userCategories.transport" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- PO Transport -->
                        <a href="{{ route('user.po-transports.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.po-transports.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-truck w-4 h-4 mr-3 {{ request()->routeIs('user.po-transports.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">PO Transport</span>
                        </a>
                    </div>
                </div>

                <!-- 📥 DOKUMEN KATEGORI -->
                <div class="space-y-1">
                    <!-- Category Header - Dokumen -->
                    <button @click="toggleUserCategory('documents')"
                            class="group w-full flex items-center justify-between px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 mr-3">
                                <i class="fas fa-folder text-red-200 group-hover:text-white"></i>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="truncate">Dokumen</span>
                        </div>
                        <i x-show="sidebarOpen" 
                           x-transition
                           :class="userCategories.documents ? 'fa-chevron-up' : 'fa-chevron-down'"
                           class="fas text-red-200 group-hover:text-white text-xs"></i>
                    </button>

                    <!-- Category Items - Dokumen -->
                    <div x-show="sidebarOpen && userCategories.documents" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="ml-6 space-y-1">
                        
                        <!-- Download Center -->
                        <a href="{{ route('user.documents.index') }}"
                           class="group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('user.documents.*') ? 'bg-red-400 text-white shadow-md' : 'text-red-100 hover:bg-red-500 hover:text-white' }}">
                            <i class="fas fa-download w-4 h-4 mr-3 {{ request()->routeIs('user.documents.*') ? 'text-white' : 'text-red-300' }}"></i>
                            <span class="truncate">Download Center</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Divider -->
            <div x-show="sidebarOpen" class="my-4 border-t border-red-500/30"></div>

            <!-- Quick Actions -->
            <div x-show="sidebarOpen" class="px-3 py-2">
                <p class="text-red-200 text-xs font-semibold uppercase tracking-wider">Quick Actions</p>
            </div>

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}"
               class="group w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'bg-white text-red-600 shadow-lg transform scale-105' : 'text-red-100 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105' }}"
               x-data="sidebarNotification()" x-init="loadUnreadCount()">
                <div class="flex items-center justify-center w-6 h-6 mr-3">
                    <i class="fas fa-bell {{ request()->routeIs('notifications.*') ? 'text-red-600' : 'text-red-200 group-hover:text-white' }} relative">
                        <span x-show="unreadCount > 0"
                              class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                    </i>
                </div>
                <span x-show="sidebarOpen" x-transition class="truncate">Notifikasi</span>
                <span x-show="sidebarOpen && unreadCount > 0"
                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                      class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold min-w-[20px] text-center"></span>
                @if(request()->routeIs('notifications.*'))
                    <div class="ml-auto w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                @endif
            </a>

            <!-- Help & Support -->
            <button class="group w-full flex items-center px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105">
                <div class="flex items-center justify-center w-6 h-6 mr-3">
                    <i class="fas fa-question-circle text-red-200 group-hover:text-white"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="truncate">Help & Support</span>
            </button>
        </nav>

        <!-- Settings & Logout -->
        <div class="p-3 border-t border-red-500/30 space-y-1">
            <!-- Settings -->
            <a href="{{ route('profile.edit') }}"
               class="group flex items-center px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-500 hover:text-white hover:shadow-lg hover:transform hover:scale-105">
                <div class="flex items-center justify-center w-6 h-6 mr-3">
                    <i class="fas fa-cog text-red-200 group-hover:text-white"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="truncate">Settings</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                        class="group w-full flex items-center px-3 py-3 text-sm font-medium text-red-100 rounded-xl transition-all duration-200 hover:bg-red-800 hover:text-white hover:shadow-lg hover:transform hover:scale-105"
                        onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <div class="flex items-center justify-center w-6 h-6 mr-3">
                        <i class="fas fa-sign-out-alt text-red-200 group-hover:text-white"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="truncate">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function sidebarCategories() {
    return {
        categories: {
            reports: true,    // Auto-expand if user in reports section
            po: false,        // Collapsed by default
            materials: false, // Material tracking category
            system: false     // Collapsed by default
        },

        userCategories: {
            reports: true,      // Auto-expand for user reports
            transport: false,   // Collapsed by default
            documents: false    // Collapsed by default
        },

        init() {
            // Auto-expand kategori berdasarkan route aktif
            const currentRoute = window.location.pathname;
            
            // ADMIN AUTO-EXPAND LOGIC
            // Expand Data & Laporan jika user di halaman terkait
            if (currentRoute.includes('/transactions') || 
                currentRoute.includes('/monthly-reports') || 
                currentRoute.includes('/loss-reports') ||
                currentRoute.includes('/mfo-requests')) {
                this.categories.reports = true;
            }
            
            // Expand Purchase Order jika user di halaman terkait
            if (currentRoute.includes('/po-materials') || 
                currentRoute.includes('/po-transports')) {
                this.categories.po = true;
            }
            
            // Expand Sistem Management jika user di halaman terkait
            if (currentRoute.includes('/master-data') || 
                currentRoute.includes('/users') || 
                currentRoute.includes('/documents')) {
                this.categories.system = true;
            }

            // USER AUTO-EXPAND LOGIC
            // Expand User Data & Laporan
            if (currentRoute.includes('user/transactions') || 
                currentRoute.includes('user/monthly-reports') || 
                currentRoute.includes('user/loss-reports') ||
                currentRoute.includes('user/mfo-requests')) {
                this.userCategories.reports = true;
            }
            
            // Expand User Purchase Order
            if (currentRoute.includes('user/po-transports')) {
                this.userCategories.transport = true;
            }
            
            // Expand User Documents
            if (currentRoute.includes('user/documents')) {
                this.userCategories.documents = true;
            }

            // Load saved states
            this.loadSavedState();
        },

        toggleCategory(category) {
            this.categories[category] = !this.categories[category];
            
            // Save state to localStorage
            localStorage.setItem('sidebarCategories', JSON.stringify(this.categories));
        },

        toggleUserCategory(category) {
            this.userCategories[category] = !this.userCategories[category];
            
            // Save state to localStorage
            localStorage.setItem('sidebarUserCategories', JSON.stringify(this.userCategories));
        },

        loadSavedState() {
            // Load admin saved state from localStorage
            const adminSaved = localStorage.getItem('sidebarCategories');
            if (adminSaved) {
                this.categories = { ...this.categories, ...JSON.parse(adminSaved) };
            }

            // Load user saved state from localStorage
            const userSaved = localStorage.getItem('sidebarUserCategories');
            if (userSaved) {
                this.userCategories = { ...this.userCategories, ...JSON.parse(userSaved) };
            }
        }
    }
}

function sidebarNotification() {
    return {
        unreadCount: 0,

        async loadUnreadCount() {
            try {
                const response = await fetch('/notifications/unread-count');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Failed to load unread count:', error);
            }
        }
    }
}

// Refresh notification count every 30 seconds
setInterval(() => {
    // Update all sidebar notification instances
    document.dispatchEvent(new CustomEvent('refresh-notifications'));
}, 30000);

// Listen for refresh event
document.addEventListener('refresh-notifications', () => {
    const elements = document.querySelectorAll('[x-data*="sidebarNotification"]');
    elements.forEach(el => {
        if (el._x_dataStack && el._x_dataStack[0].loadUnreadCount) {
            el._x_dataStack[0].loadUnreadCount();
        }
    });
});
</script>

<style>
/* Custom Scrollbar for Sidebar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: rgba(185, 28, 28, 0.3);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgba(220, 38, 38, 0.6);
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: rgba(220, 38, 38, 0.8);
}

/* Category Animation */
.category-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Sub-menu indent animation */
@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slide-in {
    animation: slideInLeft 0.2s ease-out;
}
</style>
