<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail PO Material') }}
            </h2>
            <div class="flex space-x-2">
                @if($poMaterial->status === 'pending')
                <a href="{{ route('po.po-materials.edit', $poMaterial) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Edit
                </a>
                @endif
                <a href="{{ route('po.po-materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Main Information Card -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Informasi PO Material</h3>
                        {!! $poMaterial->status_badge !!}
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- No. PO -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. PO</label>
                            <p class="text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->po_number }}
                            </p>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->supplier }}
                            </p>
                        </div>

                        <!-- Tanggal Rilis -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rilis</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->release_date ? \Carbon\Carbon::parse($poMaterial->release_date)->format('d F Y') : 'N/A' }}
                            </p>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->location }}
                            </p>
                        </div>

                        <!-- Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->project->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Sub Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Project</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->subProject->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->formatted_quantity }}
                            </p>
                        </div>

                        <!-- User -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat Oleh</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->user->name ?? 'N/A' }}
                            </p>
                        </div>

                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Nama Material)</label>
                        <div class="bg-gray-50 px-4 py-3 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $poMaterial->description }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($poMaterial->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <div class="bg-gray-50 px-4 py-3 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $poMaterial->notes }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Timestamps Card -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Waktu</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat Pada</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->created_at->format('d F Y, H:i:s') }}
                            </p>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diperbarui</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->updated_at->format('d F Y, H:i:s') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
