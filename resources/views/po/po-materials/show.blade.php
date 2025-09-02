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

                <!-- Approve Button -->
                <form action="{{ route('po.po-materials.update-status', $poMaterial) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('✅ Apakah Anda yakin ingin menyetujui PO Material ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Setujui
                    </button>
                </form>

                <!-- Cancel Button -->
                <form action="{{ route('po.po-materials.update-status', $poMaterial) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('❌ Apakah Anda yakin ingin membatalkan PO Material ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Batalkan
                    </button>
                </form>
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

                        <!-- Materials Count -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Materials</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                @if($poMaterial->items->count() > 0)
                                    {{ $poMaterial->items->count() }} Material{{ $poMaterial->items->count() > 1 ? 's' : '' }}
                                @else
                                    1 Material (Legacy)
                                @endif
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

                    <!-- Materials List -->
                    <div class="mt-8">
                        <label class="block text-lg font-medium text-gray-700 mb-4">Daftar Material</label>

                        @if($poMaterial->items->count() > 0)
                            <div class="space-y-4">
                                @foreach($poMaterial->items as $index => $item)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-3">
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-sm font-bold rounded-full mr-3">
                                                    {{ $index + 1 }}
                                                </span>
                                                <h4 class="text-sm font-semibold text-gray-800">Material {{ $index + 1 }}</h4>
                                            </div>

                                            <div class="ml-9">
                                                <p class="text-sm text-gray-900 mb-2 whitespace-pre-line">{{ $item->description }}</p>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                                        {{ $item->formatted_quantity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Fallback untuk PO lama -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-yellow-800 mb-2">PO Material Legacy</h4>
                                        <div class="text-sm text-yellow-700">
                                            <p class="mb-2"><strong>Material:</strong> {{ $poMaterial->description }}</p>
                                            <p><strong>Quantity:</strong> {{ $poMaterial->formatted_quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
