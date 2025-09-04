<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Laporan Bulanan') }}
        </h2>
    </x-slot>

    <!-- Alpine.js Data -->
    <div x-data="{
        viewMode: 'table',
        showFilters: true,
        selectedReports: [],
        stats: {
            total: {{ $stats['total'] }},
            pending: {{ $stats['pending'] }},
            reviewed: {{ $stats['reviewed'] ?? 0 }},
            approved: {{ $stats['approved'] }},
            rejected: {{ $stats['rejected'] }}
        },
        activeFilters: {
            search: '{{ request('search') }}',
            status: '{{ request('status') }}',
            user_id: '{{ request('user_id') }}',
            date_range: '{{ request('date_range') }}'
        },
        hasActiveFilters() {
            return Object.values(this.activeFilters).some(filter => filter !== '' && filter !== null);
        },
        updateStatus(reportId, status) {
            const reason = status === 'rejected' ? prompt('Alasan penolakan (opsional):') : null;
            if (status === 'rejected' && reason === null) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/monthly-reports/${reportId}/update-status`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);

            if (reason) {
                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'admin_notes';
                notesInput.value = reason;
                form.appendChild(notesInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    }" class="py-12">
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
            <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-xl border border-purple-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-700 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Kelola Laporan Bulanan</h1>
                            <p class="text-purple-100 text-lg">Review dan persetujuan laporan bulanan dari user lapangan</p>
                        </div>
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2">
                                <div class="text-2xl font-bold" x-text="stats.total">{{ $stats['total'] }}</div>
                                <div class="text-xs text-purple-100">Total Laporan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-yellow-100 hover:border-yellow-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-yellow-600 font-bold text-3xl mb-1" x-text="stats.pending">{{ $stats['pending'] }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Pending Review</div>
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
                                        <div class="text-gray-600 text-sm font-medium">Reviewed</div>
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
                                    <span class="font-medium">Sudah Diperiksa</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-green-100 hover:border-green-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-green-600 font-bold text-3xl mb-1" x-text="stats.approved">{{ $stats['approved'] }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Approved</div>
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
                                    <span class="font-medium">Disetujui</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                            <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-red-100 hover:border-red-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-red-600 font-bold text-3xl mb-1" x-text="stats.rejected">{{ $stats['rejected'] }}</div>
                                        <div class="text-gray-600 text-sm font-medium">Rejected</div>
                                    </div>
                                    <div class="w-14 h-14 bg-red-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="font-medium">Ditolak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Charts Analytics Section (Simplified - 2 Charts) -->
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl border border-blue-200 overflow-hidden">
                <!-- Header banner removed per request: charts section will show only charts -->

                <!-- Chart Loading State -->
                <div id="chart-loading" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                        <p class="text-gray-600">Memuat data grafik...</p>
                    </div>
                </div>

                <!-- Charts Container (2 Charts) -->
                <div id="charts-container" class="hidden p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Project Distribution Chart -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">Distribusi Laporan per Proyek</h4>
                                    <p class="text-sm text-gray-600">Distribusi jumlah laporan berdasarkan proyek</p>
                                </div>
                                <button onclick="toggleChartType('statusChart')" 
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                    Toggle Type
                                </button>
                            </div>
                            <div class="relative" style="height: 250px;">
                                <canvas id="statusChart"></canvas>
                            </div>
                            <div id="status-chart-details" class="mt-4 grid grid-cols-2 gap-4">
                                <!-- Dynamic status details will be inserted here -->
                            </div>
                        </div>

                        <!-- Location Reports Chart -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">Laporan Berdasarkan Lokasi</h4>
                                    <p class="text-sm text-gray-600">Distribusi jumlah laporan berdasarkan lokasi</p>
                                </div>
                            </div>
                            <div class="relative" style="height: 250px;">
                                <canvas id="userChart"></canvas>
                            </div>
                            <div id="user-chart-details" class="mt-4 text-center">
                                <!-- Dynamic user details will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Error State -->
                <div id="chart-error" class="hidden p-8 text-center">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gagal Memuat Grafik</h3>
                    <p class="text-gray-600 mb-4">Terjadi kesalahan saat memuat data grafik</p>
                    <button onclick="loadChartsData()" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Enhanced Filter Section (cleaned) -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian Laporan</h3>
                            <span x-show="hasActiveFilters()" class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
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

                <div x-show="showFilters"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-6">
                    <form id="filterForm" method="GET" action="{{ route('admin.monthly-reports.index') }}" class="space-y-6" @submit.prevent="submitFormWithChart()">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pencarian Cepat
                            </label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama user, proyek, lokasi, atau periode..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Laporan</label>
                                <select name="status" id="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 Pending Review</option>
                                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>🔵 Reviewed</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>🟢 Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>🔴 Rejected</option>
                                </select>
                            </div>

                            <div>
                                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">User Lapangan</label>
                                <select name="user_id" id="user_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">Proyek</label>
                                <select name="project_id" id="project_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Proyek</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-2">Periode</label>
                                <select name="date_range" id="date_range" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Waktu</option>
                                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>📅 Bulan Ini</option>
                                    <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>📅 Bulan Lalu</option>
                                    <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>📅 Tahun Ini</option>
                                </select>
                            </div>

                            <div>
                                <label for="report_period" class="block text-sm font-semibold text-gray-700 mb-2">Bulan Laporan</label>
                                <select name="report_period" id="report_period" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm text-sm">
                                    <option value="">Semua Bulan</option>
                                    @foreach(['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'] as $val => $label)
                                        <option value="{{ $val }}" {{ request('report_period') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <button type="submit" class="flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/></svg>
                                    Terapkan Filter
                                </button>

                                @if(request()->anyFilled(['search', 'status', 'user_id', 'project_id', 'date_range', 'report_period']))
                                    <a href="{{ route('admin.monthly-reports.index') }}" class="flex items-center px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">Reset Filter</a>
                                @endif
                            </div>

                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Hasil:</span> {{ $reports->total() }} laporan ditemukan
                                @if(request()->anyFilled(['search', 'status', 'user_id', 'project_id', 'date_range', 'report_period']))
                                    <span class="text-purple-600 font-medium ml-1">(filtered)</span>
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
                                :class="viewMode === 'table' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18m-18 8h18m-18 4h18"/>
                            </svg>
                            Table View
                        </button>
                        <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
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
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                    Bulk Actions
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                    <div class="py-1">
                                        <button @click="bulkUpdateStatus('approved')"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                            Approve Selected
                                        </button>
                                        <button @click="bulkUpdateStatus('rejected')"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                                            Reject Selected
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export -->
                        <a href="{{ route('admin.monthly-reports.export') }}?{{ http_build_query(request()->all()) }}"
                           class="flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </a>

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
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-1">
                                            <span>User & Lokasi</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Periode & Tanggal
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Catatan Laporan
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
                                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        </td>

                                        <!-- User & Location -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                        {{ substr($report->user->name, 0, 2) }}
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                                        {{ $report->user->name }}
                                                    </p>
                                                    <div class="flex items-center mt-1">
                                                        <svg class="w-3 h-3 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <p class="text-xs text-gray-500 truncate">
                                                            {{ $report->project_location ?? 'Lokasi tidak tersedia' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Period & Date -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-1">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $report->report_period ?? 'Tidak ada periode' }} {{ $report->report_date ? $report->report_date->format('Y') : '' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $report->created_at->format('d M Y, H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Report Notes -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @if($report->notes)
                                                    <div class="bg-blue-50 rounded-lg px-3 py-2">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                            </svg>
                                                            <div class="flex-1">
                                                                <p class="text-xs text-blue-700 font-medium">Catatan User:</p>
                                                                <p class="text-sm text-blue-900 mt-1 leading-relaxed">
                                                                    {{ Str::limit($report->notes, 100) }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($report->admin_notes)
                                                    <div class="bg-amber-50 rounded-lg px-3 py-2">
                                                        <div class="flex items-start">
                                                            <svg class="w-4 h-4 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            <div class="flex-1">
                                                                <p class="text-xs text-amber-700 font-medium">Catatan Admin:</p>
                                                                <p class="text-sm text-amber-900 mt-1 leading-relaxed">
                                                                    {{ Str::limit($report->admin_notes, 100) }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(!$report->notes && !$report->admin_notes)
                                                    <div class="bg-gray-50 rounded-lg px-3 py-2">
                                                        <div class="flex items-center justify-center py-2">
                                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-xs text-gray-500">Tidak ada catatan</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Status & Review -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '🟡', 'label' => 'Pending Review'],
                                                        'reviewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🔵', 'label' => 'Reviewed'],
                                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '🟢', 'label' => 'Approved'],
                                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '🔴', 'label' => 'Rejected']
                                                    ];
                                                    $status = $statusConfig[$report->status] ?? $statusConfig['pending'];
                                                @endphp

                                                <div class="relative">
                                                    <select @change="updateStatus({{ $report->id }}, $event.target.value)"
                                                            class="w-full appearance-none {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-3 py-2 pr-8 rounded-lg border-0 focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>
                                                            🟡 Pending Review
                                                        </option>
                                                        <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>
                                                            🔵 Reviewed
                                                        </option>
                                                        <option value="approved" {{ $report->status == 'approved' ? 'selected' : '' }}>
                                                            🟢 Approved
                                                        </option>
                                                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>
                                                            🔴 Rejected
                                                        </option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                        <svg class="w-4 h-4 {{ $status['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </div>
                                                </div>

                                                @if($report->reviewed_at)
                                                    <div class="text-xs text-gray-500">
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            {{ $report->reviewed_by_user->name ?? 'System' }}
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
                                                <a href="{{ route('admin.monthly-reports.show', $report) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>

                                                @if(isset($report->excel_file_path))
                                                    <a href="{{ route('admin.monthly-reports.download', $report) }}"
                                                       class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Excel
                                                    </a>
                                                @endif

                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open"
                                                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </button>
                                                    <div x-show="open" @click.away="open = false"
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="transform opacity-0 scale-95"
                                                         x-transition:enter-end="transform opacity-100 scale-100"
                                                         class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                        <div class="py-1">
                                                            <a href="{{ route('admin.monthly-reports.edit', $report) }}"
                                                               class="block px-3 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                                                Edit
                                                            </a>
                                                            <button @click="deleteReport({{ $report->id }})"
                                                                    class="block w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                                                                Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Laporan</h3>
                                                    <p class="text-sm text-gray-500 mb-4">
                                                        Belum ada laporan bulanan yang tersedia untuk ditampilkan.
                                                    </p>
                                                    @if(request()->anyFilled(['search', 'status', 'user_id', 'date_range', 'priority']))
                                                        <a href="{{ route('admin.monthly-reports.index') }}"
                                                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            Reset Filter
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($reports as $report)
                                <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl p-6 border border-purple-100 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" :value="{{ $report->id }}" x-model="selectedReports"
                                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                {{ substr($report->user->name, 0, 2) }}
                                            </div>
                                        </div>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '🟡'],
                                                'reviewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => '🔵'],
                                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '🟢'],
                                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '🔴']
                                            ];
                                            $status = $statusConfig[$report->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                            {{ $status['icon'] }} {{ ucfirst($report->status) }}
                                        </span>
                                    </div>

                                    <!-- User Info -->
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $report->user->name }}</h4>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium">{{ $report->report_period ?? 'Tidak ada periode' }} {{ $report->report_date ? $report->report_date->format('Y') : '' }}</span>
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-3 mb-6">
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-600">Transaksi</span>
                                                <span class="text-sm font-bold text-purple-600">
                                                    {{ $report->total_transactions ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-600">Material</span>
                                                <span class="text-sm font-bold text-purple-600">
                                                    {{ $report->total_materials ?? 0 }} Item
                                                </span>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-gray-600">Tanggal</span>
                                                <span class="text-xs text-gray-900">
                                                    {{ $report->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.monthly-reports.show', $report) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>

                                            @if(isset($report->excel_file_path))
                                                <a href="{{ route('admin.monthly-reports.download', $report) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Excel
                                                </a>
                                            @endif
                                        </div>

                                        <!-- Status Update -->
                                        <div class="relative">
                                            <select @change="updateStatus({{ $report->id }}, $event.target.value)"
                                                    class="appearance-none {{ $status['bg'] }} {{ $status['text'] }} text-xs font-medium px-2 py-1 pr-6 rounded border-0 focus:ring-2 focus:ring-purple-500 cursor-pointer">
                                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                                <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>🔵 Reviewed</option>
                                                <option value="approved" {{ $report->status == 'approved' ? 'selected' : '' }}>🟢 Approved</option>
                                                <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>🔴 Rejected</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1">
                                                <svg class="w-3 h-3 {{ $status['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($reports->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                                <div class="text-sm text-gray-700">
                                    <span class="font-medium">Menampilkan {{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }}</span>
                                    <span class="text-gray-500">dari {{ $reports->total() }} laporan</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    {{ $reports->appends(request()->query())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2v-5a2 2 0 00-2-2h-2a2 2 0 00-2 2v5z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Tidak Ada Laporan Bulanan</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg leading-relaxed">
                            @if(request()->anyFilled(['search', 'status', 'user_id', 'date_range', 'priority']))
                                Tidak ada laporan yang sesuai dengan filter yang dipilih. Coba ubah kriteria pencarian atau reset filter.
                            @else
                                Belum ada laporan bulanan yang dibuat oleh user lapangan. Laporan akan muncul di sini setelah user mengirimkan laporan bulanan mereka.
                            @endif
                        </p>
                        @if(request()->anyFilled(['search', 'status', 'user_id', 'date_range', 'priority']))
                            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('admin.monthly-reports.index') }}"
                                   class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset Semua Filter
                                </a>
                                <button @click="showFilters = true"
                                        class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                                    </svg>
                                    Ubah Filter
                                </button>
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mx-auto max-w-md">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-900">Tips</h4>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Laporan akan otomatis muncul ketika user lapangan mengirimkan laporan bulanan mereka melalui aplikasi mobile.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript Functions -->
    <script>
        function updateStatus(reportId, newStatus) {
            if (confirm('Apakah Anda yakin ingin mengubah status laporan ini?')) {
                // Show loading state
                const loadingToast = document.createElement('div');
                loadingToast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                loadingToast.textContent = 'Memperbarui status...';
                document.body.appendChild(loadingToast);

                fetch(`/admin/monthly-reports/${reportId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    document.body.removeChild(loadingToast);

                    if (data.success) {
                        // Show success message
                        const successToast = document.createElement('div');
                        successToast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        successToast.textContent = 'Status berhasil diperbarui!';
                        document.body.appendChild(successToast);

                        // Auto remove after 3 seconds
                        setTimeout(() => {
                            document.body.removeChild(successToast);
                        }, 3000);

                        // Reload page to reflect changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Gagal memperbarui status: ' + data.message);
                    }
                })
                .catch(error => {
                    document.body.removeChild(loadingToast);
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui status');
                });
            }
        }

        function bulkUpdateStatus(status) {
            const selectedReports = Alpine.store('reports').selectedReports;
            if (selectedReports.length === 0) {
                alert('Silakan pilih laporan yang ingin diperbarui statusnya');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin mengubah status ${selectedReports.length} laporan menjadi ${status}?`)) {
                // Show loading state
                const loadingToast = document.createElement('div');
                loadingToast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                loadingToast.textContent = 'Memperbarui status laporan...';
                document.body.appendChild(loadingToast);

                fetch('/admin/monthly-reports/bulk-update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        report_ids: selectedReports,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.body.removeChild(loadingToast);

                    if (data.success) {
                        // Show success message
                        const successToast = document.createElement('div');
                        successToast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        successToast.textContent = `${data.updated_count} laporan berhasil diperbarui!`;
                        document.body.appendChild(successToast);

                        // Auto remove after 3 seconds
                        setTimeout(() => {
                            document.body.removeChild(successToast);
                        }, 3000);

                        // Clear selection and reload page
                        Alpine.store('reports').selectedReports = [];
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Gagal memperbarui status: ' + data.message);
                    }
                })
                .catch(error => {
                    document.body.removeChild(loadingToast);
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui status');
                });
            }
        }

        function deleteReport(reportId) {
            if (confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')) {
                const loadingToast = document.createElement('div');
                loadingToast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                loadingToast.textContent = 'Menghapus laporan...';
                document.body.appendChild(loadingToast);

                fetch(`/admin/monthly-reports/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.body.removeChild(loadingToast);

                    if (data.success) {
                        const successToast = document.createElement('div');
                        successToast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        successToast.textContent = 'Laporan berhasil dihapus!';
                        document.body.appendChild(successToast);

                        setTimeout(() => {
                            document.body.removeChild(successToast);
                        }, 3000);

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Gagal menghapus laporan: ' + data.message);
                    }
                })
                .catch(error => {
                    document.body.removeChild(loadingToast);
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus laporan');
                });
            }
        }

        // Initialize Alpine.js stores
        document.addEventListener('alpine:init', () => {
            Alpine.store('reports', {
                selectedReports: [],

                toggleAll(event) {
                    if (event.target.checked) {
                        // Select all visible reports
                        this.selectedReports = Array.from(document.querySelectorAll('input[type="checkbox"][x-model="selectedReports"]'))
                            .map(input => parseInt(input.value));
                    } else {
                        this.selectedReports = [];
                    }
                }
            });
        });

        // Auto-hide success messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 300);
                }, 5000);
            }

            // Initialize charts after page load
            initializeCharts();
            loadChartsData();
        });
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Charts JavaScript -->
    <script>
        let statusChart = null;
        let userChart = null;

        // Initialize charts on page load
        function initializeCharts() {
            console.log('Initializing monthly reports charts...');

            // Project Chart (Horizontal Bar)
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                statusChart = new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: [],
                            backgroundColor: '#8B5CF6',
                            borderColor: '#7C3AED',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // horizontal bars
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label || ''}: ${context.parsed.x}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { font: { size: 11 }, stepSize: 1 },
                                grid: { color: '#f3f4f6' }
                            },
                            y: {
                                ticks: { font: { size: 11 }, autoSkip: true, maxRotation: 0 },
                                grid: { display: false }
                            }
                        },
                        animation: { duration: 600 }
                    }
                });
            }

            // User Chart (Bar)
            const userCtx = document.getElementById('userChart');
            if (userCtx) {
                userChart = new Chart(userCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: [],
                            backgroundColor: '#8B5CF6',
                            borderColor: '#7C3AED',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return context[0].label;
                                    },
                                    label: function(context) {
                                        return `Total Laporan: ${context.parsed.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10 }
                                },
                                grid: { color: '#f3f4f6' }
                            },
                            x: {
                                ticks: { font: { size: 10 }, maxRotation: 30 },
                                grid: { display: false }
                            }
                        },
                        animation: { duration: 600 }
                    }
                });
            }
        }

        // Submit form with chart updates
        function submitFormWithChart() {
            console.log('Submitting form and updating charts...');
            
            // First submit the form normally
            const form = document.getElementById('filterForm');
            if (form) {
                // Prevent default and update charts before form submission
                updateChartsWithFilters();
                
                // Submit form after a short delay to allow chart update
                setTimeout(() => {
                    form.submit();
                }, 200);
            }
        }

        // Update charts with current filter values
        function updateChartsWithFilters() {
            console.log('Updating charts with current filters...');

            const formData = new FormData(document.getElementById('filterForm'));
            const filterParams = new URLSearchParams();

            // Collect filter parameters
            for (let [key, value] of formData.entries()) {
                if (value && value.trim() !== '') {
                    filterParams.append(key, value);
                }
            }

            const queryString = filterParams.toString();
            const apiUrl = `/admin/monthly-reports/chart-data${queryString ? '?' + queryString : ''}`;

            console.log('Chart API URL:', apiUrl);

            fetch(apiUrl)
                .then(response => {
                    console.log('Chart API response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Chart data received:', data);

                    // Check for API errors
                    if (data.error) {
                        throw new Error(data.message || 'API returned error');
                    }

                    // Update charts with original keys: projectData, locationData
                    updateProjectChart(data.projectData || []);
                    updateLocationChart(data.locationData || []);
                })
                .catch(error => {
                    console.error('Chart update error:', error);
                });
        }

    // Load initial chart data (clean, single implementation)
    function loadChartsData() {
            console.log('Loading initial charts data...');

            const loadingEl = document.getElementById('chart-loading');
            const chartsEl = document.getElementById('charts-container');
            const errorEl = document.getElementById('chart-error');

            // Show loading
            if (loadingEl) loadingEl.classList.remove('hidden');
            if (chartsEl) chartsEl.classList.add('hidden');
            if (errorEl) errorEl.classList.add('hidden');

            // Build query from current filters (form preferred)
            const form = document.getElementById('filterForm');
            const params = new URLSearchParams();
            if (form) {
                new FormData(form).forEach((v, k) => {
                    if (v !== null && v !== undefined && String(v).trim() !== '') params.append(k, v);
                });
            } else {
                // fallback to URL
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((v, k) => { if (v) params.append(k, v); });
            }

            const apiUrl = '/admin/monthly-reports/chart-data' + (params.toString() ? ('?' + params.toString()) : '');
            console.log('Chart API URL:', apiUrl);

        fetch(apiUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(async response => {
                    console.log('Chart API response status:', response.status);
                    const text = await response.text();
                    // Guard: if response starts with '<' it's HTML (error page)
                    if (text.trim().startsWith('<')) {
                        throw new Error('Server returned HTML (likely an error page). Check server logs or auth middleware.');
                    }
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON from chart API: ' + e.message);
                    }
                })
                .then(data => {
                    if (!data || data.error) throw new Error(data?.message || 'Invalid chart data');

                    // Update charts and UI
                    if (loadingEl) loadingEl.classList.add('hidden');
                    if (chartsEl) chartsEl.classList.remove('hidden');

            // Backend returns projectData and locationData
            updateProjectChart(data.projectData || []);
            updateLocationChart(data.locationData || []);
                })
                .catch(error => {
                    console.error('Charts API error:', error);
                    if (loadingEl) loadingEl.classList.add('hidden');
                    if (errorEl) errorEl.classList.remove('hidden');
                });
        }

        // Update project distribution chart
        function updateProjectChart(projectData) {
            console.log('Updating project chart with:', projectData);

            if (statusChart && projectData && projectData.length > 0) {
                const labels = projectData.map(item => item.name);
                const data = projectData.map(item => item.count);

                statusChart.data.labels = labels;
                statusChart.data.datasets[0].data = data;
                // Use a palette of colors for projects
                statusChart.data.datasets[0].backgroundColor = labels.map((_, i) => `hsl(${(i * 45) % 360} 70% 55%)`);
                statusChart.update('active');

                // Update details
                const detailsContainer = document.getElementById('status-chart-details');
                detailsContainer.innerHTML = projectData.slice(0,4).map(item => {
                    return `
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-gray-900">${item.count}</div>
                            <div class="text-xs text-gray-600 mt-1">${item.name}</div>
                        </div>
                    `;
                }).join('');
            } else {
                // Show empty state
                if (statusChart) {
                    statusChart.data.labels = ['Tidak ada data'];
                    statusChart.data.datasets[0].data = [1];
                    statusChart.data.datasets[0].backgroundColor = ['#D1D5DB'];
                    statusChart.update('active');
                }

                const detailsContainer = document.getElementById('status-chart-details');
                detailsContainer.innerHTML = '<div class="col-span-2 text-center text-gray-500 py-4">Tidak ada data proyek tersedia</div>';
            }
        }

        // Update location distribution chart
        function updateLocationChart(locationData) {
            console.log('Updating location chart with:', locationData);

            if (userChart && locationData && locationData.length > 0) {
                const labels = locationData.map(item => item.name);
                const data = locationData.map(item => item.count);

                userChart.data.labels = labels;
                userChart.data.datasets[0].data = data;
                userChart.update('active');

                // Update details
                const detailsContainer = document.getElementById('user-chart-details');
                detailsContainer.innerHTML = `
                    <div class="text-sm text-gray-600 text-center">
                        <span class="font-medium">Lokasi Teratas:</span> ${locationData[0]?.name || 'Tidak ada data'} 
                        <span class="text-purple-600 font-bold">(${locationData[0]?.count || 0} laporan)</span>
                    </div>
                `;
            } else {
                // Show empty state
                if (userChart) {
                    userChart.data.labels = ['Tidak ada data'];
                    userChart.data.datasets[0].data = [1];
                    userChart.data.datasets[0].backgroundColor = '#D1D5DB';
                    userChart.update('active');
                }

                const detailsContainer = document.getElementById('user-chart-details');
                detailsContainer.innerHTML = `
                    <div class="text-sm text-gray-500 text-center">
                        <span>Tidak ada data lokasi tersedia</span>
                    </div>
                `;
            }
        }

        // Toggle chart type
        function toggleChartType(chartId) {
            if (chartId === 'statusChart' && statusChart) {
                const isDoughnut = statusChart.config.type === 'doughnut';
                const newType = isDoughnut ? 'bar' : 'doughnut';

                const currentData = statusChart.data;
                statusChart.destroy();

                const ctx = document.getElementById('statusChart');
                if (newType === 'bar') {
                    statusChart = new Chart(ctx, {
                        type: 'bar',
                        data: currentData,
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { beginAtZero: true, ticks: { stepSize: 1 } },
                                y: { ticks: { autoSkip: true } }
                            }
                        }
                    });
                } else {
                    statusChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: currentData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { padding: 10, usePointStyle: true } }
                            }
                        }
                    });
                }
            }
        }

    </script>
</x-admin-layout>
