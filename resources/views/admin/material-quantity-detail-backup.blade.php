<x-admin-layout>
    <x-slot name="header">
        <!-- Material Quantity Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg rounded-b-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Ringkasan Quantity Material</h1>
                        <p class="text-blue-100 text-lg">Total quantity setiap material yang telah diinput oleh user</p>
                        <div class="flex items-center mt-3 text-blue-100">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span class="font-medium">Data Real-time dari Transaksi & PO Material</span>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div class="text-white text-sm font-medium">Total Material Types</div>
                            <div class="text-white text-lg font-bold">
                                {{ $materialQuantitySummary['total_transaction_materials'] + $materialQuantitySummary['total_po_materials'] }}
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-4 py-3">
                            <div class="text-white text-sm font-medium">Total Quantity</div>
                            <div class="text-white text-lg font-bold">
                                {{ number_format($materialQuantitySummary['total_quantity_transactions'] + $materialQuantitySummary['total_quantity_po'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50" id="main-content">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all duration-300 ease-in-out" id="content-wrapper">

            <!-- Quick Navigation -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Material Quantity</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <div class="flex items-center space-x-3 desktop-controls">
                        <button onclick="optimizedPrint()" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Report
                        </button>
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
                            <table class="min-w-full divide-y divide-gray-200">
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
                                    <tr class="hover:bg-gray-50">
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

            <!-- Material dari PO -->
            @if($materialQuantitySummary['po_materials']->count() > 0)
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Material dari PO Material</h3>
                                <p class="text-gray-600 text-sm">Material yang telah diajukan melalui Purchase Order</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $materialQuantitySummary['po_materials']->count() }} Material
                            </span>
                        </div>
                    </div>

                    <div class="responsive-table custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah PO</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($materialQuantitySummary['po_materials'] as $material)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $material->material_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $material->category_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-green-600">{{ number_format($material->total_quantity, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $material->unit }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $material->user_count }} User
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $material->po_count }} PO
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Top 10 Material Terpopuler -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Top 10 Material dari Transaksi -->
                @if($materialQuantitySummary['most_used_materials']->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Top 10 Material Terbanyak (Transaksi)</h3>
                        <p class="text-gray-600 text-sm">Material dengan quantity tertinggi dari transaksi user</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($materialQuantitySummary['most_used_materials'] as $index => $material)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $material->material_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $material->category_name }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-purple-600">{{ number_format($material->total_quantity, 2) }}</div>
                                    <div class="text-sm text-gray-500">{{ $material->unit }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Top 10 Material dari PO -->
                @if($materialQuantitySummary['most_requested_po']->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Top 10 Material Terbanyak (PO)</h3>
                        <p class="text-gray-600 text-sm">Material dengan quantity tertinggi dari Purchase Order</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($materialQuantitySummary['most_requested_po'] as $index => $material)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $material->material_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $material->category_name }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-orange-600">{{ number_format($material->total_quantity, 2) }}</div>
                                    <div class="text-sm text-gray-500">{{ $material->unit }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Empty State jika tidak ada data -->
            @if($materialQuantitySummary['transaction_materials']->count() === 0 && $materialQuantitySummary['po_materials']->count() === 0)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Data Material</h3>
                <p class="text-gray-600 mb-6">
                    Belum ada material yang diinput oleh user melalui transaksi atau PO Material.
                    Data akan muncul setelah user mulai melakukan transaksi.
                </p>
                <a href="{{ route('admin.transactions.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Lihat Data Transaksi
                </a>
            </div>
            @endif

            <!-- Footer Note -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold mb-1">Informasi Data</h3>
                            <p class="text-gray-300">
                                Data quantity material diambil real-time dari transaksi user lapangan dan pengajuan PO Material. 
                                Update otomatis setiap kali ada transaksi baru.
                            </p>
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-400">
                        <div>Last Updated:</div>
                        <div class="font-medium">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') }} WIB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            .bg-gradient-to-r { background: #f3f4f6 !important; }
            .shadow-xl { box-shadow: none !important; }
        }

        /* Responsive Layout untuk Sidebar */
        #main-content {
            transition: all 0.3s ease-in-out;
        }

        /* Ketika sidebar terbuka (default) */
        @media (min-width: 1024px) {
            .sidebar-open #content-wrapper {
                max-width: calc(100vw - 16rem); /* 256px = 16rem sidebar width */
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Ketika sidebar tertutup */
        @media (min-width: 1024px) {
            .sidebar-closed #content-wrapper {
                max-width: calc(100vw - 4rem); /* 64px = 4rem collapsed sidebar width */
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        /* Mobile responsive */
        @media (max-width: 1023px) {
            #content-wrapper {
                max-width: 100vw;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Smooth transitions untuk semua elemen */
        .transition-layout {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Optimized table untuk responsive */
        .responsive-table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .responsive-table table {
            min-width: 100%;
            white-space: nowrap;
        }

        /* Card container adjustments */
        .adaptive-grid {
            display: grid;
            gap: 1.5rem;
            transition: all 0.3s ease-in-out;
        }

        /* Grid responsiveness berdasarkan lebar kontainer */
        .sidebar-open .adaptive-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .sidebar-closed .adaptive-grid {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        @media (max-width: 768px) {
            .adaptive-grid {
                grid-template-columns: 1fr !important;
            }
        }

        /* Enhanced scrollbar untuk table */
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Floating action button untuk mobile */
        .floating-refresh {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
            display: none;
        }

        @media (max-width: 768px) {
            .floating-refresh {
                display: block;
            }
            
            .desktop-controls {
                display: none;
            }
        }

        /* Loading state */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Deteksi perubahan sidebar state
            function updateLayoutForSidebar() {
                const sidebar = document.querySelector('[data-sidebar]') || document.querySelector('aside') || document.querySelector('.sidebar');
                const mainContent = document.getElementById('main-content');
                
                if (!mainContent) return;

                // Cek apakah sidebar terbuka atau tertutup berdasarkan class atau style
                const sidebarCollapsed = sidebar ? (
                    sidebar.classList.contains('collapsed') || 
                    sidebar.classList.contains('closed') ||
                    getComputedStyle(sidebar).width === '64px' ||
                    getComputedStyle(sidebar).transform.includes('translateX')
                ) : false;

                // Update class pada main content
                if (sidebarCollapsed) {
                    mainContent.classList.add('sidebar-closed');
                    mainContent.classList.remove('sidebar-open');
                } else {
                    mainContent.classList.add('sidebar-open');
                    mainContent.classList.remove('sidebar-closed');
                }

                // Trigger resize event untuk chart yang mungkin ada
                window.dispatchEvent(new Event('resize'));
            }

            // Jalankan saat load
            updateLayoutForSidebar();

            // Monitor perubahan sidebar dengan MutationObserver
            const targetNode = document.body;
            const config = { attributes: true, childList: true, subtree: true };
            
            const callback = function(mutationsList, observer) {
                for (let mutation of mutationsList) {
                    if (mutation.type === 'attributes') {
                        // Cek jika ada perubahan class atau style pada sidebar
                        if (mutation.target.matches('aside, .sidebar, [data-sidebar]')) {
                            updateLayoutForSidebar();
                        }
                    }
                }
            };

            const observer = new MutationObserver(callback);
            observer.observe(targetNode, config);

            // Listen untuk click events pada toggle sidebar button
            document.addEventListener('click', function(e) {
                if (e.target.closest('[data-sidebar-toggle]') || 
                    e.target.closest('.sidebar-toggle') ||
                    e.target.closest('button[aria-controls*="sidebar"]')) {
                    // Delay sedikit untuk memastikan transisi sidebar selesai
                    setTimeout(updateLayoutForSidebar, 350);
                }
            });

            // Listen untuk resize window
            window.addEventListener('resize', updateLayoutForSidebar);

            // Smooth scroll untuk internal navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Enhanced loading state untuk refresh button
            const refreshBtn = document.querySelector('[onclick="location.reload()"]');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = `
                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Refreshing...
                    `;
                    this.disabled = true;
                    
                    // Simulate loading then reload
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
            }

            // Add intersection observer untuk smooth animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe semua cards
            document.querySelectorAll('.bg-white').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                cardObserver.observe(card);
            });

            // Auto-hide floating button saat scroll up
            let lastScrollTop = 0;
            const floatingBtn = document.querySelector('.floating-refresh');
            
            if (floatingBtn) {
                window.addEventListener('scroll', function() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    if (scrollTop > lastScrollTop) {
                        // Scrolling down
                        floatingBtn.style.transform = 'translateY(100px)';
                    } else {
                        // Scrolling up
                        floatingBtn.style.transform = 'translateY(0)';
                    }
                    lastScrollTop = scrollTop;
                });
            }

            console.log('✅ Material Quantity Detail page loaded with enhanced responsiveness');
        });

        // Function untuk export data (future enhancement)
        function exportData(format = 'excel') {
            const loadingToast = document.createElement('div');
            loadingToast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            loadingToast.textContent = `Exporting data to ${format.toUpperCase()}...`;
            document.body.appendChild(loadingToast);

            setTimeout(() => {
                loadingToast.remove();
                // Here you would implement actual export logic
                alert(`Export to ${format.toUpperCase()} will be implemented soon!`);
            }, 2000);
        }

        // Function untuk print optimization
        function optimizedPrint() {
            // Hide sidebar dan elemen non-print sebelum print
            const sidebar = document.querySelector('aside');
            const nav = document.querySelector('nav');
            
            if (sidebar) sidebar.style.display = 'none';
            if (nav) nav.style.display = 'none';

            window.print();

            // Restore setelah print
            setTimeout(() => {
                if (sidebar) sidebar.style.display = '';
                if (nav) nav.style.display = '';
            }, 1000);
        }
    </script>
</x-admin-layout>
