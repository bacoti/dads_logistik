@extends('layouts.admin')

@section('title', 'Detail Quantity Material')

@section('content')
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
        });

        // Export functionality
        function exportData(type) {
            if (type === 'excel') {
                // Create a form to submit for Excel export
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.dashboard.export-summary") }}';
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        }
    </script>
</div>
@endsection
