<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Vendor: ' . $vendor->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header dengan actions -->
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $vendor->name }}</h3>
                            <p class="text-gray-600">Informasi lengkap vendor</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.vendors.edit', $vendor) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Vendor
                            </a>
                        </div>
                    </div>

                    <!-- Informasi Vendor -->
                    <div class="mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Statistik Transaksi</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Transaksi</dt>
                                    <dd class="text-2xl font-bold text-blue-600">{{ $vendor->transactions->count() }}</dd>
                                </div>

                                @php
                                    $transactionTypes = $vendor->transactions->groupBy('type');
                                @endphp

                                @foreach(['penerimaan', 'pengambilan', 'pengembalian', 'peminjaman'] as $type)
                                    @if($transactionTypes->has($type))
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ ucfirst($type) }}</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $transactionTypes[$type]->count() }} transaksi</dd>
                                    </div>
                                    @endif
                                @endforeach

                                @if($vendor->transactions->count() > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Transaksi Terakhir</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $vendor->transactions->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Riwayat Transaksi -->
                    @if($vendor->transactions->count() > 0)
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Transaksi</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Proyek
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User Input
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($vendor->transactions->sortByDesc('created_at')->take(10) as $transaction)
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->project->code }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->details->count() }} item
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('admin.transactions.show', $transaction) }}"
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($vendor->transactions->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.transactions.index', ['vendor' => $vendor->id]) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Lihat semua transaksi dengan vendor ini →
                            </a>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-900">Belum ada transaksi</p>
                        <p class="text-gray-500">Vendor ini belum memiliki riwayat transaksi dalam sistem.</p>
                    </div>
                    @endif

                    <!-- Info Tambahan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between items-center">
                                <span>Dibuat pada: {{ $vendor->created_at->format('d/m/Y H:i') }}</span>
                                <span>Terakhir diupdate: {{ $vendor->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <a href="{{ route('admin.vendors.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                            ← Kembali ke Daftar
                        </a>

                        <div class="flex space-x-3">
                            @if($vendor->transactions->count() == 0)
                            <form method="POST"
                                  action="{{ route('admin.vendors.destroy', $vendor) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Hapus Vendor
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
