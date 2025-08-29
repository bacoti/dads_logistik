<x-app-layout>
    <x-page-header
        title="Laporan Bulanan"
        subtitle="Kelola dan pantau laporan bulanan Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Bulanan']
        ]"
        :stats="'Total: ' . $reports->total() . ' laporan'"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button
                href="{{ route('user.monthly-reports.create') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
                class="bg-red-500 hover:bg-red-600 text-white">
                Buat Laporan Baru
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="min-h-screen bg-gray-50 py-8" x-data="reportFilters()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <x-section-header
                        title="Filter & Pencarian"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z\'></path></svg>'" />
                    <button @click="resetFilters()" class="text-red-600 hover:text-red-800 text-sm font-medium">Reset Filter</button>
                </div>

                <form method="GET" action="{{ route('user.monthly-reports.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <x-form-input
                                label="Pencarian"
                                name="search"
                                placeholder="Cari berdasarkan proyek, lokasi, periode..."
                                :value="request('search')"
                                :icon="'<svg fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z\'></path></svg>'" />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <x-form-input
                                type="select"
                                label="Status"
                                name="status"
                                placeholder="Semua Status"
                                :options="[
                                    'pending' => 'Pending',
                                    'reviewed' => 'Reviewed',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected'
                                ]"
                                :value="request('status')" />
                        </div>

                        <!-- Date Range -->
                        <div>
                            <x-form-input
                                type="select"
                                label="Periode"
                                name="date_range"
                                placeholder="Semua Waktu"
                                :options="[
                                    'this_month' => 'Bulan Ini',
                                    'last_month' => 'Bulan Lalu',
                                    'this_year' => 'Tahun Ini'
                                ]"
                                :value="request('date_range')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} laporan
                        </div>
                        <x-button type="submit" :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z\'></path></svg>'">
                            Terapkan Filter
                        </x-button>
                    </div>
                </form>
            </div>

            <!-- Reports Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($reports->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Proyek</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                                {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->formatted_period }}</div>
                                            <div class="text-xs text-gray-500">{{ $report->report_period }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->project->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $report->subProject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $report->project_location }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $report->status_badge }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $report->report_date->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('user.monthly-reports.show', $report) }}"
                                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition duration-200"
                                                   title="Lihat Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>

                                                @if($report->status === 'pending')
                                                    <a href="{{ route('user.monthly-reports.edit', $report) }}"
                                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition duration-200"
                                                       title="Edit Laporan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('user.monthly-reports.destroy', $report) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition duration-200"
                                                                title="Hapus Laporan"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden space-y-4 p-4">
                        @foreach($reports as $report)
                            <div class="bg-gradient-to-r from-white to-gray-50 rounded-2xl p-6 border border-gray-200 shadow-sm">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-red-100 rounded-full p-2">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $report->formatted_period }}</h4>
                                            <p class="text-sm text-gray-500">{{ $report->report_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $report->status_badge }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $report->project->name }}</span>
                                            <div class="text-gray-500">{{ $report->subProject->name }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        <span class="text-gray-900">{{ $report->project_location }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <span class="text-xs text-gray-500">#{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</span>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('user.monthly-reports.show', $report) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition duration-200">
                                            Lihat
                                        </a>
                                        @if($report->status === 'pending')
                                            <a href="{{ route('user.monthly-reports.edit', $report) }}"
                                               class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition duration-200">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($reports->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} hasil
                                </div>
                                <div class="flex space-x-1">
                                    {{ $reports->appends(request()->query())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Laporan</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Anda belum memiliki laporan bulanan. Mulai dengan membuat laporan pertama Anda.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <x-button
                                href="{{ route('user.monthly-reports.create') }}"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'">
                                Buat Laporan Pertama
                            </x-button>
                            <x-button
                                variant="secondary"
                                href="{{ route('user.dashboard') }}"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'">
                                Kembali ke Dashboard
                            </x-button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for Filters -->
    <script>
        function reportFilters() {
            return {
                resetFilters() {
                    window.location.href = "{{ route('user.monthly-reports.index') }}";
                }
            }
        }
    </script>
</x-app-layout>
