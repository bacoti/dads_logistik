<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Pilih Jenis Transaksi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                        <!-- Card Penerimaan Material -->
                        <a href="{{ route('user.transactions.create', ['type' => 'penerimaan']) }}" class="block p-6 bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md text-white text-center transition-transform transform hover:scale-105">
                            <h4 class="font-bold text-xl">Penerimaan Material</h4>
                            <p class="text-sm">Catat material yang masuk dari vendor.</p>
                        </a>

                        <!-- Card Pengambilan Material -->
                        <a href="{{ route('user.transactions.create', ['type' => 'pengambilan']) }}" class="block p-6 bg-green-500 hover:bg-green-600 rounded-lg shadow-md text-white text-center transition-transform transform hover:scale-105">
                            <h4 class="font-bold text-xl">Pengambilan Material</h4>
                            <p class="text-sm">Catat material yang diambil untuk proyek.</p>
                        </a>

                        <!-- Card Pengembalian Material -->
                        <a href="{{ route('user.transactions.create', ['type' => 'pengembalian']) }}" class="block p-6 bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-md text-white text-center transition-transform transform hover:scale-105">
                            <h4 class="font-bold text-xl">Pengembalian Material</h4>
                            <p class="text-sm">Catat material sisa yang dikembalikan.</p>
                        </a>

                        <!-- Card Peminjaman Material -->
                        <a href="{{ route('user.transactions.create', ['type' => 'peminjaman']) }}" class="block p-6 bg-purple-500 hover:bg-purple-600 rounded-lg shadow-md text-white text-center transition-transform transform hover:scale-105">
                            <h4 class="font-bold text-xl">Peminjaman Material</h4>
                            <p class="text-sm">Catat material yang dipinjam.</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
