<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail BOQ Actual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-white">Detail BOQ Actual</h3>
                            <p class="text-green-100 text-sm">Informasi lengkap data pemakaian material aktual</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.boq-actuals.edit', $boqActual) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-sm font-medium text-white hover:bg-opacity-30 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('admin.boq-actuals.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-sm font-medium text-white hover:bg-opacity-30 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Main Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Project Information -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Informasi Proyek</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Proyek</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->project->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Sub Proyek</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->subProject->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Cluster</label>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $boqActual->cluster }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nomor DN</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->dn_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Material Information -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Informasi Material</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nama Material</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->material->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $boqActual->material->category->name ?? 'No Category' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Quantity Terpakai</label>
                                    <p class="mt-1">
                                        <span class="text-2xl font-bold text-green-600">{{ number_format($boqActual->actual_quantity, 2) }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $boqActual->material->unit }}</span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tanggal Pemakaian</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->usage_date->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($boqActual->notes)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Catatan</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $boqActual->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- System Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Sistem</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Admin Input</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Input</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->created_at->format('d F Y H:i') }}</p>
                            </div>

                            @if($boqActual->updated_at != $boqActual->created_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Terakhir Diperbarui</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->updated_at->format('d F Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.boq-actuals.edit', $boqActual) }}" 
                               class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Data
                            </a>
                        </div>

                        <form action="{{ route('admin.boq-actuals.destroy', $boqActual) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus data BOQ Actual ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>