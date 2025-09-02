<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Laporan Kehilangan') }}
        </h2>
    </x-slot>

    <!-- Alpine.js Data -->
    <div x-data="lossReportsData()">
        <div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-gray-100">
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-lg border border-green-300"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Enhanced Header with Statistics -->
            <div class="bg-gradient-to-br from-white to-red-50 rounded-2xl shadow-xl border border-red-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-700 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Kelola Laporan Kehilangan</h1>
                            <p class="text-red-100 text-lg">Review dan penanganan laporan kehilangan dari user lapangan</p>
                        </div>
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2">
                                <div class="text-2xl font-bold" x-text="stats.total">{{ $stats['total'] ?? 0 }}</div>
                                <div class="text-xs text-red-100">Total Laporan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-red-100 hover:border-red-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-red-600 font-bold text-3xl mb-1" x-text="stats.total">{{ $stats['total'] ?? 0 }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Total Laporan</div>
                                    </div>
                                    <div class="w-14 h-14 bg-red-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Semua Periode</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-yellow-100 hover:border-yellow-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-yellow-600 font-bold text-3xl mb-1" x-text="stats.pending">{{ $stats['pending'] ?? 0 }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Menunggu Review</div>
                                    </div>
                                    <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-yellow-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Perlu Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-blue-100 hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-blue-600 font-bold text-3xl mb-1" x-text="stats.reviewed">{{ $stats['reviewed'] ?? 0 }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Sedang Ditinjau</div>
                                    </div>
                                    <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="font-medium">Sedang Diperiksa</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-green-100 hover:border-green-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-green-600 font-bold text-3xl mb-1" x-text="stats.completed">{{ $stats['completed'] ?? 0 }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Selesai</div>
                                    </div>
                                    <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium">Telah Selesai</span>
                                </div>
                            </div>
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
                            <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian Laporan</h3>
                            @if(request()->anyFilled(['search', 'status', 'date_range', 'priority']))
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Aktif
                                </span>
                            @endif
                        </div>
                        <button @click="showAdvancedFilters = !showAdvancedFilters"
                                class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <span x-text="showAdvancedFilters ? 'Sembunyikan' : 'Tampilkan'" class="text-sm font-medium text-gray-700"></span>
                            <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200"
                                 :class="{'rotate-180': showAdvancedFilters}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Filter Form -->
                <div x-show="showAdvancedFilters"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-6">
                    <form method="GET" action="{{ route('admin.loss-reports.index') }}" class="space-y-6" x-ref="filterForm">
                        <!-- Quick Search Row -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pencarian Cepat
                            </label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan nama user, proyek, lokasi, atau material..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                        </div>

                        <!-- Advanced Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Status Laporan
                                </label>
                                <select name="status" id="status"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        🟡 Menunggu Review
                                    </option>
                                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>
                                        🔵 Sedang Ditinjau
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        🟢 Selesai
                                    </option>
                                </select>
                            </div>

                            <!-- Date Range Filter -->
                            <div>
                                <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Periode
                                </label>
                                <select name="date_range" id="date_range"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Waktu</option>
                                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>
                                        📅 Bulan Ini
                                    </option>
                                    <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>
                                        📅 Bulan Lalu
                                    </option>
                                    <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>
                                        📅 Tahun Ini
                                    </option>
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Prioritas Penanganan
                                </label>
                                <select name="priority" id="priority"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Prioritas</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>
                                        🚨 Urgent (>7 hari)
                                    </option>
                                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>
                                        ⏰ Normal (3-7 hari)
                                    </option>
                                    <option value="recent" {{ request('priority') == 'recent' ? 'selected' : '' }}>
                                        🆕 Baru (<3 hari)
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <button type="submit"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                                    </svg>
                                    Terapkan Filter
                                </button>

                                @if(request()->anyFilled(['search', 'status', 'date_range', 'priority']))
                                    <a href="{{ route('admin.loss-reports.index') }}"
                                       class="flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reset Filter
                                    </a>
                                @endif
                            </div>

                            <!-- Filter Summary -->
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Hasil:</span> {{ $reports->total() }} laporan ditemukan
                                @if(request()->anyFilled(['search', 'status', 'date_range', 'priority']))
                                    <span class="text-red-600 font-medium ml-1">(filtered)</span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- View Mode Toggle & Actions Bar -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                    <!-- View Mode Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-xl p-1">
                        <button @click="viewMode = 'table'"
                                :class="viewMode === 'table' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-18 8h18m-18 4h18"/>
                            </svg>
                            Table View
                        </button>
                        <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Grid View
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Bulk Actions -->
                        <div x-show="selectedReports.length > 0" x-transition class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">
                                <span x-text="selectedReports.length"></span> laporan dipilih
                            </span>
                        </div>

                        <!-- Export -->
                        <button class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export
                        </button>

                        <!-- Refresh -->
                        <button @click="window.location.reload()"
                                class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reports Content -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                @if($reports->count() > 0)
                    <!-- Table View -->
                    <div x-show="viewMode === 'table'" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">
                                        <input type="checkbox" @change="toggleAll($event)"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <span>User & Lokasi</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Proyek & Cluster
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Material & Tanggal
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status & Review
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" :value="{{ $report->id }}" x-model="selectedReports"
                                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        </td>

                                        <!-- User & Location -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-11 h-11 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                        {{ strtoupper(substr($report->user->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 space-y-1">
                                                    <div class="flex items-center space-x-2">
                                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                                            {{ $report->user->name }}
                                                        </p>
                                                        <div class="h-1 w-1 bg-gray-300 rounded-full"></div>
                                                        <span class="text-xs text-gray-500 font-medium">
                                                            ID: {{ $report->user->id }}
                                                        </span>
                                                    </div>
                                                    @if($report->project_location)
                                                        <div class="flex items-start">
                                                            <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            <div class="min-w-0">
                                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                                    {{ $report->project_location }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center">
                                                            <svg class="w-3.5 h-3.5 text-gray-300 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            <p class="text-xs text-gray-400 italic">
                                                                Lokasi belum diset
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Project & Cluster -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $report->project->name ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                @if($report->subProject)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $report->subProject->name }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($report->cluster)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                        </svg>
                                                        <span class="text-xs text-gray-500">
                                                            Cluster: {{ $report->cluster }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Material & Date -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                <div class="bg-red-50 rounded-lg px-3 py-2">
                                                    <div class="flex items-start">
                                                        <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs text-red-700 font-medium">Material Hilang:</p>
                                                            <p class="text-sm text-red-900 mt-1 font-semibold">
                                                                {{ $report->material_type }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-500">
                                                        Hilang: {{ $report->loss_date->format('d M Y') }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-500">
                                                        Dibuat: {{ $report->created_at->format('d M Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Status & Review -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '🟡', 'label' => 'Menunggu Review'],
                                                        'reviewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🔵', 'label' => 'Sedang Ditinjau'],
                                                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '🟢', 'label' => 'Selesai']
                                                    ];
                                                    $status = $statusConfig[$report->status] ?? $statusConfig['pending'];
                                                @endphp

                                                <div class="relative">
                                                    <select @change="updateStatus({{ $report->id }}, $event.target.value)"
                                                            class="w-full appearance-none {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-3 py-2 pr-8 rounded-lg border-0 focus:ring-2 focus:ring-red-500 cursor-pointer">
                                                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>
                                                            🟡 Menunggu Review
                                                        </option>
                                                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>
                                                            🔵 Sedang Ditinjau
                                                        </option>
                                                        <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>
                                                            🟢 Selesai
                                                        </option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                        <svg class="w-4 h-4 {{ $status['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </div>
                                                </div>

                                                @if($report->reviewed_at && $report->reviewed_by_user)
                                                    <div class="text-xs text-gray-500">
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            {{ $report->reviewed_by_user->name }}
                                                        </div>
                                                        <div class="flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $report->reviewed_at->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.loss-reports.show', $report) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>

                                                @if($report->supporting_document_path)
                                                    <a href="{{ route('admin.loss-reports.download', $report) }}"
                                                       class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Download
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endempty
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @forelse($reports as $report)
                            <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="p-6">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start space-x-3 flex-1">
                                            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg flex-shrink-0">
                                                {{ strtoupper(substr($report->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $report->user->name }}</h3>
                                                    <div class="h-1 w-1 bg-gray-300 rounded-full flex-shrink-0"></div>
                                                    <span class="text-xs text-gray-500 font-medium flex-shrink-0">ID: {{ $report->user->id }}</span>
                                                </div>
                                                @if($report->project_location)
                                                    <div class="flex items-start mt-1">
                                                        <svg class="w-3 h-3 text-gray-400 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $report->project_location }}</p>
                                                    </div>
                                                @else
                                                    <div class="flex items-center mt-1">
                                                        <svg class="w-3 h-3 text-gray-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <p class="text-xs text-gray-400 italic">Lokasi belum diset</p>
                                                    </div>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-1">{{ $report->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <input type="checkbox" :value="{{ $report->id }}" x-model="selectedReports"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500 flex-shrink-0 ml-2">
                                    </div>

                                    <!-- Project Info -->
                                    <div class="mb-4">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">{{ $report->project->name ?? 'N/A' }}</span>
                                        </div>
                                        @if($report->subProject)
                                            <p class="text-xs text-gray-500 ml-6">{{ $report->subProject->name }}</p>
                                        @endif
                                        <div class="flex items-center mt-2">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-xs text-gray-500">{{ $report->project_location ?? 'Lokasi tidak tersedia' }}</span>
                                        </div>
                                        @if($report->cluster)
                                            <div class="flex items-center mt-1">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                                <span class="text-xs text-gray-500">Cluster: {{ $report->cluster }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Material Loss -->
                                    <div class="mb-4 bg-red-50 rounded-lg p-3">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <div>
                                                <p class="text-xs text-red-700 font-medium mb-1">Material Hilang:</p>
                                                <p class="text-sm text-red-900 font-semibold">{{ $report->material_type }}</p>
                                                <p class="text-xs text-red-600 mt-1">
                                                    Tanggal: {{ $report->loss_date->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Review'],
                                                'reviewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Sedang Ditinjau'],
                                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai']
                                            ];
                                            $status = $statusConfig[$report->status] ?? $statusConfig['pending'];
                                        @endphp

                                        <div class="relative">
                                            <select @change="updateStatus({{ $report->id }}, $event.target.value)"
                                                    class="w-full appearance-none {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-3 py-2 pr-8 rounded-lg border-0 focus:ring-2 focus:ring-red-500 cursor-pointer">
                                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>
                                                    🟡 Menunggu Review
                                                </option>
                                                <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>
                                                    🔵 Sedang Ditinjau
                                                </option>
                                                <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>
                                                    🟢 Selesai
                                                </option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                <svg class="w-4 h-4 {{ $status['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>

                                        @if($report->reviewed_at && $report->reviewed_by_user)
                                            <div class="text-xs text-gray-500 mt-2 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $report->reviewed_by_user->name }} - {{ $report->reviewed_at->format('d M Y') }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.loss-reports.show', $report) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>

                                            @if($report->supporting_document_path)
                                                <a href="{{ route('admin.loss-reports.download', $report) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endempty
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Laporan Kehilangan</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            Saat ini belum ada laporan kehilangan material yang sesuai dengan filter yang dipilih.
                        </p>
                        <div class="flex items-center justify-center space-x-3">
                            <button @click="window.location.reload()"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Refresh Data
                            </button>
                            <button @click="showAdvancedFilters = false; resetFilters()"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="mt-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-700">
                                <span class="font-medium">{{ $reports->firstItem() ?? 0 }}</span>
                                <span class="mx-1">-</span>
                                <span class="font-medium">{{ $reports->lastItem() ?? 0 }}</span>
                                <span class="mx-1">dari</span>
                                <span class="font-medium">{{ $reports->total() }}</span>
                                <span class="ml-1">laporan</span>
                            </div>

                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                {{-- Previous Page Link --}}
                                @if ($reports->onFirstPage())
                                    <span class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-xs font-medium text-gray-400 cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="ml-1 hidden sm:inline">Previous</span>
                                    </span>
                                @else
                                    <a href="{{ $reports->previousPageUrl() }}"
                                       class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="ml-1 hidden sm:inline">Previous</span>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                                    @if ($page == $reports->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-red-300 bg-red-100 text-xs font-semibold text-red-700">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition-colors duration-200">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($reports->hasMorePages())
                                    <a href="{{ $reports->nextPageUrl() }}"
                                       class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition-colors duration-200">
                                        <span class="mr-1 hidden sm:inline">Next</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-xs font-medium text-gray-400 cursor-not-allowed">
                                        <span class="mr-1 hidden sm:inline">Next</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
    </div>
</div>

<script>
    function lossReportsData() {
        return {
            viewMode: 'table',
            showAdvancedFilters: false,
            selectedReports: [],
            stats: {
                total: {{ $stats['total'] ?? 0 }},
                pending: {{ $stats['pending'] ?? 0 }},
                reviewed: {{ $stats['reviewed'] ?? 0 }},
                completed: {{ $stats['completed'] ?? 0 }}
            },

            // Check if filters are active
            hasActiveFilters() {
                const params = new URLSearchParams(window.location.search);
                return params.has('search') || params.has('status') || params.has('date_range') || params.has('priority');
            },

            toggleAll(event) {
                if (event.target.checked) {
                    this.selectedReports = @json($reports->pluck('id'));
                } else {
                    this.selectedReports = [];
                }
            },

            async updateStatus(reportId, newStatus) {
                try {
                    const response = await fetch(`/admin/loss-reports/${reportId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Show success message
                        this.showNotification('Status berhasil diperbarui!', 'success');

                        // Update statistics
                        this.updateStats(data.stats);

                        // Refresh page to show changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error('Gagal memperbarui status');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('Gagal memperbarui status. Silakan coba lagi.', 'error');

                    // Reset the select to previous value
                    if (event && event.target) {
                        event.target.value = event.target.dataset.originalValue || 'pending';
                    }
                }
            },

            updateStats(newStats) {
                if (newStats) {
                    this.stats = { ...this.stats, ...newStats };
                }
            },

            showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white max-w-sm transform transition-all duration-500 translate-x-full`;

                if (type === 'success') {
                    notification.classList.add('bg-green-500');
                } else if (type === 'error') {
                    notification.classList.add('bg-red-500');
                } else {
                    notification.classList.add('bg-blue-500');
                }

                notification.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${type === 'success' ?
                                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                            }
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 500);
                }, 3000);
            },

            exportReports() {
                const selectedIds = this.selectedReports.length > 0 ? this.selectedReports.join(',') : 'all';
                const currentParams = new URLSearchParams(window.location.search);
                currentParams.set('export', 'true');
                if (selectedIds !== 'all') {
                    currentParams.set('ids', selectedIds);
                }

                window.location.href = `${window.location.pathname}?${currentParams.toString()}`;
            },

            resetFilters() {
                // Reset form
                if (this.$refs.filterForm) {
                    this.$refs.filterForm.reset();
                }

                // Clear URL parameters and reload
                window.location.href = window.location.pathname;
            }
        }
    }

    // Initialize select original values for reset functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select[onchange*="updateStatus"]').forEach(select => {
            select.dataset.originalValue = select.value;
        });
    });
</script>

</x-admin-layout>
