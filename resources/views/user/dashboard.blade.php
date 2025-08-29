<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg px-6 py-4 shadow-lg">
            <h2 class="font-bold text-2xl mb-2">
                👋 Selamat datang, {{ Auth::user()->name }}!
            </h2>
            <p class="text-red-100 text-sm">
                Kelola transaksi material dengan mudah dan efisien
            </p>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        hoveredCard: null,
        stats: {
            today: {{ $stats['today'] }},
            thisWeek: {{ $stats['thisWeek'] }},
            thisMonth: {{ $stats['thisMonth'] }},
            pending: {{ $stats['pending'] }}
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Quick Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.today">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Minggu Ini</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.thisWeek">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.thisMonth">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900" x-text="stats.pending">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Action Cards -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Jenis Transaksi Material</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Pilih jenis transaksi yang ingin Anda buat. Setiap transaksi memiliki proses yang telah disesuaikan dengan kebutuhan lapangan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                    <!-- Penerimaan Material Card -->
                    <div @mouseenter="hoveredCard = 'penerimaan'"
                         @mouseleave="hoveredCard = null"
                         class="group relative bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border-2 border-red-200 hover:border-red-300 hover:shadow-xl transition-all duration-300 cursor-pointer">

                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                            </div>

                            <h4 class="text-xl font-bold text-red-900 mb-2">Penerimaan</h4>
                            <p class="text-red-700 text-sm mb-6 leading-relaxed">
                                Input material yang diterima dari vendor atau supplier
                            </p>

                            <a href="{{ route('user.transactions.create', ['type' => 'penerimaan']) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg group-hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Transaksi
                            </a>
                        </div>

                        <!-- Hover Effect Indicator -->
                        <div x-show="hoveredCard === 'penerimaan'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Pengambilan Material Card -->
                    <div @mouseenter="hoveredCard = 'pengambilan'"
                         @mouseleave="hoveredCard = null"
                         class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300 cursor-pointer">

                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H3"></path>
                                </svg>
                            </div>

                            <h4 class="text-xl font-bold text-blue-900 mb-2">Pengambilan</h4>
                            <p class="text-blue-700 text-sm mb-6 leading-relaxed">
                                Input material yang diambil untuk keperluan proyek
                            </p>

                            <a href="{{ route('user.transactions.create', ['type' => 'pengambilan']) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg group-hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Transaksi
                            </a>
                        </div>

                        <div x-show="hoveredCard === 'pengambilan'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute -top-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Pengembalian Material Card -->
                    <div @mouseenter="hoveredCard = 'pengembalian'"
                         @mouseleave="hoveredCard = null"
                         class="group relative bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border-2 border-orange-200 hover:border-orange-300 hover:shadow-xl transition-all duration-300 cursor-pointer">

                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"></path>
                                </svg>
                            </div>

                            <h4 class="text-xl font-bold text-orange-900 mb-2">Pengembalian</h4>
                            <p class="text-orange-700 text-sm mb-6 leading-relaxed">
                                Input material yang dikembalikan ke gudang
                            </p>

                            <a href="{{ route('user.transactions.create', ['type' => 'pengembalian']) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg group-hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Transaksi
                            </a>
                        </div>

                        <div x-show="hoveredCard === 'pengembalian'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Peminjaman Material Card -->
                    <div @mouseenter="hoveredCard = 'peminjaman'"
                         @mouseleave="hoveredCard = null"
                         class="group relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200 hover:border-purple-300 hover:shadow-xl transition-all duration-300 cursor-pointer">

                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>

                            <h4 class="text-xl font-bold text-purple-900 mb-2">Peminjaman</h4>
                            <p class="text-purple-700 text-sm mb-6 leading-relaxed">
                                Input material yang dipinjam sementara
                            </p>

                            <a href="{{ route('user.transactions.create', ['type' => 'peminjaman']) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg group-hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Transaksi
                            </a>
                        </div>

                        <div x-show="hoveredCard === 'peminjaman'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute -top-2 -right-2 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Quick Access -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h4>
                        </div>
                        <a href="{{ route('user.transactions.index') }}"
                           class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center">
                            Lihat Semua
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Activity List -->
                    <div class="space-y-4">
                        @forelse($recentTransactions as $transaction)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-2 h-2 rounded-full mr-3 
                                    {{ $transaction->type === 'penerimaan' ? 'bg-green-500' : 
                                       ($transaction->type === 'pengambilan' ? 'bg-blue-500' : 
                                        ($transaction->type === 'pengembalian' ? 'bg-orange-500' : 'bg-purple-500')) }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ ucfirst($transaction->type) }} - {{ $transaction->project->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $transaction->transaction_date->format('d M Y') }} • 
                                        {{ $transaction->location }}
                                        @if($transaction->vendor)
                                            • {{ $transaction->vendor->name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-gray-400 rounded-full mr-3"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">Belum ada transaksi</p>
                                    <p class="text-sm text-gray-500">Mulai buat transaksi pertama Anda</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Aksi Cepat</h4>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('user.transactions.index') }}"
                           class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700">Lihat Semua Transaksi</span>
                        </a>

                        <a href="{{ route('user.monthly-reports.index') }}"
                           class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-blue-700">Laporan Bulanan</span>
                        </a>

                        <a href="{{ route('user.monthly-reports.create') }}"
                           class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-green-700">Buat Laporan Baru</span>
                        </a>

                        <a href="{{ route('user.loss-reports.index') }}"
                           class="flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-red-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-red-700">Laporan Kehilangan</span>
                        </a>

                        <a href="{{ route('user.loss-reports.create') }}"
                           class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-orange-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-orange-700">Buat Laporan Kehilangan</span>
                        </a>

                        <a href="{{ route('user.mfo-requests.index') }}"
                           class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-purple-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-purple-700">Pengajuan MFO</span>
                        </a>

                        <a href="{{ route('user.mfo-requests.create') }}"
                           class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-indigo-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-indigo-700">Buat Pengajuan MFO</span>
                        </a>

                        <a href="{{ route('user.po-transports.index') }}"
                           class="flex items-center p-3 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-teal-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-teal-700">PO Transport</span>
                        </a>

                        <a href="{{ route('user.po-transports.create') }}"
                           class="flex items-center p-3 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-cyan-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-cyan-700">Buat PO Transport</span>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700">Edit Profil</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help & Support Section -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl border border-red-100 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Butuh Bantuan?</h4>
                            <p class="text-gray-600 text-sm">Pelajari cara menggunakan sistem dengan mudah</p>
                        </div>
                    </div>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                        Panduan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
