<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Pengajuan MFO</h2>
                            <p class="text-gray-600 mt-1">Kelola semua pengajuan Material Field Order dari pengguna</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-600 font-medium">Total Pengajuan</p>
                                    <p class="text-3xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-600 font-medium">Menunggu Review</p>
                                    <p class="text-3xl font-bold text-yellow-900">{{ $stats['pending'] ?? 0 }}</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-600 font-medium">Sedang Ditinjau</p>
                                    <p class="text-3xl font-bold text-purple-900">{{ $stats['reviewed'] ?? 0 }}</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-600 font-medium">Disetujui</p>
                                    <p class="text-3xl font-bold text-green-900">{{ $stats['approved'] ?? 0 }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-600 font-medium">Ditolak</p>
                                    <p class="text-3xl font-bold text-red-900">{{ $stats['rejected'] ?? 0 }}</p>
                                </div>
                                <div class="bg-red-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Tren Pengajuan MFO</h3>
                                    <p class="text-sm text-gray-600">Grafik pengajuan MFO berdasarkan periode</p>
                                </div>
                                
                                <!-- Chart Controls -->
                                <div class="flex space-x-3">
                                    <select id="mfo_group_by" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="day">Harian</option>
                                        <option value="week">Mingguan</option>
                                        <option value="month" selected>Bulanan</option>
                                    </select>
                                    
                                    <input type="date" id="mfo_start" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    
                                    <input type="date" id="mfo_end" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    
                                    <button id="mfo_refresh" class="px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Refresh
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Chart Container -->
                            <div class="relative" style="height: 400px;">
                                <canvas id="mfoChart" class="w-full h-full"></canvas>
                                
                                <!-- Loading State -->
                                <div id="mfo_loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75" style="display: none;">
                                    <div class="flex items-center space-x-2">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
                                        <span class="text-sm text-gray-600">Memuat data...</span>
                                    </div>
                                </div>
                                
                                <!-- No Data State -->
                                <div id="mfo_no_data" class="absolute inset-0 flex items-center justify-center bg-white" style="display: none;">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                                        <p class="mt-1 text-sm text-gray-500">Belum ada data pengajuan MFO untuk periode yang dipilih</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <form method="GET" action="{{ route('admin.mfo-requests.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan nama pengguna, proyek, lokasi..."
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="md:w-48">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <div class="md:w-48">
                                <label for="date_range" class="block text-sm font-medium text-gray-700">Periode</label>
                                <select name="date_range" id="date_range" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Semua Periode</option>
                                    <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="last_month" {{ request('date_range') === 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                                    <option value="this_year" {{ request('date_range') === 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Filter
                                </button>
                                <a href="{{ route('admin.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reset
                                </a>
                                <a href="{{ route('admin.mfo-requests.export') }}?{{ http_build_query(request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pengguna
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Proyek
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi & Cluster
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Pengajuan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($mfoRequests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $request->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->project->name ?? 'N/A' }}</div>
                                            @if($request->subProject)
                                                <div class="text-sm text-gray-500">{{ $request->subProject->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->project_location }}</div>
                                            @if($request->cluster)
                                                <div class="text-sm text-gray-500">Cluster: {{ $request->cluster }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->request_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {!! $request->status_badge !!}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.mfo-requests.show', $request) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                                @if($request->document_path)
                                                    <a href="{{ route('admin.mfo-requests.download', $request) }}" 
                                                       class="text-green-600 hover:text-green-900">
                                                        Download
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada pengajuan MFO ditemukan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($mfoRequests->hasPages())
                        <div class="mt-6">
                            {{ $mfoRequests->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MFO Chart Modal for Details -->
    <div id="mfoDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Detail Pengajuan MFO</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeMfoModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="max-h-96 overflow-y-auto">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        console.log('MFO Chart script loaded');
        
        let mfoChart = null;
        
        // Initialize chart when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing MFO chart');
            initializeMfoChart();
            setupChartControls();
        });
        
        function initializeMfoChart() {
            const canvas = document.getElementById('mfoChart');
            if (!canvas) {
                console.error('MFO Chart canvas not found');
                return;
            }
            
            console.log('Canvas found, initializing chart...');
            const ctx = canvas.getContext('2d');
            
            // Initialize empty chart
            mfoChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: [],
                        fill: true,
                        backgroundColor: 'rgba(99,102,241,0.18)',
                        borderColor: 'rgba(99,102,241,1)',
                        pointBackgroundColor: 'white',
                        pointBorderColor: 'rgba(99,102,241,1)',
                        pointHoverBackgroundColor: 'rgba(99,102,241,1)',
                        pointHoverBorderColor: 'white',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.25,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(17,24,39,0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(99,102,241,1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                title: function(tooltipItems) {
                                    return formatPeriodLabel(tooltipItems[0].label, getCurrentGroupBy());
                                },
                                label: function(context) {
                                    return `Jumlah Pengajuan: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#374151',
                                callback: function(value, index, values) {
                                    const label = this.getLabelForValue(value);
                                    return formatPeriodLabel(label, getCurrentGroupBy());
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(15,23,42,0.06)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#374151',
                                precision: 0
                            }
                        }
                    },
                    onClick: function(event, elements) {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const period = mfoChart.data.labels[index];
                            showMfoDetails(period);
                        }
                    }
                }
            });
            
            console.log('Chart created');
            loadMfoChartData();
        }
        
        function setupChartControls() {
            // Group by change
            document.getElementById('mfo_group_by').addEventListener('change', function() {
                loadMfoChartData();
            });
            
            // Date range change
            document.getElementById('mfo_start').addEventListener('change', function() {
                loadMfoChartData();
            });
            
            document.getElementById('mfo_end').addEventListener('change', function() {
                loadMfoChartData();
            });
            
            // Refresh button
            document.getElementById('mfo_refresh').addEventListener('click', function() {
                loadMfoChartData();
            });
        }
        
        function loadMfoChartData() {
            console.log('Loading chart data...');
            showLoading(true);
            
            const groupBy = getCurrentGroupBy();
            const startDate = document.getElementById('mfo_start').value;
            const endDate = document.getElementById('mfo_end').value;
            
            const params = new URLSearchParams({
                group_by: groupBy
            });
            
            if (startDate) params.append('start', startDate);
            if (endDate) params.append('end', endDate);
            
            fetch(`/admin/mfo-requests/chart-data?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data);
                showLoading(false);
                
                if (data.error) {
                    throw new Error(data.message || 'Unknown error');
                }
                
                updateChartData(data.data || []);
            })
            .catch(error => {
                console.error('Chart data error:', error);
                showLoading(false);
                showNoData(true);
                
                // Show error in console but don't alert user
                console.error('Failed to load chart data:', error.message);
            });
        }
        
        function updateChartData(data) {
            if (!mfoChart || !data) return;
            
            if (data.length === 0) {
                showNoData(true);
                return;
            }
            
            showNoData(false);
            
            // Update chart data
            mfoChart.data.labels = data.map(item => item.period);
            mfoChart.data.datasets[0].data = data.map(item => item.count);
            mfoChart.update('active');
            
            console.log('Chart updated with', data.length, 'data points');
        }
        
        function showMfoDetails(period) {
            const groupBy = getCurrentGroupBy();
            
            console.log('Loading details for period:', period);
            
            const params = new URLSearchParams({
                period: period,
                group_by: groupBy,
                per_page: 10,
                page: 1
            });
            
            document.getElementById('modalTitle').textContent = `Detail Pengajuan MFO - ${formatPeriodLabel(period, groupBy)}`;
            document.getElementById('modalContent').innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div></div>';
            document.getElementById('mfoDetailsModal').style.display = 'block';
            
            fetch(`/admin/mfo-requests/chart-data/details?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.message);
                }
                
                let html = '<div class="space-y-3">';
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(request => {
                        html += `
                            <div class="p-3 border border-gray-200 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">${request.user?.name || 'N/A'}</div>
                                        <div class="text-sm text-gray-600">${request.project?.name || 'N/A'}</div>
                                        <div class="text-sm text-gray-500">${request.project_location || ''}</div>
                                        <div class="text-xs text-gray-400">Tanggal: ${new Date(request.request_date).toLocaleDateString('id-ID')}</div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusBadgeClasses(request.status)}">${getStatusLabel(request.status)}</span>
                                        <a href="/admin/mfo-requests/${request.id}" class="text-indigo-600 hover:text-indigo-800 text-sm">Detail</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += '<div class="text-center text-gray-500 py-8">Tidak ada data untuk periode ini</div>';
                }
                
                html += '</div>';
                document.getElementById('modalContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Details error:', error);
                document.getElementById('modalContent').innerHTML = '<div class="text-center text-red-500 py-4">Gagal memuat data detail</div>';
            });
        }
        
        function closeMfoModal() {
            document.getElementById('mfoDetailsModal').style.display = 'none';
        }
        
        function getCurrentGroupBy() {
            return document.getElementById('mfo_group_by').value || 'month';
        }
        
        function formatPeriodLabel(period, groupBy) {
            if (!period) return '';
            
            try {
                switch (groupBy) {
                    case 'day':
                        return new Date(period).toLocaleDateString('id-ID');
                    case 'week':
                        if (period.includes('W')) {
                            const [year, week] = period.split('-W');
                            return `Minggu ${week}, ${year}`;
                        }
                        return period;
                    case 'month':
                    default:
                        const date = new Date(period);
                        return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                }
            } catch (e) {
                return period;
            }
        }
        
        function showLoading(show) {
            const loading = document.getElementById('mfo_loading');
            if (loading) {
                loading.style.display = show ? 'flex' : 'none';
            }
        }
        
        function showNoData(show) {
            const noData = document.getElementById('mfo_no_data');
            if (noData) {
                noData.style.display = show ? 'flex' : 'none';
            }
        }
        
        function getStatusBadgeClasses(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'reviewed': 'bg-purple-100 text-purple-800',
                'approved': 'bg-green-100 text-green-800',
                'rejected': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
        
        function getStatusLabel(status) {
            const labels = {
                'pending': 'Menunggu Review',
                'reviewed': 'Sedang Ditinjau',
                'approved': 'Disetujui',
                'rejected': 'Ditolak'
            };
            return labels[status] || status;
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('mfoDetailsModal');
            if (event.target === modal) {
                closeMfoModal();
            }
        }
    </script>
    @endpush
</x-admin-layout>
