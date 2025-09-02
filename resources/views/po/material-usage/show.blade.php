<x-app-layout>
    <x-page-header 
        title="Detail Penggunaan Material" 
        subtitle="Informasi detail penggunaan material"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('po.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Material Usage', 'url' => route('po.material-usage.index')],
            ['title' => 'Detail #' . $usage->id]
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button 
                variant="secondary"
                href="{{ route('po.material-usage.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Usage Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Penggunaan</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Usage Transaction
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $usage->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Penggunaan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usage->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usage->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipe Transaksi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($usage->type) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Material</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-lg bg-red-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Material</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $usage->poMaterialItem->nama_material }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Satuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $usage->poMaterialItem->satuan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Digunakan</dt>
                                    <dd class="mt-1 text-lg font-bold text-red-600">-{{ number_format($usage->quantity, 0) }} {{ $usage->poMaterialItem->satuan }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">From PO</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $usage->poMaterialItem->poMaterial->nomor_po }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Impact -->
            @php
                $currentStock = $usage->poMaterialItem->stock;
                $stockBeforeUsage = $currentStock ? $currentStock->available_quantity + $usage->quantity : $usage->quantity;
            @endphp
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Dampak Stock</h3>
                    <p class="mt-1 text-sm text-gray-600">Perubahan stock akibat penggunaan material ini</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Stock Sebelum</dt>
                            <dd class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($stockBeforeUsage, 0) }}</dd>
                            <dd class="text-sm text-gray-500">{{ $usage->poMaterialItem->satuan }}</dd>
                        </div>
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Penggunaan</dt>
                            <dd class="mt-2 text-2xl font-bold text-red-600">-{{ number_format($usage->quantity, 0) }}</dd>
                            <dd class="text-sm text-gray-500">{{ $usage->poMaterialItem->satuan }}</dd>
                        </div>
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Stock Setelah</dt>
                            <dd class="mt-2 text-2xl font-bold text-green-600">{{ number_format($currentStock ? $currentStock->available_quantity : 0, 0) }}</dd>
                            <dd class="text-sm text-gray-500">{{ $usage->poMaterialItem->satuan }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Notes -->
            @if($usage->notes)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Catatan Penggunaan</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $usage->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related PO Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi PO Terkait</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor PO</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usage->poMaterialItem->poMaterial->nomor_po }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Supplier</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $usage->poMaterialItem->poMaterial->nama_supplier }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal PO</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($usage->poMaterialItem->poMaterial->tanggal_po)->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status PO</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($usage->poMaterialItem->poMaterial->status === 'approved') bg-green-100 text-green-800
                                    @elseif($usage->poMaterialItem->poMaterial->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($usage->poMaterialItem->poMaterial->status) }}
                                </span>
                            </dd>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('po.material-receipt.show', $usage->poMaterialItem->poMaterial) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Detail PO
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
