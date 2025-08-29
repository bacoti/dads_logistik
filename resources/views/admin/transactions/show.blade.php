<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi #' . $transaction->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Transaksi {{ ucfirst($transaction->type) }} Material
                            </h3>
                            <p class="text-gray-600">
                                Dibuat pada {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @switch($transaction->type)
                                    @case('penerimaan') bg-green-100 text-green-800 @break
                                    @case('pengambilan') bg-blue-100 text-blue-800 @break
                                    @case('pengembalian') bg-orange-100 text-orange-800 @break
                                    @case('peminjaman') bg-purple-100 text-purple-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch
                            ">
                                {{ ucfirst($transaction->type) }}
                            </span>
                            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-full">
                                ID: #{{ $transaction->id }}
                            </span>
                        </div>
                    </div>

                    <!-- Informasi Umum -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Transaksi</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                                    <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User Input</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->user->name }}</dd>
                                </div>
                                @if($transaction->vendor)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $transaction->vendor->name }}
                                        @if($transaction->vendor->contact_person)
                                            <div class="text-xs text-gray-500">{{ $transaction->vendor->contact_person }}</div>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Proyek</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Proyek Utama</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->project->name }} ({{ $transaction->project->code }})</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sub Proyek</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->subProject->name }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Lokasi</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->location }}</dd>
                                </div>
                                @if($transaction->cluster)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cluster</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->cluster }}</dd>
                                </div>
                                @endif
                                @if($transaction->site_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Site ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->site_id }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Detail Material -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Detail Material</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Satuan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transaction->details as $detail)
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $detail->material->category->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $detail->material->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="font-semibold">{{ number_format($detail->quantity) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->material->unit }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 text-center text-sm font-medium text-gray-900">
                                            Total: {{ $transaction->details->count() }} item material
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($transaction->notes)
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Keterangan</h4>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $transaction->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Bukti Upload -->
                    @if($transaction->proof_path)
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Bukti Transaksi</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @php
                                $fileExtension = pathinfo($transaction->proof_path, PATHINFO_EXTENSION);
                                $imageExtensions = ['jpg', 'jpeg', 'png'];
                            @endphp

                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                <div class="flex items-start space-x-4">
                                    <img src="{{ Storage::url($transaction->proof_path) }}"
                                         alt="Bukti Transaksi"
                                         class="max-w-md rounded-lg shadow-md">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600 mb-2">File gambar berhasil diupload</p>
                                        <a href="{{ Storage::url($transaction->proof_path) }}"
                                           target="_blank"
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Buka di Tab Baru
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">File PDF</p>
                                        <a href="{{ Storage::url($transaction->proof_path) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            Buka File PDF
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between border-t pt-6">
                        <a href="{{ route('admin.transactions.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            ← Kembali ke Tabel Data
                        </a>

                        <div class="text-sm text-gray-500">
                            Terakhir diupdate: {{ $transaction->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
