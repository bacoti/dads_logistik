<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary Material') }}
        </h2>
    </x-slot>

    <div x-data="{
        showFilters: true,
        activeFilters: {
            project_id: '{{ request('project_id') }}',
            sub_project_id: '{{ request('sub_project_id') }}',
            cluster: '{{ request('cluster') }}',
            hide_no_data: '{{ request('hide_no_data') }}'
        },
        hasActiveFilters() {
            return Object.values(this.activeFilters).some(filter => filter !== '' && filter !== null && filter !== '0');
        }
    }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Header Section -->
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl border border-blue-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Summary Material</h1>
                            <p class="text-blue-100 text-lg">Analisis pemakaian material berdasarkan proyek dan BOQ Actual</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold">{{ count($summaryData) }}</div>
                                <div class="text-blue-100 text-sm">Total Material</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-8 py-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.boq-actuals.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Kelola BOQ Actual
                            </a>
                            <a href="{{ route('admin.boq-actuals.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah BOQ Actual
                            </a>
                        </div>
                        <button @click="showFilters = !showFilters" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span x-text="showFilters ? 'Sembunyikan Filter' : 'Tampilkan Filter'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div x-show="showFilters" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Filter Summary</h3>
                </div>
                <form method="GET" action="{{ route('admin.boq-actuals.summary') }}" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Project Filter -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Proyek</label>
                            <select name="project_id" id="project_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Proyek</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sub Project Filter -->
                        <div>
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">Sub Proyek</label>
                            <select name="sub_project_id" id="sub_project_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Sub Proyek</option>
                                @foreach($subProjects as $subProject)
                                    <option value="{{ $subProject->id }}" {{ request('sub_project_id') == $subProject->id ? 'selected' : '' }}>
                                        {{ $subProject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cluster Filter -->
                        <div>
                            <label for="cluster" class="block text-sm font-medium text-gray-700 mb-2">Cluster</label>
                            <select name="cluster" id="cluster" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Cluster</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster }}" {{ request('cluster') == $cluster ? 'selected' : '' }}>
                                        {{ $cluster }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hide No Data -->
                        <div>
                            <label for="hide_no_data" class="block text-sm font-medium text-gray-700 mb-2">Tampilan</label>
                            <select name="hide_no_data" id="hide_no_data" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="0" {{ request('hide_no_data') == '0' ? 'selected' : '' }}>Tampilkan Semua</option>
                                <option value="1" {{ request('hide_no_data') == '1' ? 'selected' : '' }}>Sembunyikan Tanpa Data</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                        
                        @if(request()->hasAny(['project_id', 'sub_project_id', 'cluster', 'hide_no_data']))
                            <a href="{{ route('admin.boq-summary') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Summary Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                @if(count($summaryData) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Material</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">DO<br><span class="text-xs font-normal">(Material Diterima)</span></th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Material Terpakai<br><span class="text-xs font-normal">(BOQ Actual)</span></th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa<br><span class="text-xs font-normal">(DO - BOQ Actual)</span></th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($summaryData as $index => $data)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $data['material_name'] }}</div>
                                            <div class="text-sm text-gray-500">Unit: {{ $data['unit'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $data['category_name'] ?? 'No Category' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $data['project_name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $data['sub_project_name'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium text-blue-600">
                                                {{ number_format($data['received_quantity'], 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $data['unit'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium text-green-600">
                                                {{ number_format($data['actual_usage'], 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $data['unit'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-medium {{ $data['remaining_stock'] >= 0 ? 'text-orange-600' : 'text-red-600' }}">
                                                {{ number_format($data['remaining_stock'], 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $data['unit'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($data['remaining_stock'] > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Sisa {{ number_format($data['remaining_stock'], 2) }}
                                                </span>
                                            @elseif($data['remaining_stock'] == 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Terpakai Habis
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Kelebihan Pakai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <div class="text-lg font-bold text-blue-600">{{ count($summaryData) }}</div>
                                <div class="text-sm text-gray-500">Total Material</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-green-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['actual_usage'] > 0; })) }}
                                </div>
                                <div class="text-sm text-gray-500">Sudah Terpakai</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-orange-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['remaining_stock'] > 0; })) }}
                                </div>
                                <div class="text-sm text-gray-500">Ada Sisa</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-red-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['remaining_stock'] < 0; })) }}
                                </div>
                                <div class="text-sm text-gray-500">Kelebihan Pakai</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data summary</h3>
                        <p class="mt-1 text-sm text-gray-500">Data akan muncul setelah ada transaksi penerimaan dan BOQ Actual yang diinput.</p>
                        <div class="mt-6 flex justify-center space-x-4">
                            <a href="{{ route('admin.boq-actuals.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah BOQ Actual
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show any flash messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
    @endpush
</x-admin-layout>