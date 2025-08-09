<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Judul akan dinamis sesuai tipe transaksi --}}
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{
                    items: [{ material_id: '', quantity: 1, document: null }]
                }">

                    {{-- Form utama --}}
                    <form action="{{ route('user.transactions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        {{-- Bagian Informasi Umum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="transaction_date" :value="__('Tanggal Transaksi')" />
                                <x-text-input id="transaction_date" class="block mt-1 w-full" type="date" name="transaction_date" :value="old('transaction_date', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="project_id" :value="__('Nama Project')" />
                                <select name="project_id" id="project_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Pilih Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="location_id" :value="__('Lokasi Project')" />
                                <select name="location_id" id="location_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }} - {{ $location->cluster_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Field Vendor hanya muncul untuk Penerimaan --}}
                            @if($type == 'penerimaan')
                            <div>
                                <x-input-label for="vendor_id" :value="__('Vendor Tujuan')" />
                                <select name="vendor_id" id="vendor_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Pilih Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>

                        {{-- Bagian Detail Material Dinamis --}}
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-2">Detail Material</h3>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-10 gap-4 mb-4 p-4 border rounded-lg">
                                        {{-- Kolom Material --}}
                                        <div class="md:col-span-4">
                                            <label class="block text-sm font-medium">Material</label>
                                            <select :name="`items[${index}][material_id]`" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                <option value="">Pilih Material</option>
                                                @foreach ($materials as $material)
                                                    <option value="{{ $material->id }}">{{ $material->name }} (Stok: {{ $material->stock }} {{ $material->unit }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Kolom Kuantitas --}}
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium">Kuantitas</label>
                                            <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" min="1" required>
                                        </div>
                                        {{-- Kolom Upload Dokumen --}}
                                        <div class="md:col-span-3">
                                            <label class="block text-sm font-medium">Upload Dokumen (Opsional)</label>
                                            <input type="file" :name="`items[${index}][document]`" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        </div>
                                        {{-- Tombol Hapus --}}
                                        <div class="md:col-span-1 flex items-end">
                                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-700">&times;</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="items.push({ material_id: '', quantity: 1, document: null })" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700">
                                + Tambah Material
                            </button>
                        </div>

                        {{-- Keterangan Tambahan & Tombol Submit --}}
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div>
                                <x-input-label for="notes" :value="__('Keterangan Tambahan')" />
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('user.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Batal</a>
                                <x-primary-button>
                                    {{ __('Ajukan Transaksi') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
