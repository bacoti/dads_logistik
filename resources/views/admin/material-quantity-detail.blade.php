<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Quantity Material') }}
        </h2>
    </x-slot>
<div class="min-h-screen bg-gray-50">
    <div class="main-content-wrapper transition-layout">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-3xl font-bold text-gray-900">Detail Quantity Material</h1>
                        <p class="text-gray-600 mt-2">Ringkasan lengkap total quantity material berdasarkan kategori</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="window.history.back()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-layout">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali
                        </button>
                        <a href="{{ route('admin.analytics.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analytics Dashboard
                        </a>
                        <button onclick="location.reload()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-layout">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                        <button onclick="exportData('excel')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                            </svg>
                            Filter Material
                        </h3>
                        <button id="toggleFilter" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div id="filterContent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Filter by Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select id="filterCategory" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Kategori</option>
                            </select>
                        </div>

                        <!-- Filter by Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Utama</label>
                            <select id="filterProject" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Project</option>
                            </select>
                        </div>

                        <!-- Filter by Sub Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sub Project</label>
                            <select id="filterSubProject" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Sub Project</option>
                            </select>
                        </div>

                        <!-- Filter by Source -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Data</label>
                            <select id="filterSource" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Sumber</option>
                                <option value="Transaction">Transaction</option>
                                <option value="PO Material">PO Material (Approved)</option>
                            </select>
                        </div>

                        <!-- Filter by Minimum Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Minimum</label>
                            <input type="number" id="filterMinQuantity" min="0" step="0.01"
                                   placeholder="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Search Input -->
                        <div class="md:col-span-2 lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Material</label>
                            <div class="relative">
                                <input type="text" id="searchMaterial" 
                                       placeholder="Ketik nama material..."
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="md:col-span-2 lg:col-span-2 flex items-end gap-3">
                            <button onclick="applyFilters()" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                                </svg>
                                Terapkan Filter
                            </button>
                            <button onclick="resetFilters()" 
                                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    <div id="activeFilters" class="mt-4 hidden">
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Filter Aktif:</h4>
                            <div id="filterTags" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="adaptive-grid mb-8" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 transition-layout">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $materialQuantitySummary['total_categories'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 transition-layout">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Material</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $materialQuantitySummary['total_materials'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 transition-layout">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Kuantitas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($materialQuantitySummary['total_quantity_all'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 transition-layout">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Dari Transaksi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($materialQuantitySummary['total_quantity_transactions'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PO Material Statistics -->
            @if(isset($materialQuantitySummary['po_statistics']))
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Statistik PO Material</h3>
                                <p class="text-gray-600 text-sm">Status persetujuan Purchase Order Material - hanya yang disetujui yang dihitung dalam quantity</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Live Data
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-gray-700">{{ $materialQuantitySummary['po_statistics']->total_po_requests ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Total PO</div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-yellow-700">{{ $materialQuantitySummary['po_statistics']->pending_requests ?? 0 }}</div>
                                <div class="text-sm text-yellow-600">Pending</div>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-700">{{ $materialQuantitySummary['po_statistics']->approved_requests ?? 0 }}</div>
                                <div class="text-sm text-green-600">Approved</div>
                            </div>
                            
                            <div class="bg-red-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-red-700">{{ $materialQuantitySummary['po_statistics']->rejected_requests ?? 0 }}</div>
                                <div class="text-sm text-red-600">Rejected</div>
                            </div>
                            
                            <div class="bg-green-100 rounded-lg p-4 text-center">
                                <div class="text-lg font-bold text-green-800">{{ number_format($materialQuantitySummary['po_statistics']->approved_quantity ?? 0, 2) }}</div>
                                <div class="text-xs text-green-700">Qty Approved</div>
                            </div>
                            
                            <div class="bg-yellow-100 rounded-lg p-4 text-center">
                                <div class="text-lg font-bold text-yellow-800">{{ number_format($materialQuantitySummary['po_statistics']->pending_quantity ?? 0, 2) }}</div>
                                <div class="text-xs text-yellow-700">Qty Pending</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <strong>Catatan Penting:</strong> Hanya PO Material dengan status "Approved" yang dihitung dalam total quantity material. 
                                    PO yang masih "Pending" atau "Rejected" tidak termasuk karena barang belum benar-benar masuk ke sistem.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Material Data Grouped by Category -->
            @if(isset($materialQuantitySummary['materials_by_category']) && $materialQuantitySummary['materials_by_category']->count() > 0)
                @foreach($materialQuantitySummary['materials_by_category'] as $categoryName => $materials)
                <div class="mb-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $categoryName }}</h3>
                                    <p class="text-gray-600 text-sm">Material dalam kategori {{ $categoryName }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $materials->count() }} Material
                                </span>
                            </div>
                        </div>

                        <div class="responsive-table custom-scrollbar">
                            <table class="min-w-full divide-y divide-gray-200" data-category="{{ $categoryName }}">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Utama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($materials as $material)
                                    <tr class="hover:bg-gray-50 material-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $material->material_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $material->project_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $material->sub_project_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ number_format($material->total_quantity, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $material->unit ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $material->user_count ?? ($material->po_count ?? 0) }} {{ $material->source_type == 'Transaction' ? 'users' : 'PO' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $material->source_type == 'Transaction' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $material->source_type }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada data material</h3>
                    <p class="mt-2 text-sm text-gray-500">Belum ada material yang diinput oleh user atau dalam PO material.</p>
                </div>
            @endif

        </div>
    </div>

    <style>
        /* Responsive Table CSS */
        .responsive-table {
            overflow-x: auto;
            transition: all 0.3s ease;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }

        /* Adaptive Grid Layout */
        .adaptive-grid {
            display: grid;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Sidebar responsive behavior */
        .sidebar-open .adaptive-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .sidebar-closed .adaptive-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .sidebar-open .responsive-table {
            max-width: calc(100vw - 280px);
        }

        .sidebar-closed .responsive-table {
            max-width: calc(100vw - 100px);
        }

        /* Smooth transitions */
        .transition-layout {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar-open .responsive-table,
            .sidebar-closed .responsive-table {
                max-width: calc(100vw - 2rem);
            }
            
            .adaptive-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Desktop specific adjustments */
        @media (min-width: 1024px) {
            .main-content-wrapper {
                transition: margin-left 0.3s ease, width 0.3s ease;
            }
        }

        /* Filter specific styles */
        .filter-section {
            transition: all 0.3s ease;
        }

        .filter-toggle-icon {
            transition: transform 0.2s ease;
        }

        .filter-tag {
            animation: fadeIn 0.2s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-2px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .filter-dropdown:focus {
            ring: 2px;
            ring-blue-500;
            border-color: #3B82F6;
        }

        /* Enhanced table hover effects */
        .material-row {
            transition: all 0.2s ease;
        }

        .material-row:hover {
            background-color: #F9FAFB;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Loading state styles */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3B82F6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar detection for responsive behavior
            const sidebar = document.querySelector('aside');
            const mainContent = document.querySelector('.main-content-wrapper');
            
            if (sidebar && mainContent) {
                // Initial check
                updateSidebarState();
                
                // Create observer for sidebar changes
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && 
                            (mutation.attributeName === 'class' || mutation.attributeName === 'style')) {
                            updateSidebarState();
                        }
                    });
                });
                
                // Observe sidebar changes
                observer.observe(sidebar, {
                    attributes: true,
                    attributeOldValue: true
                });
                
                // Also observe document for class changes
                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }
            
            function updateSidebarState() {
                const body = document.body;
                const sidebar = document.querySelector('aside');
                
                if (sidebar) {
                    const sidebarRect = sidebar.getBoundingClientRect();
                    const isVisible = sidebarRect.width > 0 && getComputedStyle(sidebar).display !== 'none';
                    
                    if (isVisible && sidebarRect.width > 200) {
                        body.classList.add('sidebar-open');
                        body.classList.remove('sidebar-closed');
                    } else {
                        body.classList.add('sidebar-closed');
                        body.classList.remove('sidebar-open');
                    }
                }
            }
            
            // Handle window resize
            window.addEventListener('resize', updateSidebarState);

            // Initialize filter functionality
            initializeFilters();
        });

        // Global variables for filter data
        let allMaterialData = [];
        let filteredData = [];
        let filterOptions = {
            categories: new Set(),
            projects: new Set(),
            subProjects: new Set(),
            sources: new Set()
        };

        // Initialize filter functionality
        function initializeFilters() {
            // Extract data from DOM
            extractMaterialData();
            
            // Populate filter dropdowns
            populateFilterOptions();
            
            // Setup event listeners
            setupFilterEventListeners();
            
            // Initialize filter toggle
            setupFilterToggle();
        }

        // Extract material data from DOM
        function extractMaterialData() {
            allMaterialData = [];
            const materialSections = document.querySelectorAll('[data-category]');
            
            materialSections.forEach(section => {
                const categoryName = section.getAttribute('data-category');
                const rows = section.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 7) {
                        const materialData = {
                            category: categoryName,
                            material: cells[0].textContent.trim(),
                            project: cells[1].textContent.trim(),
                            subProject: cells[2].textContent.trim(),
                            quantity: cells[3].textContent.trim(),
                            unit: cells[4].textContent.trim(),
                            userCount: cells[5].textContent.trim(),
                            source: cells[6].textContent.trim(),
                            element: row
                        };
                        
                        allMaterialData.push(materialData);
                        
                        // Collect unique values for filter options
                        filterOptions.categories.add(categoryName);
                        if (materialData.project !== '-') filterOptions.projects.add(materialData.project);
                        if (materialData.subProject !== '-') filterOptions.subProjects.add(materialData.subProject);
                        filterOptions.sources.add(materialData.source);
                    }
                });
            });
            
            filteredData = [...allMaterialData];
        }

        // Populate filter dropdown options
        function populateFilterOptions() {
            const categorySelect = document.getElementById('filterCategory');
            const projectSelect = document.getElementById('filterProject');
            const subProjectSelect = document.getElementById('filterSubProject');
            
            // Populate categories
            Array.from(filterOptions.categories).sort().forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
            
            // Populate projects
            Array.from(filterOptions.projects).sort().forEach(project => {
                const option = document.createElement('option');
                option.value = project;
                option.textContent = project;
                projectSelect.appendChild(option);
            });
            
            // Populate sub projects
            Array.from(filterOptions.subProjects).sort().forEach(subProject => {
                const option = document.createElement('option');
                option.value = subProject;
                option.textContent = subProject;
                subProjectSelect.appendChild(option);
            });
        }

        // Setup filter event listeners
        function setupFilterEventListeners() {
            const filterElements = ['filterCategory', 'filterProject', 'filterSubProject', 'filterSource', 'searchMaterial'];
            
            filterElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', applyFilters);
                    if (id === 'searchMaterial') {
                        element.addEventListener('input', debounce(applyFilters, 300));
                    }
                }
            });
        }

        // Setup filter toggle functionality
        function setupFilterToggle() {
            const toggleButton = document.getElementById('toggleFilter');
            const filterContent = document.getElementById('filterContent');
            const toggleIcon = toggleButton.querySelector('svg');
            
            toggleButton.addEventListener('click', function() {
                const isHidden = filterContent.style.display === 'none';
                
                if (isHidden) {
                    filterContent.style.display = 'grid';
                    toggleIcon.style.transform = 'rotate(0deg)';
                } else {
                    filterContent.style.display = 'none';
                    toggleIcon.style.transform = 'rotate(-90deg)';
                }
            });
        }

        // Apply filters function
        function applyFilters() {
            const categoryFilter = document.getElementById('filterCategory').value;
            const projectFilter = document.getElementById('filterProject').value;
            const subProjectFilter = document.getElementById('filterSubProject').value;
            const sourceFilter = document.getElementById('filterSource').value;
            const searchFilter = document.getElementById('searchMaterial').value.toLowerCase();
            const minQuantityFilter = parseFloat(document.getElementById('filterMinQuantity').value) || 0;
            
            filteredData = allMaterialData.filter(item => {
                const matchCategory = !categoryFilter || item.category === categoryFilter;
                const matchProject = !projectFilter || item.project === projectFilter;
                const matchSubProject = !subProjectFilter || item.subProject === subProjectFilter;
                const matchSource = !sourceFilter || item.source === sourceFilter;
                const matchSearch = !searchFilter || item.material.toLowerCase().includes(searchFilter);
                const matchQuantity = item.quantity >= minQuantityFilter;
                
                return matchCategory && matchProject && matchSubProject && matchSource && matchSearch && matchQuantity;
            });
            
            updateDisplayedData();
            updateActiveFilters();
            updateSummaryCards();
        }

        // Reset filters function
        function resetFilters() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterProject').value = '';
            document.getElementById('filterSubProject').value = '';
            document.getElementById('filterSource').value = '';
            document.getElementById('searchMaterial').value = '';
            document.getElementById('filterMinQuantity').value = '';
            
            filteredData = [...allMaterialData];
            updateDisplayedData();
            updateActiveFilters();
            updateSummaryCards();
        }

        // Update displayed data based on filters
        function updateDisplayedData() {
            // Hide all rows first
            allMaterialData.forEach(item => {
                item.element.style.display = 'none';
            });
            
            // Show filtered rows
            filteredData.forEach(item => {
                item.element.style.display = '';
            });
            
            // Hide empty category sections
            const categorySections = document.querySelectorAll('[data-category]');
            categorySections.forEach(section => {
                const visibleRows = section.querySelectorAll('tbody tr[style=""]').length;
                const categoryDiv = section.closest('.mb-8');
                
                if (visibleRows === 0) {
                    categoryDiv.style.display = 'none';
                } else {
                    categoryDiv.style.display = 'block';
                    // Update material count badge
                    const badge = section.querySelector('.bg-indigo-100');
                    if (badge) {
                        badge.textContent = `${visibleRows} Material`;
                    }
                }
            });
        }

        // Update active filters display
        function updateActiveFilters() {
            const activeFiltersDiv = document.getElementById('activeFilters');
            const filterTagsDiv = document.getElementById('filterTags');
            
            filterTagsDiv.innerHTML = '';
            let hasActiveFilters = false;
            
            const filters = [
                { id: 'filterCategory', label: 'Kategori' },
                { id: 'filterProject', label: 'Project' },
                { id: 'filterSubProject', label: 'Sub Project' },
                { id: 'filterSource', label: 'Sumber' },
                { id: 'searchMaterial', label: 'Pencarian' }
            ];
            
            filters.forEach(filter => {
                const element = document.getElementById(filter.id);
                if (element && element.value) {
                    hasActiveFilters = true;
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                    tag.innerHTML = `
                        ${filter.label}: ${element.value}
                        <button onclick="clearFilter('${filter.id}')" class="ml-1 text-blue-600 hover:text-blue-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    filterTagsDiv.appendChild(tag);
                }
            });
            
            if (hasActiveFilters) {
                activeFiltersDiv.classList.remove('hidden');
            } else {
                activeFiltersDiv.classList.add('hidden');
            }
        }

        // Clear individual filter
        function clearFilter(filterId) {
            document.getElementById(filterId).value = '';
            applyFilters();
        }

        // Update summary cards based on filtered data
        function updateSummaryCards() {
            const totalCategories = new Set(filteredData.map(item => item.category)).size;
            const totalMaterials = filteredData.length;
            const totalQuantity = filteredData.reduce((sum, item) => {
                const qty = parseFloat(item.quantity.replace(/,/g, '')) || 0;
                return sum + qty;
            }, 0);
            const transactionQuantity = filteredData
                .filter(item => item.source === 'Transaction')
                .reduce((sum, item) => {
                    const qty = parseFloat(item.quantity.replace(/,/g, '')) || 0;
                    return sum + qty;
                }, 0);
            
            // Update the summary cards
            const summaryCards = document.querySelectorAll('.adaptive-grid .bg-white');
            if (summaryCards.length >= 4) {
                summaryCards[0].querySelector('.text-2xl').textContent = totalCategories;
                summaryCards[1].querySelector('.text-2xl').textContent = totalMaterials;
                summaryCards[2].querySelector('.text-2xl').textContent = totalQuantity.toLocaleString('id-ID', { minimumFractionDigits: 2 });
                summaryCards[3].querySelector('.text-2xl').textContent = transactionQuantity.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            }
        }

        // Debounce function for search input
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Export functionality
        function exportData(type) {
            if (type === 'excel') {
                // Simple CSV export for now
                let csvContent = "data:text/csv;charset=utf-8,";
                
                // Add headers
                csvContent += "Kategori,Material,Project Utama,Sub Project,Total Quantity,Satuan,Jumlah User,Sumber\n";
                
                // Get all table data
                const tables = document.querySelectorAll('.responsive-table table');
                tables.forEach(table => {
                    const categoryTitle = table.closest('.bg-white').querySelector('h3').textContent;
                    const rows = table.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 7) {
                            const material = cells[0].textContent.trim();
                            const project = cells[1].textContent.trim();
                            const subProject = cells[2].textContent.trim();
                            const quantity = cells[3].textContent.trim();
                            const unit = cells[4].textContent.trim();
                            const userCount = cells[5].textContent.trim();
                            const source = cells[6].textContent.trim();
                            
                            csvContent += `"${categoryTitle}","${material}","${project}","${subProject}","${quantity}","${unit}","${userCount}","${source}"\n`;
                        }
                    });
                });
                
                // Download file
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `material_quantity_summary_${new Date().toISOString().split('T')[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    </script>
</div>
</x-admin-layout>
