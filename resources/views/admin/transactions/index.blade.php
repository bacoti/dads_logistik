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
            project_id: '{{ request('project_id') }}',
            user_id: '{{ request('user_id') }}',
            date_from: '{{ request('date_from') }}',
            date_to: '{{ request('date_to') }}'
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
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="space-y-6">
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
                                   placeholder="Cari berdasarkan lokasi, cluster, site ID, atau catatan..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
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

                            <!-- Vendor Filter -->
                            <div>
                                <label for="vendor_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Vendor
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
                                            class="px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-xs">
                                        <option value="transaction_date" {{ request('sort_by') == 'transaction_date' ? 'selected' : '' }}>Tanggal Transaksi</option>
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                        <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Tipe</option>
                                    </select>
                                    <select name="sort_order" id="sort_order"
                                            class="px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-xs">
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <button type="submit"
                                        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Terapkan Filter
                                </button>

                                @if(request()->anyFilled(['search', 'type', 'vendor_id', 'project_id', 'user_id', 'date_from', 'date_to']))
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
                            @if(request()->anyFilled(['search', 'type', 'vendor_id', 'project_id', 'user_id', 'date_from', 'date_to']))
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
                            <!-- Export Button -->
                            <button class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex items-center"
                                    title="Export Data"
                                    @click="alert('Fitur export akan segera tersedia!')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium">Export</span>
                            </button>

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
                                                Lokasi & Vendor
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
                                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                                        <span class="text-blue-700 font-bold text-sm">#{{ substr($transaction->id, -3) }}</span>
                                                    </div>
                                                    <span class="text-sm font-mono font-medium text-gray-900">#{{ $transaction->id }}</span>
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
                                                    @if($transaction->vendor)
                                                        <div class="flex items-center text-xs text-blue-600 ml-6">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                            {{ $transaction->vendor->name }}
                                                        </div>
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
                            @if(request()->anyFilled(['search', 'type', 'vendor_id', 'project_id', 'user_id', 'date_from', 'date_to']))
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
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                                <span class="text-blue-700 font-bold text-sm">#{{ substr($transaction->id, -3) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-mono font-bold text-gray-900">#{{ $transaction->id }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i') }}</div>
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

                                        @if($transaction->vendor)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->vendor->name }}</div>
                                                    <div class="text-xs text-gray-500">Vendor</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

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
</x-admin-layout>
