<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Transaksi') }}
        </h2>
    </x-slot>

    <!-- Alpine.js Data -->
    <div x-data="{
        viewMode: 'table',
        showFilters: true,
        selectedTransactions: [],
        stats: {
            total: {{ $transactions->total() }},
            penerimaan: {{ $transactions->where('type', 'penerimaan')->count() }},
            pengambilan: {{ $transactions->where('type', 'pengambilan')->count() }},
            pengembalian: {{ $transactions->where('type', 'pengembalian')->count() }},
            peminjaman: {{ $transactions->where('type', 'peminjaman')->count() }}
        },
        activeFilters: {
            search: '{{ request('search') }}',
            type: '{{ request('type') }}',
            vendor_id: '{{ request('vendor_id') }}',
            vendor_name: '{{ request('vendor_name') }}',
            project_id: '{{ request('project_id') }}',
            sub_project_id: '{{ request('sub_project_id') }}',
            user_id: '{{ request('user_id') }}',
            date_from: '{{ request('date_from') }}',
            date_to: '{{ request('date_to') }}',
            delivery_order_no: '{{ request('delivery_order_no') }}',
            delivery_note_no: '{{ request('delivery_note_no') }}',
            delivery_return_no: '{{ request('delivery_return_no') }}',
            return_destination: '{{ request('return_destination') }}'
        },
        hasActiveFilters() {
            return Object.values(this.activeFilters).some(filter => filter !== '' && filter !== null);
        }
    }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Enhanced Header with Statistics -->
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl border border-blue-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Transaksi Logistik</h1>
                            <p class="text-blue-100 text-lg">Monitoring dan analisis seluruh aktivitas transaksi material</p>
                        </div>
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2">
                                <div class="text-2xl font-bold" x-text="stats.total">{{ $transactions->total() }}</div>
                                <div class="text-xs text-blue-100">Total Transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-green-100 hover:border-green-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-green-600 font-bold text-3xl mb-1" x-text="stats.penerimaan">{{ $transactions->where('type', 'penerimaan')->count() }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Penerimaan</div>
                                    </div>
                                    <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span class="font-medium">Masuk Gudang</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-blue-100 hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-blue-600 font-bold text-3xl mb-1" x-text="stats.pengambilan">{{ $transactions->where('type', 'pengambilan')->count() }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Pengambilan</div>
                                    </div>
                                    <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                    <span class="font-medium">Keluar Gudang</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-orange-100 hover:border-orange-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-orange-600 font-bold text-3xl mb-1" x-text="stats.pengembalian">{{ $transactions->where('type', 'pengembalian')->count() }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Pengembalian</div>
                                    </div>
                                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-orange-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="font-medium">Kembali Gudang</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-purple-100 hover:border-purple-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-purple-600 font-bold text-3xl mb-1" x-text="stats.peminjaman">{{ $transactions->where('type', 'peminjaman')->count() }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Peminjaman</div>
                                    </div>
                                    <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-purple-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Sementara</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Dashboard Charts -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Chart 1: Transaksi per Project -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Distribusi Transaksi per Project</h3>
                                    <p class="text-sm text-gray-600">Overview volume aktivitas setiap project</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleChartType('projectChart')" class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg hover:bg-indigo-200 transition-colors">
                                    Switch View
                                </button>
                                <div class="text-xs text-gray-500">
                                    Updated: {{ now()->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <!-- Chart Loading State -->
                            <div class="chart-loading absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10" style="display: none;">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
                                    <p class="text-sm text-gray-500 mt-2">Memuat data chart...</p>
                                </div>
                            </div>
                            <canvas id="projectChart" width="400" height="300"></canvas>
                        </div>
                        <!-- Project Chart Legend/Details -->
                        <div id="projectChartDetails" class="mt-6 grid grid-cols-2 gap-4">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Transaksi per Lokasi & Timeline -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Aktivitas per Lokasi</h3>
                                    <p class="text-sm text-gray-600">Heat map dan trend aktivitas setiap lokasi</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select id="locationChartPeriod" onchange="updateLocationChartPeriod()" class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg border-0">
                                    <option value="7">7 Hari</option>
                                    <option value="30" selected>30 Hari</option>
                                    <option value="90">90 Hari</option>
                                </select>
                                <button onclick="toggleChartType('locationChart')" class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg hover:bg-emerald-200 transition-colors">
                                    Switch View
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <!-- Chart Loading State -->
                            <div class="chart-loading absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10" style="display: none;">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto"></div>
                                    <p class="text-sm text-gray-500 mt-2">Memuat data lokasi...</p>
                                </div>
                            </div>
                            <canvas id="locationChart" width="400" height="300"></canvas>
                        </div>
                        <!-- Location Chart Filters -->
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button onclick="filterLocationChart('all')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full transition-colors active">
                                Semua Tipe
                            </button>
                            <button onclick="filterLocationChart('penerimaan')" class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-full transition-colors">
                                Penerimaan
                            </button>
                            <button onclick="filterLocationChart('pengambilan')" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-full transition-colors">
                                Pengambilan
                            </button>
                            <button onclick="filterLocationChart('pengembalian')" class="text-xs bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-1 rounded-full transition-colors">
                                Pengembalian
                            </button>
                            <button onclick="filterLocationChart('peminjaman')" class="text-xs bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1 rounded-full transition-colors">
                                Peminjaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            
            <!-- Enhanced Filter Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Filter Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian Transaksi</h3>
                            <span x-show="hasActiveFilters()" class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Aktif
                            </span>
                        </div>
                        <button @click="showFilters = !showFilters"
                                class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <span x-text="showFilters ? 'Sembunyikan' : 'Tampilkan'" class="text-sm font-medium text-gray-700"></span>
                            <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200"
                                 :class="{'rotate-180': showFilters}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Filter Form -->
                <div x-show="showFilters"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-6">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="space-y-6" id="filterForm">
                        <!-- Quick Search Row -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pencarian Global
                            </label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan lokasi, cluster, user, project, vendor, nomor dokumen, atau catatan..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                            <p class="mt-1 text-xs text-gray-500">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Gunakan tombol "Terapkan Filter" untuk menerapkan pencarian
                            </p>
                        </div>

                        <!-- Advanced Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Type Filter -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Tipe Transaksi
                                </label>
                                <select name="type" id="type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Tipe</option>
                                    <option value="penerimaan" {{ request('type') == 'penerimaan' ? 'selected' : '' }}>
                                        🟢 Penerimaan
                                    </option>
                                    <option value="pengambilan" {{ request('type') == 'pengambilan' ? 'selected' : '' }}>
                                        🔵 Pengambilan
                                    </option>
                                    <option value="pengembalian" {{ request('type') == 'pengembalian' ? 'selected' : '' }}>
                                        🟠 Pengembalian
                                    </option>
                                    <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>
                                        🟣 Peminjaman
                                    </option>
                                </select>
                            </div>

                            <!-- Project Filter -->
                            <div>
                                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Project
                                </label>
                                <select name="project_id" id="project_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Project Filter -->
                            <div>
                                <label for="sub_project_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Sub Project
                                </label>
                                <select name="sub_project_id" id="sub_project_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Sub Project</option>
                                    @foreach($subProjects as $subProject)
                                        <option value="{{ $subProject->id }}" {{ request('sub_project_id') == $subProject->id ? 'selected' : '' }}>
                                            {{ $subProject->project->name }} - {{ $subProject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Vendor Filter -->
                            <div>
                                <label for="vendor_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Vendor (Terdaftar)
                                </label>
                                <select name="vendor_id" id="vendor_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Custom Vendor Name Filter -->
                            <div>
                                <label for="vendor_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Vendor (Input Manual)
                                </label>
                                <input type="text" name="vendor_name" id="vendor_name"
                                       value="{{ request('vendor_name') }}"
                                       placeholder="Cari vendor yang diinput manual..."
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                            </div>

                            <!-- User Filter -->
                            <div>
                                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    User Lapangan
                                </label>
                                <select name="user_id" id="user_id"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Document Number Filters -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                            <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Filter Berdasarkan Nomor Dokumen
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Delivery Order Number -->
                                <div>
                                    <label for="delivery_order_no" class="block text-xs font-medium text-gray-700 mb-1">
                                        No. DO (Delivery Order)
                                    </label>
                                    <input type="text" name="delivery_order_no" id="delivery_order_no"
                                           value="{{ request('delivery_order_no') }}"
                                           placeholder="Masukkan No. DO..."
                                           class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                </div>

                                <!-- Delivery Note Number -->
                                <div>
                                    <label for="delivery_note_no" class="block text-xs font-medium text-gray-700 mb-1">
                                        No. DN (Delivery Note)
                                    </label>
                                    <input type="text" name="delivery_note_no" id="delivery_note_no"
                                           value="{{ request('delivery_note_no') }}"
                                           placeholder="Masukkan No. DN..."
                                           class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                </div>

                                <!-- Delivery Return Number -->
                                <div>
                                    <label for="delivery_return_no" class="block text-xs font-medium text-gray-700 mb-1">
                                        No. DR (Delivery Return)
                                    </label>
                                    <input type="text" name="delivery_return_no" id="delivery_return_no"
                                           value="{{ request('delivery_return_no') }}"
                                           placeholder="Masukkan No. DR..."
                                           class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                </div>

                                <!-- Return Destination -->
                                <div>
                                    <label for="return_destination" class="block text-xs font-medium text-gray-700 mb-1">
                                        Tujuan Pengembalian
                                    </label>
                                    <input type="text" name="return_destination" id="return_destination"
                                           value="{{ request('return_destination') }}"
                                           placeholder="Tujuan pengembalian..."
                                           class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Dari Tanggal
                                </label>
                                <input type="date" name="date_from" id="date_from"
                                       value="{{ request('date_from') }}"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Sampai Tanggal
                                </label>
                                <input type="date" name="date_to" id="date_to"
                                       value="{{ request('date_to') }}"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                            </div>

                            <!-- Quick Date Presets -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Preset Tanggal
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" data-preset="today"
                                            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all duration-200">
                                        Hari Ini
                                    </button>
                                    <button type="button" data-preset="yesterday"
                                            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all duration-200">
                                        Kemarin
                                    </button>
                                    <button type="button" data-preset="week"
                                            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all duration-200">
                                        7 Hari
                                    </button>
                                    <button type="button" data-preset="month"
                                            class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-all duration-200">
                                        30 Hari
                                    </button>
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                                    </svg>
                                    Urutkan Berdasarkan
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="sort_by" id="sort_by"
                                            class="px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-xs"
                                            onchange="submitForm()">
                                        <option value="transaction_date" {{ request('sort_by') == 'transaction_date' ? 'selected' : '' }}>Tanggal Transaksi</option>
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                        <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Tipe</option>
                                        <option value="user_id" {{ request('sort_by') == 'user_id' ? 'selected' : '' }}>User</option>
                                        <option value="project_id" {{ request('sort_by') == 'project_id' ? 'selected' : '' }}>Project</option>
                                    </select>
                                    <select name="sort_order" id="sort_order"
                                            class="px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-xs"
                                            onchange="submitForm()">
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <!-- Apply Filter Button -->
                                <button type="submit" id="applyFilterBtn"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Terapkan Filter
                                </button>

                                @if(request()->anyFilled(['search', 'type', 'vendor_id', 'vendor_name', 'project_id', 'sub_project_id', 'user_id', 'date_from', 'date_to', 'delivery_order_no', 'delivery_note_no', 'delivery_return_no', 'return_destination']))
                                    <a href="{{ route('admin.transactions.index') }}"
                                       class="flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reset Filter
                                    </a>
                                @endif
                            </div>

                            <!-- Filter Summary -->
                            @if(request()->anyFilled(['search', 'type', 'vendor_id', 'vendor_name', 'project_id', 'sub_project_id', 'user_id', 'date_from', 'date_to', 'delivery_order_no', 'delivery_note_no', 'delivery_return_no', 'return_destination']))
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Hasil:</span> {{ $transactions->total() }} transaksi ditemukan
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Enhanced Transactions Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Table Header with View Controls -->
                <div class="bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 px-6 py-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-xl font-bold text-gray-900">Data Transaksi</h3>
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $transactions->total() }} transaksi
                            </span>
                            @if(request()->anyFilled(['search', 'type', 'vendor_id', 'project_id', 'user_id', 'date_from', 'date_to']))
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Filtered
                                </span>
                            @endif
                        </div>

                        <!-- View Mode Toggle -->
                        <div class="flex items-center space-x-4">
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" style="display: none;" class="flex items-center text-sm text-blue-600 bg-blue-50 px-4 py-2 rounded-xl border border-blue-200">
                                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="font-medium">Memuat data...</span>
                            </div>

                            <!-- Export Button -->
                            <a href="{{ route('admin.transactions.export') }}?{{ http_build_query(request()->all()) }}"
                               class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex items-center"
                               title="Export Data Transaksi Detail ke Excel">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium">Export Excel</span>
                            </a>

                            <!-- View Mode Selector -->
                            <div class="flex items-center bg-gray-100 rounded-xl p-1">
                                <button @click="viewMode = 'table'"
                                        :class="viewMode === 'table' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    Tabel
                                </button>
                                <button @click="viewMode = 'grid'"
                                        :class="viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    Grid
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table View -->
                <div x-show="viewMode === 'table'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                ID Transaksi
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Tanggal & Tipe
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                User & Project
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                                Lokasi & Vendor/Tujuan
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                No. Dokumen
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                Items
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                                </svg>
                                                Aksi
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 transition-all duration-200 group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                                        <span class="text-blue-700 font-bold text-sm">#{{ $transaction->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="mr-3">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('H:i') }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                            @switch($transaction->type)
                                                                @case('penerimaan') bg-green-100 text-green-800 @break
                                                                @case('pengambilan') bg-blue-100 text-blue-800 @break
                                                                @case('pengembalian') bg-orange-100 text-orange-800 @break
                                                                @case('peminjaman') bg-purple-100 text-purple-800 @break
                                                                @default bg-gray-100 text-gray-800
                                                            @endswitch
                                                        ">
                                                            <div class="w-2 h-2 rounded-full mr-2
                                                                @switch($transaction->type)
                                                                    @case('penerimaan') bg-green-500 @break
                                                                    @case('pengambilan') bg-blue-500 @break
                                                                    @case('pengembalian') bg-orange-500 @break
                                                                    @case('peminjaman') bg-purple-500 @break
                                                                    @default bg-gray-500
                                                                @endswitch
                                                            "></div>
                                                            {{ ucfirst($transaction->type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-gray-700 font-bold text-sm">{{ strtoupper(substr($transaction->user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            <strong>{{ $transaction->project->name }}</strong>
                                                            @if($transaction->subProject)
                                                                <br><span class="text-gray-400">{{ $transaction->subProject->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center text-sm font-medium text-gray-900">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        </svg>
                                                        {{ $transaction->location }}
                                                    </div>
                                                    @if($transaction->cluster)
                                                        <div class="text-xs text-gray-500 ml-6">{{ $transaction->cluster }}</div>
                                                    @endif
                                                    
                                                    @if($transaction->type == 'pengembalian' && $transaction->return_destination)
                                                        <div class="flex items-center text-xs text-orange-600 ml-6">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                            </svg>
                                                            Tujuan: {{ $transaction->return_destination }}
                                                        </div>
                                                    @elseif($transaction->vendor || $transaction->vendor_name)
                                                        <div class="flex items-center text-xs text-blue-600 ml-6">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                            Vendor: 
                                                            @if($transaction->vendor)
                                                                {{ $transaction->vendor->name }}
                                                            @else
                                                                {{ $transaction->vendor_name }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-6 py-4">
                                                <div class="space-y-2">
                                                    @if($transaction->type == 'penerimaan')
                                                        @if($transaction->delivery_order_no)
                                                            <div class="flex items-center text-xs">
                                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                    </svg>
                                                                    DO: {{ $transaction->delivery_order_no }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if($transaction->delivery_note_no)
                                                            <div class="flex items-center text-xs">
                                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                    </svg>
                                                                    DN: {{ $transaction->delivery_note_no }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @elseif($transaction->type == 'pengembalian' && $transaction->delivery_return_no)
                                                        <div class="flex items-center text-xs">
                                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                                </svg>
                                                                DR: {{ $transaction->delivery_return_no }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(($transaction->type == 'penerimaan' && (!$transaction->delivery_order_no || !$transaction->delivery_note_no)) || 
                                                        ($transaction->type == 'pengembalian' && !$transaction->delivery_return_no))
                                                        <span class="text-xs text-gray-400 italic">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 rounded-xl text-sm font-bold shadow-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    {{ $transaction->details->count() }} item
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.transactions.show', $transaction) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-xl text-sm font-medium transition-all duration-200 hover:scale-105 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Enhanced Empty State -->
                        <div class="text-center py-20">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-32 h-32 bg-gray-100 rounded-full opacity-20"></div>
                                </div>
                                <div class="relative">
                                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            @if(request()->anyFilled(['search', 'type', 'vendor_id', 'vendor_name', 'project_id', 'sub_project_id', 'user_id', 'date_from', 'date_to', 'delivery_order_no', 'delivery_note_no', 'delivery_return_no', 'return_destination']))
                                <h3 class="mt-6 text-xl font-semibold text-gray-900">Transaksi tidak ditemukan</h3>
                                <p class="mt-2 text-gray-500">Tidak ada transaksi yang cocok dengan filter yang diterapkan.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.transactions.index') }}"
                                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Hapus Filter
                                    </a>
                                </div>
                            @else
                                <h3 class="mt-6 text-xl font-semibold text-gray-900">Belum ada transaksi</h3>
                                <p class="mt-2 text-gray-500">Transaksi logistik akan muncul di sini setelah user lapangan melakukan aktivitas.</p>
                            @endif
                        </div>
                    @endif
                <!-- Enhanced Grid View -->
                <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if($transactions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
                            @foreach($transactions as $transaction)
                                <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                                    <!-- Transaction Header -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                <span class="text-blue-700 font-bold text-sm">#{{ $transaction->id }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                                @switch($transaction->type)
                                                    @case('penerimaan') bg-green-100 text-green-800 border border-green-200 @break
                                                    @case('pengambilan') bg-blue-100 text-blue-800 border border-blue-200 @break
                                                    @case('pengembalian') bg-orange-100 text-orange-800 border border-orange-200 @break
                                                    @case('peminjaman') bg-purple-100 text-purple-800 border border-purple-200 @break
                                                    @default bg-gray-100 text-gray-800 border border-gray-200
                                                @endswitch
                                            ">
                                                <div class="w-2 h-2 rounded-full mr-2
                                                    @switch($transaction->type)
                                                        @case('penerimaan') bg-green-500 @break
                                                        @case('pengambilan') bg-blue-500 @break
                                                        @case('pengembalian') bg-orange-500 @break
                                                        @case('peminjaman') bg-purple-500 @break
                                                        @default bg-gray-500
                                                    @endswitch
                                                "></div>
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- User & Project Info -->
                                    <div class="space-y-3 mb-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                                <div class="text-xs text-gray-500">User Lapangan</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->project->name }}</div>
                                                @if($transaction->subProject)
                                                    <div class="text-xs text-gray-500">{{ $transaction->subProject->name }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->location }}</div>
                                                @if($transaction->cluster)
                                                    <div class="text-xs text-gray-500">{{ $transaction->cluster }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($transaction->type == 'pengembalian' && $transaction->return_destination)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->return_destination }}</div>
                                                    <div class="text-xs text-gray-500">Tujuan Pengembalian</div>
                                                </div>
                                            </div>
                                        @elseif($transaction->vendor || $transaction->vendor_name)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if($transaction->vendor)
                                                            {{ $transaction->vendor->name }}
                                                        @else
                                                            {{ $transaction->vendor_name }}
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-gray-500">Vendor</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Document Numbers -->
                                    @if($transaction->type == 'penerimaan' && ($transaction->delivery_order_no || $transaction->delivery_note_no))
                                        <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                            <div class="text-xs font-semibold text-green-700 mb-2">Dokumen Penerimaan:</div>
                                            <div class="space-y-1">
                                                @if($transaction->delivery_order_no)
                                                    <div class="inline-block mr-2 mb-1">
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                            DO: {{ $transaction->delivery_order_no }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($transaction->delivery_note_no)
                                                    <div class="inline-block mr-2 mb-1">
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                            DN: {{ $transaction->delivery_note_no }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($transaction->type == 'pengembalian' && $transaction->delivery_return_no)
                                        <div class="mb-4 p-3 bg-orange-50 rounded-lg border border-orange-200">
                                            <div class="text-xs font-semibold text-orange-700 mb-2">Dokumen Pengembalian:</div>
                                            <div class="inline-block">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                                    DR: {{ $transaction->delivery_return_no }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Items Count & Action -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 rounded-lg text-sm font-bold">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            {{ $transaction->details->count() }} Items
                                        </div>

                                        <a href="{{ route('admin.transactions.show', $transaction) }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all duration-200 hover:scale-105 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Grid Empty State -->
                        <div class="text-center py-20">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-32 h-32 bg-gray-100 rounded-full opacity-20"></div>
                                </div>
                                <div class="relative">
                                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-gray-900">Belum ada transaksi</h3>
                            <p class="mt-2 text-gray-500 mb-8">Tampilan grid untuk transaksi akan muncul setelah ada aktivitas dari user lapangan.</p>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Pagination -->
                @if($transactions->hasPages())
                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                            <div class="flex items-center text-sm text-gray-700">
                                <span class="font-medium">Menampilkan</span>
                                <span class="mx-1 font-bold text-blue-600">{{ $transactions->firstItem() }}</span>
                                <span>sampai</span>
                                <span class="mx-1 font-bold text-blue-600">{{ $transactions->lastItem() }}</span>
                                <span>dari</span>
                                <span class="mx-1 font-bold text-blue-600">{{ $transactions->total() }}</span>
                                <span>transaksi</span>
                            </div>

                            <div class="flex items-center">
                                {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Filter Variables
        let isSubmitting = false;
        
        // Form submission with chart update
        function submitFormWithChart() {
            if (isSubmitting) return;
            
            showLoading(true);
            isSubmitting = true;
            
            // Get current form data for chart update
            const formData = new FormData(document.getElementById('filterForm'));
            const filterParams = new URLSearchParams();
            
            // Build filter parameters for chart API
            for (let [key, value] of formData.entries()) {
                if (value) {
                    filterParams.append(key, value);
                }
            }
            
            // Update charts before submitting form
            updateChartsWithFilters(filterParams.toString());
            
            // Submit form after short delay
            setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 100);
        }
        
        // Update charts with current filter parameters
        function updateChartsWithFilters(filterQueryString) {
            showChartLoading(true);
            
            const url = '{{ route("admin.transactions.chart-data") }}' + (filterQueryString ? '?' + filterQueryString : '');
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                chartData = data;
                updateProjectChart(data.projectData);
                updateLocationChart(data.locationData);
                updateChartDetails(data);
                showChartLoading(false);
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                showChartLoading(false);
                showChartError();
            });
        }
        
        // Show/hide loading indicator
        function showLoading(show, text = 'Memuat data...') {
            const loadingIndicator = document.getElementById('loadingIndicator');
            if (loadingIndicator) {
                const loadingText = loadingIndicator.querySelector('span');
                
                if (show) {
                    if (loadingText) loadingText.textContent = text;
                    loadingIndicator.style.display = 'flex';
                } else {
                    loadingIndicator.style.display = 'none';
                }
            }
        }
        
        // Enhanced date preset functions
        function setDateRange(preset) {
            const today = new Date();
            const dateFromInput = document.getElementById('date_from');
            const dateToInput = document.getElementById('date_to');
            
            let fromDate, toDate;
            
            switch(preset) {
                case 'today':
                    fromDate = toDate = today;
                    break;
                case 'yesterday':
                    fromDate = toDate = new Date(today.getTime() - 24 * 60 * 60 * 1000);
                    break;
                case 'week':
                    fromDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    toDate = today;
                    break;
                case 'month':
                    fromDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                    toDate = today;
                    break;
            }
            
            dateFromInput.value = fromDate.toISOString().split('T')[0];
            dateToInput.value = toDate.toISOString().split('T')[0];
            
            // Auto-submit after setting dates
            showLoading(true, 'Menerapkan filter tanggal...');
            setTimeout(submitForm, 300);
        }
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add form submit event listener
            const filterForm = document.getElementById('filterForm');
            const applyFilterBtn = document.getElementById('applyFilterBtn');
            
            if (filterForm && applyFilterBtn) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitFormWithChart();
                });
                
                applyFilterBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    submitFormWithChart();
                });
            }
            
            // Add date preset functionality
            const presetButtons = document.querySelectorAll('[data-preset]');
            presetButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const preset = this.getAttribute('data-preset');
                    setDateRange(preset);
                });
            });
            
            // Add visual feedback for form elements
            const formElements = document.querySelectorAll('#filterForm select, #filterForm input');
            formElements.forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.borderColor = '#3B82F6';
                    this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
                });
                
                element.addEventListener('blur', function() {
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
            });
        });
        
        // Handle page unload to show loading state
        window.addEventListener('beforeunload', function() {
            showLoading(true, 'Memuat halaman...');
        });
        
        // Handle back button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                showLoading(false);
                isSubmitting = false;
            }
        });
        
        // Clear search on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const searchInput = document.getElementById('search');
                if (searchInput && searchInput === document.activeElement) {
                    searchInput.value = '';
                    searchInput.focus();
                }
            }
        });
    </script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
    
    <!-- Analytics Charts Script -->
    <script>
        // Chart instances
        let projectChart, locationChart;
        let chartData = {};

        // Initialize Charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            loadChartData();
        });

        // Initialize Chart Instances
        function initializeCharts() {
            console.log('Initializing charts...');
            
            // Project Chart
            const projectCtx = document.getElementById('projectChart');
            console.log('Project chart canvas element:', projectCtx);
            
            if (projectCtx) {
                projectChart = new Chart(projectCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: [
                                '#8B5CF6', // Purple
                                '#06B6D4', // Cyan
                                '#10B981', // Emerald
                                '#F59E0B', // Amber
                                '#EF4444', // Red
                                '#6366F1', // Indigo
                                '#EC4899', // Pink
                                '#84CC16'  // Lime
                            ],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverBorderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 12 },
                                cornerRadius: 8,
                                caretPadding: 10,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' transaksi';
                                    }
                                }
                            }
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                            duration: 1500,
                            easing: 'easeInOutQuart'
                        },
                        cutout: '60%'
                    }
                });
                console.log('Project chart initialized:', projectChart);
            } else {
                console.error('Project chart canvas element not found');
            }

            // Location Chart
            const locationCtx = document.getElementById('locationChart');
            console.log('Location chart canvas element:', locationCtx);
            
            if (locationCtx) {
                locationChart = new Chart(locationCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Penerimaan',
                            data: [],
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }, {
                            label: 'Pengambilan',
                            data: [],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }, {
                            label: 'Pengembalian',
                            data: [],
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }, {
                            label: 'Peminjaman',
                            data: [],
                            backgroundColor: 'rgba(147, 51, 234, 0.8)',
                            borderColor: 'rgba(147, 51, 234, 1)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'rect',
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: { size: 14, weight: 'bold' },
                                bodyFont: { size: 12 },
                                cornerRadius: 8,
                                caretPadding: 10,
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [5, 5],
                                    color: 'rgba(156, 163, 175, 0.3)'
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    stepSize: 1
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeInOutQuart'
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });
                console.log('Location chart initialized:', locationChart);
            } else {
                console.error('Location chart canvas element not found');
            }
            
            console.log('Charts initialization complete. Project chart:', !!projectChart, 'Location chart:', !!locationChart);
        }

        // Load Chart Data from API
        function loadChartData() {
            console.log('Loading chart data...');
            showChartLoading(true);
            
            fetch('{{ route("admin.transactions.chart-data") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Chart API Response:', response);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Chart data received:', data);
                chartData = data;
                updateProjectChart(data.projectData);
                updateLocationChart(data.locationData);
                updateChartDetails(data);
                showChartLoading(false);
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                showChartLoading(false);
                showChartError();
            });
        }

        // Update Project Chart
        function updateProjectChart(projectData) {
            console.log('Updating project chart with data:', projectData);
            if (projectChart) {
                let labels, data;
                
                if (projectData && projectData.length > 0) {
                    labels = projectData.map(item => item.name);
                    data = projectData.map(item => item.count);
                } else {
                    // Fallback for empty data
                    labels = ['Tidak ada data'];
                    data = [1];
                }
                
                console.log('Project chart labels:', labels);
                console.log('Project chart data:', data);
                
                projectChart.data.labels = labels;
                projectChart.data.datasets[0].data = data;
                
                // Update colors for empty state
                if (projectData && projectData.length > 0) {
                    projectChart.data.datasets[0].backgroundColor = [
                        '#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', 
                        '#EF4444', '#6366F1', '#EC4899', '#84CC16'
                    ];
                } else {
                    projectChart.data.datasets[0].backgroundColor = ['#D1D5DB'];
                }
                
                projectChart.update('active');
                
                // Update chart details
                updateProjectDetails(projectData);
            } else {
                console.error('Project chart instance not available');
            }
        }

        // Update Location Chart
        function updateLocationChart(locationData) {
            console.log('Updating location chart with data:', locationData);
            
            if (locationChart) {
                const types = ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'];
                
                if (locationData && Object.keys(locationData).length > 0) {
                    const locations = Object.keys(locationData);
                    locationChart.data.labels = locations;
                    
                    types.forEach((type, index) => {
                        if (locationChart.data.datasets[index]) {
                            locationChart.data.datasets[index].data = locations.map(location => 
                                (locationData[location] && locationData[location][type]) ? locationData[location][type] : 0
                            );
                        }
                    });
                } else {
                    // Fallback for empty data
                    locationChart.data.labels = ['Tidak ada data'];
                    types.forEach((type, index) => {
                        if (locationChart.data.datasets[index]) {
                            locationChart.data.datasets[index].data = [0];
                        }
                    });
                }
                
                locationChart.update('active');
                console.log('Location chart updated successfully');
            } else {
                console.error('Location chart instance not available');
            }
        }

        // Update Chart Details
        function updateChartDetails(data) {
            // Update total statistics
            const totalStats = document.querySelectorAll('.stat-number');
            if (totalStats.length >= 4) {
                totalStats[0].textContent = data.totals?.penerimaan || 0;
                totalStats[1].textContent = data.totals?.pengambilan || 0;
                totalStats[2].textContent = data.totals?.pengembalian || 0;
                totalStats[3].textContent = data.totals?.peminjaman || 0;
            }
        }

        // Update Project Chart Details
        function updateProjectDetails(projectData) {
            const detailsContainer = document.getElementById('projectChartDetails');
            if (detailsContainer) {
                if (projectData && projectData.length > 0) {
                    detailsContainer.innerHTML = projectData.map(item => {
                        return `
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 truncate">${item.name}</span>
                                    <span class="text-sm font-bold text-indigo-600">${item.count}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    detailsContainer.innerHTML = '<div class="text-center text-gray-400 py-6">Tidak ada data project</div>';
                }
            }
        }

        // Chart Utility Functions
        function toggleChartType(chartId) {
            if (chartId === 'projectChart' && projectChart) {
                projectChart.config.type = projectChart.config.type === 'doughnut' ? 'bar' : 'doughnut';
                projectChart.update('active');
            } else if (chartId === 'locationChart' && locationChart) {
                locationChart.config.type = locationChart.config.type === 'bar' ? 'line' : 'bar';
                locationChart.update('active');
            }
        }

        function updateLocationChartPeriod() {
            const period = document.getElementById('locationChartPeriod').value;
            // Reload data with period parameter
            fetch(`{{ route("admin.transactions.chart-data") }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    updateLocationChart(data.locationData);
                });
        }

        function filterLocationChart(type) {
            // Update active button
            document.querySelectorAll('button[onclick*="filterLocationChart"]').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            event.target.classList.remove('bg-gray-100', 'text-gray-700');
            event.target.classList.add('active', 'bg-blue-500', 'text-white');
            
            if (locationChart) {
                if (type === 'all') {
                    locationChart.data.datasets.forEach(dataset => {
                        dataset.hidden = false;
                    });
                } else {
                    locationChart.data.datasets.forEach((dataset, index) => {
                        const types = ['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'];
                        dataset.hidden = types[index] !== type;
                    });
                }
                locationChart.update();
            }
        }

        // Loading and Error States
        function showChartLoading(show) {
            const loadingElements = document.querySelectorAll('.chart-loading');
            loadingElements.forEach(el => {
                el.style.display = show ? 'flex' : 'none';
            });
        }

        function showChartError() {
            console.log('Error loading chart data - showing fallback message');
            // Add error handling UI if needed
        }

        // Auto-refresh charts every 5 minutes
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                loadChartData();
            }
        }, 5 * 60 * 1000);

        // Refresh charts when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                setTimeout(loadChartData, 1000);
            }
        });
    </script>
</x-admin-layout>
