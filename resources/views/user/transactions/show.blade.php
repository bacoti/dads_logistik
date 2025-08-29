<x-app-layout>
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
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Informasi Umum -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Transaksi</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                                    <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</dd>
                                </div>
                                @if($transaction->vendor)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->vendor->name }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User Input</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->user->name }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Lokasi</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Proyek Utama</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->project->name }} ({{ $transaction->project->code }})</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sub Proyek</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->subProject->name }}</dd>
                                </div>
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
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->material->category->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->material->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($detail->quantity) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detail->material->unit }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($transaction->notes)
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Keterangan</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $transaction->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Bukti Upload -->
                    @if($transaction->proof_path)
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Bukti</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @php
                                $fileExtension = pathinfo($transaction->proof_path, PATHINFO_EXTENSION);
                                $imageExtensions = ['jpg', 'jpeg', 'png'];
                            @endphp

                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                <img src="{{ Storage::url($transaction->proof_path) }}"
                                     alt="Bukti Transaksi"
                                     class="max-w-md rounded-lg shadow-md">
                            @else
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <a href="{{ Storage::url($transaction->proof_path) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat File PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('user.transactions.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            ← Kembali ke Daftar
                        </a>
                        <div class="space-x-2">
                            <a href="{{ route('user.transactions.edit', $transaction) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Edit Transaksi
                            </a>
                            <form action="{{ route('user.transactions.destroy', $transaction) }}"
                                  method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    Hapus Transaksi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
