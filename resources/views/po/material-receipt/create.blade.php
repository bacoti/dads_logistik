<x-app-layout>
    <x-page-header 
        title="Terima Material" 
        subtitle="Catat penerimaan material dari supplier: {{ $poMaterial->nama_supplier }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('po.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Material Receipt', 'url' => route('po.material-receipt.index')],
            ['title' => $poMaterial->nomor_po, 'url' => route('po.material-receipt.show', $poMaterial)],
            ['title' => 'Terima Material']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button 
                variant="secondary"
                href="{{ route('po.material-receipt.show', $poMaterial) }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- PO Summary -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi PO</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor PO</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $poMaterial->nomor_po }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Supplier</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $poMaterial->nama_supplier }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal PO</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($poMaterial->tanggal_po)->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kontak Supplier</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $poMaterial->kontak_supplier }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Form -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Form Penerimaan Material</h3>
                    <p class="mt-1 text-sm text-gray-600">Pilih material yang akan diterima dan masukkan jumlah yang diterima</p>
                </div>
                
                <form action="{{ route('po.material-receipt.store', $poMaterial) }}" method="POST" x-data="receiptForm()">
                    @csrf
                    <div class="p-6">
                        <!-- Material Selection -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Material yang Tersedia</label>
                            
                            @foreach($poMaterial->items as $material)
                                @php
                                    $receivedQty = $material->transactions ? $material->transactions->where('type', 'receipt')->sum('quantity') : 0;
                                    $remainingQty = $material->quantity - $receivedQty;
                                @endphp
                                
                                @if($remainingQty > 0)
                                <div class="border border-gray-200 rounded-lg p-4" x-data="{ selected: false }">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   x-model="selected"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <div class="ml-3">
                                                <label class="text-sm font-medium text-gray-900">{{ $material->nama_material }}</label>
                                                <p class="text-sm text-gray-500">{{ $material->unit }}</p>
                                                <p class="text-xs text-gray-500">
                                                    Diminta: {{ number_format($material->quantity, 0) }} | 
                                                    Diterima: {{ number_format($receivedQty, 0) }} | 
                                                    Sisa: {{ number_format($remainingQty, 0) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4" x-show="selected" x-transition>
                                            <div class="flex items-center space-x-2">
                                                <label class="text-sm text-gray-600">Qty Diterima:</label>
                                                <input type="number" 
                                                       name="receipts[{{ $loop->index }}][quantity_received]"
                                                       min="0" 
                                                       max="{{ $remainingQty }}"
                                                       step="0.01"
                                                       placeholder="0"
                                                       x-bind:required="selected"
                                                       class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <label class="text-sm text-gray-600">Kondisi:</label>
                                                <select name="receipts[{{ $loop->index }}][condition]"
                                                        x-bind:required="selected"
                                                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    <option value="">Pilih</option>
                                                    <option value="good">Baik</option>
                                                    <option value="damaged">Rusak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden field for po_material_item_id -->
                                    <input type="hidden" 
                                           name="receipts[{{ $loop->index }}][po_material_item_id]" 
                                           value="{{ $material->id }}"
                                           x-bind:disabled="!selected">
                                    
                                    <!-- Notes for this material -->
                                    <div x-show="selected" x-transition class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                                        <textarea name="receipts[{{ $loop->index }}][notes]"
                                                  rows="2"
                                                  placeholder="Kondisi material, kualitas, dll..."
                                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        @php
                            $totalRemaining = $poMaterial->items->sum(function($m) { 
                                $receivedQty = $m->transactions ? $m->transactions->where('type', 'receipt')->sum('quantity') : 0;
                                return $m->quantity - $receivedQty; 
                            });
                        @endphp

                        @if($totalRemaining <= 0)
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Semua Material Telah Diterima</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada material yang perlu diterima lagi untuk PO ini.</p>
                        </div>
                        @endif

                        <!-- Transaction Date and Location -->
                        @if($totalRemaining > 0)
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal Penerimaan <span class="text-red-500">*</span></label>
                                <input type="date" 
                                       name="transaction_date" 
                                       id="transaction_date"
                                       value="{{ date('Y-m-d') }}"
                                       required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="receipt_location" class="block text-sm font-medium text-gray-700">Lokasi Penerimaan</label>
                                <input type="text" 
                                       name="receipt_location" 
                                       id="receipt_location"
                                       placeholder="Contoh: Gudang A, Site Proyek, dll..."
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- General Notes -->
                        <div class="mt-6">
                            <label for="general_notes" class="block text-sm font-medium text-gray-700">Catatan Umum</label>
                            <textarea name="general_notes" 
                                      id="general_notes"
                                      rows="3"
                                      placeholder="Catatan umum mengenai penerimaan material ini..."
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <x-button 
                                variant="secondary"
                                href="{{ route('po.material-receipt.show', $poMaterial) }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                                Batal
                            </x-button>
                            <x-button 
                                type="submit"
                                variant="primary"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'"
                                class="bg-green-600 hover:bg-green-700 text-white">
                                Simpan Penerimaan
                            </x-button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function receiptForm() {
            return {
                init() {
                    // Form initialization logic if needed
                }
            }
        }
    </script>
</x-app-layout>
