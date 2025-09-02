<x-app-layout>
    <x-page-header 
        title="Material Receipt" 
        subtitle="Kelola penerimaan material dari supplier"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('po.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Material Receipt']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button 
                variant="secondary"
                href="{{ route('po.po-materials.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Lihat PO Materials
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Info Alert -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Halaman ini menampilkan PO Material yang sudah <strong>disetujui</strong> dan siap untuk diinput penerimaan materialnya dari supplier.
                    </p>
                </div>
            </div>
        </div>

        @if($poMaterials->count() > 0)
        <!-- PO Materials Cards -->
        <div class="grid gap-6">
            @foreach($poMaterials as $po)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-600 text-white rounded-lg p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $po->po_number }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $po->supplier }} • {{ $po->project->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            {!! $po->status_badge !!}
                            <span class="text-sm text-gray-500">{{ $po->release_date ? $po->release_date->format('d M Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Materials Summary -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-gray-900">Material Items ({{ $po->items->count() }})</h4>
                        <div class="flex space-x-2">
                            <a href="{{ route('po.material-receipt.show', $po) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('po.material-receipt.create', $po) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Input Penerimaan
                            </a>
                        </div>
                    </div>

                    <!-- Materials Preview -->
                    <div class="space-y-3">
                        @foreach($po->items->take(3) as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-gray-600 rounded-full">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $item->description }}">
                                        {{ Str::limit($item->description, 60) }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                                        <span>PO: {{ $item->formatted_quantity }}</span>
                                        <span>Diterima: {{ $item->total_received ?? 0 }} {{ $item->unit }}</span>
                                        @if($item->total_received > 0)
                                            <span class="text-green-600">{{ round(($item->total_received / $item->quantity) * 100, 1) }}%</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Receipt Status -->
                            @php
                                $received = $item->total_received ?? 0;
                                $percentage = $item->quantity > 0 ? ($received / $item->quantity) * 100 : 0;
                            @endphp
                            
                            @if($received >= $item->quantity)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Complete
                                </span>
                            @elseif($received > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ Partial
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ❌ Pending
                                </span>
                            @endif
                        </div>
                        @endforeach

                        @if($po->items->count() > 3)
                        <div class="text-center text-sm text-gray-500 py-2">
                            +{{ $po->items->count() - 3 }} material lainnya
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($poMaterials->hasPages())
        <div class="mt-8">
            {{ $poMaterials->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Material yang Siap Diterima</h3>
            <p class="text-gray-500 mb-6">
                Belum ada PO Material yang disetujui dan menunggu penerimaan material.<br>
                Pastikan PO Material sudah dibuat dan disetujui terlebih dahulu.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('po.po-materials.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat PO Material Baru
                </a>
                <a href="{{ route('po.po-materials.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Lihat Semua PO Material
                </a>
            </div>
        </div>
        @endif
    </div>

    @push('styles')
    <style>
        .receipt-progress {
            background: linear-gradient(90deg, #10b981 var(--progress-width, 0%), #f3f4f6 var(--progress-width, 0%));
        }
    </style>
    @endpush
</x-app-layout>
