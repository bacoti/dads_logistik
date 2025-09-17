<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BOQ Actual') }}
        </h2>
    </x-slot>

    <div x-data="{
        showFilters: true,
        stats: {
            total: {{ $boqActuals->count() }}
        },
        activeFilters: {
            search: '{{ request('search') }}',
            project_id: '{{ request('project_id') }}',
            sub_project_id: '{{ request('sub_project_id') }}',
            cluster: '{{ request('cluster') }}',
            date_from: '{{ request('date_from') }}',
            date_to: '{{ request('date_to') }}'
        },
        hasActiveFilters() {
            return Object.values(this.activeFilters).some(filter => filter !== '' && filter !== null);
        }
    }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Header Section -->
            <div class="bg-gradient-to-br from-white to-green-50 rounded-2xl shadow-xl border border-green-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-700 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">BOQ Actual</h1>
                            <p class="text-green-100 text-lg">Manajemen data pemakaian material aktual per proyek</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold" x-text="stats.total"></div>
                                <div class="text-green-100 text-sm">Total Data</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-8 py-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.boq-actuals.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah BOQ Actual
                            </a>
                            <a href="{{ route('admin.boq-actuals.summary') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Lihat Summary
                            </a>
                        </div>
                        <button @click="showFilters = !showFilters" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
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
                    <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
                </div>
                <form method="GET" action="{{ route('admin.boq-actuals.index') }}" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Material/DN</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan nama material atau nomor DN..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- Project Filter -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Proyek</label>
                            <select name="project_id" id="project_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
                            <select name="sub_project_id" id="sub_project_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
                            <select name="cluster" id="cluster" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Semua Cluster</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster }}" {{ request('cluster') == $cluster ? 'selected' : '' }}>
                                        {{ $cluster }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" 
                                   name="date_from" 
                                   id="date_from"
                                   value="{{ request('date_from') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" 
                                   name="date_to" 
                                   id="date_to"
                                   value="{{ request('date_to') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-4">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                        
                        @if(request()->hasAny(['search', 'project_id', 'sub_project_id', 'cluster', 'date_from', 'date_to']))
                            <a href="{{ route('admin.boq-actuals.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Data Cards -->
            <div class="space-y-6">
                @if($boqActuals->count() > 0)
                    @foreach($hierarchyData as $projectKey => $projectData)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-xl"
                             x-data="{ expanded: false }">
                            <!-- Project Header -->
                            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 cursor-pointer"
                                 @click="expanded = !expanded">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-building text-blue-600 text-xl"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $projectData['project']->name }}</h3>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                                <span class="flex items-center">
                                                    <i class="fas fa-map-marker-alt w-4 h-4 mr-1"></i>
                                                    {{ count($projectData['clusters']) }} Lokasi
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-file-alt w-4 h-4 mr-1"></i>
                                                    {{ $projectData['totalDNs'] }} DN
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-cubes w-4 h-4 mr-1"></i>
                                                    {{ $projectData['totalMaterials'] }} Materials
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">Click to expand</div>
                                            <div class="text-xs text-gray-500">View locations</div>
                                        </div>
                                        <div class="ml-4">
                                            <i class="fas fa-chevron-down w-4 h-4 text-gray-400 transition-transform duration-200"
                                               :class="{ 'transform rotate-180': expanded }"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clusters/Locations List (Level 2) -->
                            <div x-show="expanded" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100 max-h-screen"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 max-h-screen"
                                 x-transition:leave-end="opacity-0 max-h-0"
                                 class="overflow-hidden">
                                
                                @foreach($projectData['clusters'] as $clusterKey => $clusterData)
                                    <div class="border-b border-gray-100 last:border-b-0"
                                         x-data="{ clusterExpanded: false }">
                                        <!-- Cluster Header -->
                                        <div class="px-6 py-3 bg-gradient-to-r from-green-50 to-emerald-50 cursor-pointer"
                                             @click="clusterExpanded = !clusterExpanded">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-map-marker-alt text-green-600"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-900">{{ $clusterData['cluster_name'] }}</span>
                                                        <div class="text-sm text-gray-500">
                                                            {{ count($clusterData['dns']) }} DN • {{ $clusterData['totalMaterials'] }} Materials
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="text-right">
                                                        <div class="text-sm font-medium text-gray-700">Click to expand</div>
                                                        <div class="text-xs text-gray-500">View DN numbers</div>
                                                    </div>
                                                    <i class="fas fa-chevron-down w-4 h-4 text-gray-400 transition-transform duration-200"
                                                       :class="{ 'transform rotate-180': clusterExpanded }"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DN Numbers List (Level 3) -->
                                        <div x-show="clusterExpanded" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 max-h-0"
                                             x-transition:enter-end="opacity-100 max-h-screen"
                                             class="overflow-hidden bg-gray-50">
                                            
                                            @foreach($clusterData['dns'] as $dnKey => $dnData)
                                                <div class="border-b border-gray-200 last:border-b-0"
                                                     x-data="{ dnExpanded: false }">
                                                    <!-- DN Header -->
                                                    <div class="px-8 py-3 bg-gradient-to-r from-yellow-50 to-amber-50 cursor-pointer"
                                                         @click="dnExpanded = !dnExpanded">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="w-6 h-6 bg-yellow-100 rounded flex items-center justify-center">
                                                                    <i class="fas fa-file-alt text-yellow-600 text-sm"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="font-medium text-gray-900">{{ $dnData['dn_number'] }}</span>
                                                                    <div class="text-sm text-gray-500">
                                                                        {{ count($dnData['materials']) }} Materials • 
                                                                        {{ $dnData['usage_date'] ? $dnData['usage_date']->format('d/m/Y') : 'No Date' }} • 
                                                                        {{ $dnData['user']->name ?? 'Unknown User' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <div class="text-right">
                                                                    <div class="text-sm font-medium text-gray-700">Click to expand</div>
                                                                    <div class="text-xs text-gray-500">View materials</div>
                                                                </div>
                                                                <i class="fas fa-chevron-down w-4 h-4 text-gray-400 transition-transform duration-200"
                                                                   :class="{ 'transform rotate-180': dnExpanded }"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Materials List (Level 4) -->
                                                    <div x-show="dnExpanded" 
                                                         x-transition:enter="transition ease-out duration-300"
                                                         x-transition:enter-start="opacity-0 max-h-0"
                                                         x-transition:enter-end="opacity-100 max-h-screen"
                                                         class="overflow-hidden bg-white">
                                                        
                                                        <div class="px-10 py-4">
                                                            <div class="space-y-3">
                                                                @foreach($dnData['materials'] as $material)
                                                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150 border border-gray-200">
                                                                        <div class="flex items-center space-x-4">
                                                                            <div class="flex-shrink-0">
                                                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                                                    <i class="fas fa-box text-blue-600"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center space-x-3">
                                                                                    <h4 class="font-medium text-gray-900">{{ $material->material->name }}</h4>
                                                                                    <span class="text-sm text-gray-500">• {{ $material->material->category->name ?? 'No Category' }}</span>
                                                                                </div>
                                                                                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                                                                                    <span class="flex items-center">
                                                                                        <i class="fas fa-weight-hanging w-3 h-3 mr-1"></i>
                                                                                        {{ number_format($material->actual_quantity, 2) }} {{ $material->material->unit }}
                                                                                    </span>
                                                                                    <span class="flex items-center">
                                                                                        <i class="fas fa-calendar w-3 h-3 mr-1"></i>
                                                                                        {{ $material->usage_date->format('d/m/Y') }}
                                                                                    </span>
                                                                                    <span class="flex items-center">
                                                                                        <i class="fas fa-user w-3 h-3 mr-1"></i>
                                                                                        {{ $material->user->name }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex items-center space-x-1">
                                                                            <a href="{{ route('admin.boq-actuals.show', $material) }}" 
                                                                               class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                                                               title="Lihat Detail">
                                                                                <i class="fas fa-eye w-4 h-4"></i>
                                                                            </a>
                                                                            <a href="{{ route('admin.boq-actuals.edit', $material) }}" 
                                                                               class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors duration-150"
                                                                               title="Edit">
                                                                                <i class="fas fa-edit w-4 h-4"></i>
                                                                            </a>
                                                                            <form action="{{ route('admin.boq-actuals.destroy', $material) }}" 
                                                                                  method="POST" 
                                                                                  class="inline"
                                                                                  onsubmit="return confirm('Yakin ingin menghapus data BOQ Actual ini?')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" 
                                                                                        class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                                                        title="Hapus">
                                                                                    <i class="fas fa-trash w-4 h-4"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="text-center py-16">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Belum ada data BOQ Actual</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                                Mulai dengan menambahkan data pemakaian material aktual untuk tracking yang lebih baik.
                            </p>
                            <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                                <a href="{{ route('admin.boq-actuals.create') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                    <i class="fas fa-plus w-5 h-5 mr-2"></i>
                                    Tambah BOQ Actual
                                </a>
                                <a href="{{ route('admin.boq-actuals.summary') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                    <i class="fas fa-chart-pie w-5 h-5 mr-2"></i>
                                    Lihat Summary
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Success/Error Messages
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