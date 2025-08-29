<x-admin-layout>
    <x-slot name="header">
        <!-- Modern Header with Company Branding -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 shadow-lg rounded-b-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
                        <p class="text-red-100 text-lg">PT DADS Logistik - Sistem Manajemen Material</p>
                        <div class="flex items-center mt-3 text-red-100">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Sistem Logistik PT DADS</span>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <!-- Quick Status Indicators -->
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div class="text-white text-sm font-medium">Status Sistem</div>
                            <div class="flex items-center mt-1">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-green-100 text-sm">Online & Aktif</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div class="text-white text-sm font-medium">Total Users</div>
                            <div class="text-white text-lg font-bold">
                                {{ \App\Models\User::count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Key Performance Indicators -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Ringkasan Kinerja</h2>
                        <p class="text-gray-600">Overview statistik sistem logistik PT DADS</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center text-sm text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                            Update otomatis setiap 30 detik
                        </div>
                        
                        <!-- Export Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Excel
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.outside="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50">
                                
                                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
                                    Export Data
                                </div>

                                <a href="{{ route('admin.export.summary') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Ringkasan Lengkap</div>
                                        <div class="text-xs text-gray-500">Multi-sheet dengan semua data</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.export.transactions') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Data Transaksi</div>
                                        <div class="text-xs text-gray-500">Semua transaksi material</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.export.monthly-reports') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-1"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium">Laporan Bulanan</div>
                                        <div class="text-xs text-gray-500">Semua laporan dari field users</div>
                                    </div>
                                </a>

                                <div class="px-4 py-2 mt-2 border-t border-gray-100">
                                    <div class="text-xs text-gray-500">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        Format Excel (.xlsx)
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button onclick="location.reload()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Performance Cards Grid - Clean & Simple -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- Total Transaksi -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-red-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Transaksi</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\Transaction::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-green-100 text-green-700 text-xs rounded-full font-medium">Aktif</span>
                        </div>
                    </div>

                    <!-- Total Material -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Material</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\Material::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Tersedia</span>
                        </div>
                    </div>

                    <!-- Material Quantity Summary -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-cyan-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-cyan-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Qty Material</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($materialQuantitySummary['total_quantity_transactions'] + $materialQuantitySummary['total_quantity_po'], 0) }}</p>
                            <a href="{{ route('admin.material-quantity-detail') }}" class="inline-block px-2 py-1 mt-2 bg-cyan-100 text-cyan-700 text-xs rounded-full font-medium hover:bg-cyan-200 transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>

                    <!-- Total Project -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-purple-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Project</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\Project::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">Aktif</span>
                        </div>
                    </div>

                    <!-- Total Vendor -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-green-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Vendor</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\Vendor::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-green-100 text-green-700 text-xs rounded-full font-medium">Terdaftar</span>
                        </div>
                    </div>

                    <!-- Total Category -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-orange-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Kategori Material</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\Category::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">Aktif</span>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-300 transition-all duration-200">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-xs font-medium text-gray-600 mb-1">Total Users</h4>
                            <p class="text-xl font-bold text-gray-900">{{ number_format(\App\Models\User::count()) }}</p>
                            <span class="inline-block px-2 py-1 mt-2 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">Online</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status & Activity -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Status & Aktivitas Sistem
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-white rounded-lg border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">Server Status</div>
                            <div class="flex items-center justify-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                <span class="font-semibold text-green-600">Online</span>
                            </div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">Database</div>
                            <div class="flex items-center justify-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="font-semibold text-green-600">Connected</span>
                            </div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">Waktu Update</div>
                            <div class="font-semibold text-blue-600" id="last-update">{{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }}</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">System Uptime</div>
                            <div class="font-semibold text-indigo-600">99.9%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation Menu -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Menu Navigasi Cepat
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Transaksi Menu -->
                    <a href="{{ route('admin.transactions.index') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-red-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Kelola Transaksi</h4>
                                <p class="text-xs text-gray-600">Manajemen data transaksi</p>
                            </div>
                        </div>
                    </a>

                    <!-- Materials Menu -->
                    <a href="{{ route('admin.materials.index') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Kelola Material</h4>
                                <p class="text-xs text-gray-600">Data master material</p>
                            </div>
                        </div>
                    </a>

                    <!-- Material Quantity Summary - NEW -->
                    <a href="{{ route('admin.material-quantity-detail') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-cyan-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-cyan-600 transition-colors">Quantity Material</h4>
                                <p class="text-xs text-gray-600">Total quantity dari user</p>
                            </div>
                        </div>
                    </a>

                    <!-- Analytics Dashboard - NEW -->
                    <a href="{{ route('admin.analytics.dashboard') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Analytics Dashboard</h4>
                                <p class="text-xs text-gray-600">Trend & prediksi material</p>
                            </div>
                        </div>
                    </a>

                    <!-- Projects Menu -->
                    <a href="{{ route('admin.projects.index') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-purple-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Kelola Project</h4>
                                <p class="text-xs text-gray-600">Manajemen project</p>
                            </div>
                        </div>
                    </a>

                    <!-- Vendors Menu -->
                    <a href="{{ route('admin.vendors.index') }}"
                       class="group bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-green-300 transition-all duration-200 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Kelola Vendor</h4>
                                <p class="text-xs text-gray-600">Data master vendor</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                    <div class="relative group transform hover:scale-105 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-green-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                        <div class="relative bg-white rounded-3xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:border-green-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">Total Users</p>
                                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $totalFieldUsers + $totalPOUsers + 1 }}</p>
                                <div class="flex items-center justify-center px-3 py-1 bg-gradient-to-r from-blue-50 to-green-50 rounded-full border border-gray-200">
                                    <div class="flex space-x-1 mr-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
                                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                                    </div>
                                    <span class="text-gray-700 font-semibold text-sm">Multi-role aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics & Charts Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Analytics & Trends</h2>
                        <p class="text-gray-600">Visualisasi data dan tren aktivitas sistem</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center text-sm text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                            </svg>
                            Data real-time
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Transaction Trends Chart -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Trend Transaksi</h3>
                                <p class="text-sm text-gray-600">6 bulan terakhir</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>

                    <!-- Report Status Distribution -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Status Laporan</h3>
                                <p class="text-sm text-gray-600">Distribusi semua laporan</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="reportStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bottom Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- User Role Distribution -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Distribusi User</h3>
                                <p class="text-sm text-gray-600">Berdasarkan role</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="userRoleChart"></canvas>
                        </div>
                    </div>

                    <!-- Weekly Activity -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Aktivitas Mingguan</h3>
                                <p class="text-sm text-gray-600">7 hari terakhir</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="weeklyActivityChart"></canvas>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold">Quick Insights</h3>
                                <p class="text-sm text-gray-300">Ringkasan cepat</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm">Transaksi Bulan Ini</span>
                                </div>
                                <span class="font-bold text-lg">{{ \App\Models\Transaction::whereMonth('transaction_date', now()->month)->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm">Laporan Approved</span>
                                </div>
                                <span class="font-bold text-lg">{{ \App\Models\MonthlyReport::where('status', 'approved')->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm">Perlu Review</span>
                                </div>
                                <span class="font-bold text-lg">{{ \App\Models\MonthlyReport::where('status', 'pending')->count() }}</span>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-600">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-400 mb-1">
                                        {{ number_format((\App\Models\MonthlyReport::where('status', 'approved')->count() / max(\App\Models\MonthlyReport::count(), 1)) * 100, 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-300">Approval Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Actions Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M3 17a1 1 0 001-1v-1.586l2.293 2.293a1 1 0 001.414-1.414L5.414 13H7a1 1 0 000-2H3a1 1 0 00-1 1v4a1 1 0 001 1zm13-3a1 1 0 01-1 1h-1.586l2.293 2.293a1 1 0 001.414-1.414L15.414 13H17a1 1 0 000-2h-4a1 1 0 00-1 1v4a1 1 0 001 1z" clip-rule="evenodd"/>
                    </svg>
                    Aksi Prioritas
                </h2>

                @php
                    $priorityItems = [];

                    $pendingReports = \App\Models\MonthlyReport::where('status', 'pending')->count();
                    if($pendingReports > 0) {
                        $priorityItems[] = [
                            'title' => 'Review Laporan Bulanan',
                            'count' => $pendingReports,
                            'description' => 'laporan menunggu review',
                            'route' => 'admin.monthly-reports.index',
                            'color' => 'red',
                            'urgent' => true
                        ];
                    }

                    $pendingLoss = \App\Models\LossReport::where('status', 'pending')->count();
                    if($pendingLoss > 0) {
                        $priorityItems[] = [
                            'title' => 'Review Laporan Kehilangan',
                            'count' => $pendingLoss,
                            'description' => 'laporan kehilangan perlu ditangani',
                            'route' => 'admin.loss-reports.index',
                            'color' => 'red',
                            'urgent' => true
                        ];
                    }

                    $pendingMfo = \App\Models\MfoRequest::where('status', 'pending')->count();
                    if($pendingMfo > 0) {
                        $priorityItems[] = [
                            'title' => 'Review Pengajuan MFO',
                            'count' => $pendingMfo,
                            'description' => 'pengajuan material field order',
                            'route' => 'admin.mfo-requests.index',
                            'color' => 'orange',
                            'urgent' => false
                        ];
                    }

                    $pendingPo = \App\Models\PoMaterial::where('status', 'pending')->count();
                    if($pendingPo > 0) {
                        $priorityItems[] = [
                            'title' => 'Review PO Materials',
                            'count' => $pendingPo,
                            'description' => 'purchase order menunggu persetujuan',
                            'route' => 'admin.po-materials.index',
                            'color' => 'blue',
                            'urgent' => false
                        ];
                    }

                    $pendingTransport = \App\Models\PoTransport::where('status', 'pending')->count();
                    if($pendingTransport > 0) {
                        $priorityItems[] = [
                            'title' => 'Review PO Transportasi',
                            'count' => $pendingTransport,
                            'description' => 'PO transportasi menunggu persetujuan',
                            'route' => 'admin.po-transports.index',
                            'color' => 'cyan',
                            'urgent' => false
                        ];
                    }
                @endphp

                @if(count($priorityItems) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        @foreach($priorityItems as $item)
                            <div class="relative group">
                                @if($item['urgent'])
                                    <div class="absolute -top-2 -right-2 z-10">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                                            URGENT
                                        </span>
                                    </div>
                                @endif

                                <div class="relative bg-white rounded-2xl p-6 shadow-lg border-2 border-{{ $item['color'] }}-200 hover:border-{{ $item['color'] }}-400 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                                            <div class="flex items-center mb-2">
                                                <span class="text-3xl font-bold text-{{ $item['color'] }}-600">{{ $item['count'] }}</span>
                                                <span class="ml-2 text-sm text-gray-600">{{ $item['description'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-sm text-{{ $item['color'] }}-600">
                                            @if($item['urgent'])
                                                <svg class="w-4 h-4 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-medium">Perlu perhatian segera</span>
                                            @else
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-medium">Segera ditindaklanjuti</span>
                                            @endif
                                        </div>

                                        <a href="{{ route($item['route']) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-{{ $item['color'] }}-500 to-{{ $item['color'] }}-600 hover:from-{{ $item['color'] }}-600 hover:to-{{ $item['color'] }}-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            Tindak Lanjut
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-200 rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-green-800 mb-2">Semua Terkendali!</h3>
                        <p class="text-green-600">Tidak ada aksi prioritas yang perlu segera ditangani.</p>
                    </div>
                @endif
            </div>
            <!-- Main Navigation Menu -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Menu Utama Sistem
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                    <!-- Data Management Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                        <div class="relative bg-white rounded-3xl shadow-xl border-2 border-red-100 hover:border-red-300 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-white">Manajemen Data</h3>
                                            <p class="text-red-100 text-sm">Transaksi & Tabel Data</p>
                                        </div>
                                    </div>
                                    <div class="text-white opacity-80">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-8">
                                <p class="text-gray-600 mb-6 leading-relaxed">
                                    Akses dan kelola semua data transaksi material, filter berdasarkan berbagai kriteria,
                                    dan monitoring aktivitas real-time sistem logistik.
                                </p>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Filter & pencarian advanced</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Export data dalam berbagai format</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Monitoring real-time</span>
                                    </div>
                                </div>

                                <a href="{{ route('admin.transactions.index') }}"
                                   class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Buka Tabel Data Transaksi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Management Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                        <div class="relative bg-white rounded-3xl shadow-xl border-2 border-blue-100 hover:border-blue-300 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-white">Manajemen Laporan</h3>
                                            <p class="text-blue-100 text-sm">Review & Approval</p>
                                        </div>
                                    </div>
                                    <div class="text-white opacity-80">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-8">
                                <p class="text-gray-600 mb-6 leading-relaxed">
                                    Review dan approve laporan bulanan, laporan kehilangan material,
                                    serta kelola workflow persetujuan dari user lapangan.
                                </p>

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <a href="{{ route('admin.monthly-reports.index') }}"
                                       class="flex flex-col items-center p-4 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-blue-200">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 text-center">Laporan Bulanan</span>
                                    </a>

                                    <a href="{{ route('admin.loss-reports.index') }}"
                                       class="flex flex-col items-center p-4 border-2 border-red-200 rounded-xl hover:border-red-400 hover:bg-red-50 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-red-200">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 text-center">Lap. Kehilangan</span>
                                    </a>
                                </div>

                                <a href="{{ route('admin.monthly-reports.index') }}"
                                   class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Kelola Semua Laporan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Request Management Card -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-teal-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                        <div class="relative bg-white rounded-3xl shadow-xl border-2 border-green-100 hover:border-green-300 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-white">Manajemen Pengajuan</h3>
                                            <p class="text-green-100 text-sm">MFO & PO Materials</p>
                                        </div>
                                    </div>
                                    <div class="text-white opacity-80">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-8">
                                <p class="text-gray-600 mb-6 leading-relaxed">
                                    Proses approval untuk Material Field Order (MFO) dari user lapangan
                                    dan Purchase Order Materials dari tim procurement.
                                </p>

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <a href="{{ route('admin.mfo-requests.index') }}"
                                       class="flex flex-col items-center p-4 border-2 border-green-200 rounded-xl hover:border-green-400 hover:bg-green-50 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-green-200">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 text-center">Pengajuan MFO</span>
                                    </a>

                                    <a href="{{ route('admin.po-materials.index') }}"
                                       class="flex flex-col items-center p-4 border-2 border-teal-200 rounded-xl hover:border-teal-400 hover:bg-teal-50 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-teal-200">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 text-center">PO Materials</span>
                                    </a>

                                    <a href="{{ route('admin.po-transports.index') }}"
                                       class="flex flex-col items-center p-4 border-2 border-cyan-200 rounded-xl hover:border-cyan-400 hover:bg-cyan-50 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center mb-2 group-hover:bg-cyan-200">
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2 2H8a2 2 0 01-2-2v-2"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 text-center">PO Transportasi</span>
                                    </a>
                                </div>

                                <a href="{{ route('admin.mfo-requests.index') }}"
                                   class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Kelola Semua Pengajuan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master Data Management Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                    Manajemen Data Master
                </h2>

                <div class="bg-white rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Data Master Sistem</h3>
                                <p class="text-gray-600">Kelola semua data referensi dan master data untuk operasional sistem</p>
                            </div>
                            <div class="hidden md:flex items-center space-x-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span>Sinkronisasi aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <!-- Vendor Management -->
                            <a href="{{ route('admin.vendors.index') }}"
                               class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border-2 border-blue-200 hover:border-blue-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">Vendor</h4>
                                    <p class="text-sm text-gray-600 mb-3">Kelola data vendor & supplier</p>
                                    <div class="flex items-center text-sm font-medium text-blue-600">
                                        <span>{{ \App\Models\Vendor::count() }} vendor terdaftar</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Project Management -->
                            <a href="{{ route('admin.projects.index') }}"
                               class="group relative bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border-2 border-green-200 hover:border-green-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-green-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">Project</h4>
                                    <p class="text-sm text-gray-600 mb-3">Kelola data project & sub-project</p>
                                    <div class="flex items-center text-sm font-medium text-green-600">
                                        <span>{{ \App\Models\Project::count() }} project aktif</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Category Management -->
                            <a href="{{ route('admin.categories.index') }}"
                               class="group relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 bg-purple-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">Kategori</h4>
                                    <p class="text-sm text-gray-600 mb-3">Kelola kategori material</p>
                                    <div class="flex items-center text-sm font-medium text-purple-600">
                                        <span>{{ \App\Models\Category::count() }} kategori</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Material Management -->
                            <a href="{{ route('admin.materials.index') }}"
                               class="group relative bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 border-2 border-orange-200 hover:border-orange-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-orange-600 transition-colors duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">Material</h4>
                                    <p class="text-sm text-gray-600 mb-3">Kelola data material & stok</p>
                                    <div class="flex items-center text-sm font-medium text-orange-600">
                                        <span>{{ \App\Models\Material::count() }} material</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Quick Stats Bar -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap items-center justify-center space-x-8 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                    <span>Vendor: {{ \App\Models\Vendor::count() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span>Project: {{ \App\Models\Project::count() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                    <span>Kategori: {{ \App\Models\Category::count() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                    <span>Material: {{ \App\Models\Material::count() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                    <span>Users: {{ $totalFieldUsers + $totalPOUsers + 1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Help & System Status -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-3xl shadow-2xl p-8 text-white">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="flex items-center mb-6 lg:mb-0">
                        <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mr-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Butuh Bantuan?</h3>
                            <p class="text-gray-300 text-lg">Tim support siap membantu Anda 24/7</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <button onclick="alert('Fitur live chat akan segera hadir!')"
                                class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Live Chat
                        </button>
                        <button onclick="alert('Manual sistem dalam format PDF akan diunduh.')"
                                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl transition-all duration-300">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Manual
                        </button>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700">
                    <div class="text-center text-gray-400 text-sm">
                        <p class="mb-2">
                            <strong class="text-white">PT DADS Logistik</strong> - Sistem Manajemen Material Terintegrasi
                        </p>
                        <p>
                            Powered by Laravel Framework • Version 1.0 •
                            © {{ date('Y') }} All rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript for Better UX and Real-time Updates -->
    <script>
        // Chart data from backend
        const chartData = {
            transactionTrends: @json($transactionTrends),
            monthlyReportTrends: @json($monthlyReportTrends),
            months: @json($months),
            reportStats: @json($reportStats),
            userRoleStats: @json($userRoleStats),
            weeklyActivity: @json($weeklyActivity)
        };

        console.log('Chart.js available:', typeof Chart !== 'undefined');
        console.log('Chart data loaded:', chartData);

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking Chart.js...');
            
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }
            
            // Check if all canvas elements exist
            const canvasElements = [
                'transactionChart',
                'reportStatusChart', 
                'userRoleChart',
                'weeklyActivityChart'
            ];
            
            let missingElements = [];
            canvasElements.forEach(id => {
                if (!document.getElementById(id)) {
                    missingElements.push(id);
                }
            });
            
            if (missingElements.length > 0) {
                console.error('Missing canvas elements:', missingElements);
                // Try again after a short delay
                setTimeout(function() {
                    console.log('Retrying chart initialization...');
                    initializeCharts();
                }, 1000);
            } else {
                console.log('All canvas elements found, initializing charts...');
                initializeCharts();
            }
            
            // Auto-refresh functionality for stats
            function refreshStats() {
                console.log('Auto-refreshing statistics...');
                // In real implementation, you would fetch updated data via AJAX here
            }

            // Refresh stats every 30 seconds
            setInterval(refreshStats, 30000);

            // Add smooth animations for better UX
            const cards = document.querySelectorAll('.hover\\:shadow-md');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add loading states for interactive elements
            const refreshButton = document.querySelector('[onclick="location.reload()"]');
            if (refreshButton) {
                refreshButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Refreshing...
                    `;

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
            }
        });

        // Chart initialization function
        function initializeCharts() {
            // Chart.js default configuration
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            Chart.defaults.color = '#6B7280';
            
            console.log('Initializing charts...');
            console.log('Chart data:', chartData);
            
            // 1. Transaction Trends Line Chart
            const transactionCanvas = document.getElementById('transactionChart');
            if (transactionCanvas) {
                console.log('Transaction chart canvas found');
                const transactionCtx = transactionCanvas.getContext('2d');
                new Chart(transactionCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.months,
                        datasets: [{
                            label: 'Transaksi',
                            data: chartData.transactionTrends,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }, {
                            label: 'Laporan',
                            data: chartData.monthlyReportTrends,
                            borderColor: 'rgb(147, 51, 234)',
                            backgroundColor: 'rgba(147, 51, 234, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(147, 51, 234)',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
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
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
                console.log('Transaction chart initialized');
            } else {
                console.error('Transaction chart canvas not found');
            }

            // 2. Report Status Doughnut Chart
            const reportStatusCanvas = document.getElementById('reportStatusChart');
            if (reportStatusCanvas) {
                console.log('Report status chart canvas found');
                const reportStatusCtx = reportStatusCanvas.getContext('2d');
                new Chart(reportStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Reviewed', 'Approved', 'Rejected'],
                        datasets: [{
                            data: [
                                chartData.reportStats.pending,
                                chartData.reportStats.reviewed,
                                chartData.reportStats.approved,
                                chartData.reportStats.rejected
                            ],
                            backgroundColor: [
                                'rgb(249, 115, 22)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
                console.log('Report status chart initialized');
            } else {
                console.error('Report status chart canvas not found');
            }

            // 3. User Role Pie Chart
            const userRoleCanvas = document.getElementById('userRoleChart');
            if (userRoleCanvas) {
                console.log('User role chart canvas found');
                const userRoleCtx = userRoleCanvas.getContext('2d');
                new Chart(userRoleCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Admin', 'PO Officer', 'Field User'],
                        datasets: [{
                            data: [
                                chartData.userRoleStats.admin,
                                chartData.userRoleStats.po,
                                chartData.userRoleStats.user
                            ],
                            backgroundColor: [
                                'rgb(147, 51, 234)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)'
                            ],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
                console.log('User role chart initialized');
            } else {
                console.error('User role chart canvas not found');
            }

            // 4. Weekly Activity Bar Chart
            const weeklyActivityCanvas = document.getElementById('weeklyActivityChart');
            if (weeklyActivityCanvas) {
                console.log('Weekly activity chart canvas found');
                const weeklyActivityCtx = weeklyActivityCanvas.getContext('2d');
                new Chart(weeklyActivityCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.weeklyActivity.map(item => item.date),
                        datasets: [{
                            label: 'Transaksi',
                            data: chartData.weeklyActivity.map(item => item.transactions),
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgb(249, 115, 22)',
                            borderWidth: 1,
                            borderRadius: 4
                        }, {
                            label: 'Laporan',
                            data: chartData.weeklyActivity.map(item => item.reports),
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1,
                            borderRadius: 4
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
                                    padding: 15
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
                console.log('Weekly activity chart initialized');
            } else {
                console.error('Weekly activity chart canvas not found');
            }
            
            console.log('All charts initialization completed');
        }
    </script>
</x-admin-layout>
