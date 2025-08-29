<x-admin-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="main-content-wrapper transition-layout">
            <div class="container mx-auto px-4 py-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-4 lg:mb-0">
                            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                            <p class="text-gray-600 mt-2">Analisis trend penggunaan material dan prediksi kebutuhan</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="window.history.back()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Kembali
                            </button>
                            <button onclick="exportAnalytics()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Report
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Filter & Pengaturan</h3>
                            <button onclick="resetFilters()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Reset Filter
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                                <select id="periodFilter" onchange="updateCharts()" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="6months">6 Bulan Terakhir</option>
                                    <option value="12months">12 Bulan Terakhir</option>
                                    <option value="3months">3 Bulan Terakhir</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select id="categoryFilter" onchange="updateCharts()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                                <select id="projectFilter" onchange="updateCharts()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">Semua Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tampilan</label>
                                <select id="chartTypeFilter" onchange="updateChartDisplay()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="overview">Overview</option>
                                    <option value="detailed">Detail per Material</option>
                                    <option value="prediction">Prediksi & Trend</option>
                                    <option value="comparison">Perbandingan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Trend Penggunaan</p>
                                <p class="text-2xl font-bold text-gray-900" id="trendPercentage">+15.2%</p>
                                <p class="text-xs text-green-600">vs bulan lalu</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Material Aktif</p>
                                <p class="text-2xl font-bold text-gray-900" id="activeMaterials">{{ $totalActiveMaterials ?? 0 }}</p>
                                <p class="text-xs text-gray-500">dalam periode</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Prediksi Kebutuhan</p>
                                <p class="text-2xl font-bold text-gray-900" id="predictedNeed">High</p>
                                <p class="text-xs text-orange-600">bulan depan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Akurasi Prediksi</p>
                                <p class="text-2xl font-bold text-gray-900" id="predictionAccuracy">87.5%</p>
                                <p class="text-xs text-green-600">rata-rata</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Sections -->
                <div id="overviewCharts" class="chart-section">
                    <!-- Overview Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Material Usage Trend -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Trend Penggunaan Material</h3>
                                <p class="text-sm text-gray-600">Grafik penggunaan material dalam 6 bulan terakhir</p>
                            </div>
                            <div class="p-6">
                                <canvas id="materialTrendChart" width="400" height="200"></canvas>
                            </div>
                        </div>

                        <!-- Top Materials -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Material Paling Banyak Digunakan</h3>
                                <p class="text-sm text-gray-600">Top 10 material berdasarkan quantity</p>
                            </div>
                            <div class="p-6">
                                <canvas id="topMaterialsChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Category Distribution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Distribusi Kategori Material</h3>
                            <p class="text-sm text-gray-600">Persentase penggunaan berdasarkan kategori</p>
                        </div>
                        <div class="p-6">
                            <canvas id="categoryDistributionChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div id="detailedCharts" class="chart-section" style="display: none;">
                    <!-- Detailed Charts will be loaded here -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Penggunaan per Material</h3>
                            <p class="text-sm text-gray-600">Analisis mendalam setiap material</p>
                        </div>
                        <div class="p-6">
                            <div id="detailedChartsContainer">
                                <!-- Dynamic charts will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="predictionCharts" class="chart-section" style="display: none;">
                    <!-- Prediction Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Prediksi Kebutuhan Material</h3>
                                <p class="text-sm text-gray-600">Forecast berdasarkan historical data</p>
                            </div>
                            <div class="p-6">
                                <canvas id="predictionChart" width="400" height="200"></canvas>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Seasonal Pattern</h3>
                                <p class="text-sm text-gray-600">Pola musiman penggunaan material</p>
                            </div>
                            <div class="p-6">
                                <canvas id="seasonalChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Prediction Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Prediksi Kebutuhan 3 Bulan Ke Depan</h3>
                            <p class="text-sm text-gray-600">Estimasi berdasarkan trend dan pattern</p>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Ini</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Depan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">2 Bulan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">3 Bulan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                        </tr>
                                    </thead>
                                    <tbody id="predictionTableBody" class="bg-white divide-y divide-gray-200">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="comparisonCharts" class="chart-section" style="display: none;">
                    <!-- Comparison Charts -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Perbandingan Antar Project</h3>
                            <p class="text-sm text-gray-600">Analisis komparatif penggunaan material</p>
                        </div>
                        <div class="p-6">
                            <canvas id="projectComparisonChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-lg p-6 flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat data analytics...</span>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    
    <script>
        // Global variables
        let materialTrendChart = null;
        let topMaterialsChart = null;
        let categoryDistributionChart = null;
        let predictionChart = null;
        let seasonalChart = null;
        let projectComparisonChart = null;

        // Chart data
        const analyticsData = @json($analyticsData);

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            updateMetrics();
        });

        function initializeCharts() {
            showLoading();
            
            // Initialize all charts
            createMaterialTrendChart();
            createTopMaterialsChart();
            createCategoryDistributionChart();
            createPredictionChart();
            createSeasonalChart();
            createProjectComparisonChart();
            
            hideLoading();
        }

        function createMaterialTrendChart() {
            const ctx = document.getElementById('materialTrendChart').getContext('2d');
            
            materialTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: analyticsData.monthlyLabels,
                    datasets: [{
                        label: 'Total Penggunaan',
                        data: analyticsData.monthlyUsage,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        function createTopMaterialsChart() {
            const ctx = document.getElementById('topMaterialsChart').getContext('2d');
            
            topMaterialsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.topMaterials.labels,
                    datasets: [{
                        label: 'Quantity',
                        data: analyticsData.topMaterials.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(249, 115, 22, 0.8)',
                            'rgba(168, 85, 247, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createCategoryDistributionChart() {
            const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
            
            categoryDistributionChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: analyticsData.categoryDistribution.labels,
                    datasets: [{
                        data: analyticsData.categoryDistribution.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        function createPredictionChart() {
            const ctx = document.getElementById('predictionChart').getContext('2d');
            
            predictionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: analyticsData.prediction.labels,
                    datasets: [{
                        label: 'Historical',
                        data: analyticsData.prediction.historical,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Predicted',
                        data: analyticsData.prediction.predicted,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderDash: [5, 5],
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createSeasonalChart() {
            const ctx = document.getElementById('seasonalChart').getContext('2d');
            
            seasonalChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Pattern Musiman',
                        data: analyticsData.seasonal,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(16, 185, 129)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createProjectComparisonChart() {
            const ctx = document.getElementById('projectComparisonChart').getContext('2d');
            
            projectComparisonChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: analyticsData.projectComparison.labels,
                    datasets: [{
                        label: 'Material Usage',
                        data: analyticsData.projectComparison.data,
                        backgroundColor: 'rgba(139, 92, 246, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function updateCharts() {
            showLoading();
            
            const period = document.getElementById('periodFilter').value;
            const category = document.getElementById('categoryFilter').value;
            const project = document.getElementById('projectFilter').value;
            
            // Fetch updated data
            fetch(`/admin/analytics-data?period=${period}&category=${category}&project=${project}`)
                .then(response => response.json())
                .then(data => {
                    // Update all charts with new data
                    updateChartData(data);
                    updateMetrics();
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error updating charts:', error);
                    hideLoading();
                });
        }

        function updateChartDisplay() {
            const chartType = document.getElementById('chartTypeFilter').value;
            
            // Hide all sections
            document.querySelectorAll('.chart-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show selected section
            document.getElementById(chartType + 'Charts').style.display = 'block';
            
            // Load specific data if needed
            if (chartType === 'detailed') {
                loadDetailedCharts();
            } else if (chartType === 'prediction') {
                updatePredictionTable();
            }
        }

        function updateChartData(data) {
            // Update material trend chart
            materialTrendChart.data.labels = data.monthlyLabels;
            materialTrendChart.data.datasets[0].data = data.monthlyUsage;
            materialTrendChart.update();
            
            // Update other charts similarly...
        }

        function updateMetrics() {
            // Update key metrics
            // This would be calculated from the analytics data
        }

        function loadDetailedCharts() {
            // Load detailed charts for each material
            const container = document.getElementById('detailedChartsContainer');
            container.innerHTML = '<p class="text-center text-gray-500">Loading detailed charts...</p>';
            
            // Implementation for detailed charts
        }

        function updatePredictionTable() {
            // Update prediction table
            const tbody = document.getElementById('predictionTableBody');
            tbody.innerHTML = '';
            
            analyticsData.predictionTable.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.material}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.current}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.month1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.month2}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.month3}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${row.confidence >= 80 ? 'bg-green-100 text-green-800' : 
                              row.confidence >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                            ${row.confidence}%
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function resetFilters() {
            document.getElementById('periodFilter').value = '6months';
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('projectFilter').value = 'all';
            document.getElementById('chartTypeFilter').value = 'overview';
            updateCharts();
            updateChartDisplay();
        }

        function exportAnalytics() {
            // Implementation for exporting analytics
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/export-analytics';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }
    </script>

    <style>
        .chart-section {
            transition: all 0.3s ease;
        }
        
        .main-content-wrapper {
            transition: all 0.3s ease;
        }
        
        /* Responsive chart containers */
        canvas {
            max-height: 400px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-admin-layout>
