<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Transaksi #{{ $transaction->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Informasi Umum -->
                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold mb-2">Informasi Umum</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div><strong>Tanggal Transaksi:</strong> {{ $transaction->transaction_date->format('d F Y') }}</div>
                            <div><strong>Jenis Transaksi:</strong> <span class="capitalize">{{ $transaction->type }}</span></div>
                            <div><strong>Project:</strong> {{ $transaction->project->name }}</div>
                            <div><strong>Lokasi:</strong> {{ $transaction->location->name }}</div>
                            @if($transaction->vendor)
                            <div><strong>Vendor:</strong> {{ $transaction->vendor->name }}</div>
                            @endif
                            <div>
                                <strong>Status:</strong>
                                @php
                                    $statusClass = match($transaction->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                            @if($transaction->status != 'pending')
                            <div><strong>Diproses oleh:</strong> {{ $transaction->approver->name ?? '-' }}</div>
                            <div><strong>Tanggal Diproses:</strong> {{ $transaction->approved_at ? $transaction->approved_at->format('d F Y H:i') : '-' }}</div>
                            @endif
                            @if($transaction->notes)
                            <div class="col-span-2"><strong>Keterangan:</strong> {{ $transaction->notes }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Item Material -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Item Material</h3>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Material</th>
                                        <th scope="col" class="px-6 py-3">Kuantitas</th>
                                        <th scope="col" class="px-6 py-3">Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->items as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $item->material->name }}</td>
                                        <td class="px-6 py-4">{{ $item->quantity }} {{ $item->material->unit }}</td>
                                        <td class="px-6 py-4">
                                            @if($item->document_path)
                                            <a href="{{ Storage::url($item->document_path) }}" target="_blank" class="text-blue-500 hover:underline">Lihat Dokumen</a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <a href="{{ route('user.transactions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
