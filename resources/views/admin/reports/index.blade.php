<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Panel Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Filter Laporan</h3>
                    <form method="GET" action="{{ route('admin.reports.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Kolom Filter Tanggal -->
                            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md dark:bg-gray-900 border-gray-300 dark:border-gray-700 shadow-sm">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md dark:bg-gray-900 border-gray-300 dark:border-gray-700 shadow-sm">
                                </div>
                            </div>
                            <!-- Kolom Tombol Aksi -->
                            <div class="md:col-span-1 flex items-end space-x-2">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel Hasil Laporan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Hasil Laporan</h3>
                        @if(request()->filled('start_date') && request()->filled('end_date'))
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.reports.export.excel', request()->query()) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </a>
                            <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                PDF
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Jenis</th>
                                    <th class="px-6 py-3">Project</th>
                                    <th class="px-6 py-3">Material</th>
                                    <th class="px-6 py-3">Kuantitas</th>
                                    <th class="px-6 py-3">User</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    @foreach($transaction->items as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                        <td class="px-6 py-4 capitalize">{{ $transaction->type }}</td>
                                        <td class="px-6 py-4">{{ $transaction->project->name }}</td>
                                        <td class="px-6 py-4">{{ $item->material->name }}</td>
                                        <td class="px-6 py-4">{{ $item->quantity }} {{ $item->material->unit }}</td>
                                        <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($transaction->status) }}</td>
                                    </tr>
                                    @endforeach
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">
                                        Tidak ada data untuk ditampilkan. Silakan pilih rentang tanggal dan klik "Terapkan".
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
