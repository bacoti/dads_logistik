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
                            @if(count($summaryData) > 0)
                                <a href="{{ route('admin.boq-actuals.export-summary', request()->all()) }}"
                                   class="inline-flex items-center px-6 py-3 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export Excel Matrix
                                </a>
                            @endif
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
                            <a href="{{ route('admin.boq-actuals.summary') }}"
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

            <!-- Summary Hierarchical Card View -->
            <div x-data="{
                expandedProjects: {},
                expandedSubProjects: {},
                expandedClusters: {},
                expandedDNs: {},
                toggleProject(projectId) {
                    this.expandedProjects[projectId] = !this.expandedProjects[projectId];
                },
                toggleSubProject(subProjectId) {
                    this.expandedSubProjects[subProjectId] = !this.expandedSubProjects[subProjectId];
                },
                toggleCluster(clusterId) {
                    this.expandedClusters[clusterId] = !this.expandedClusters[clusterId];
                },
                toggleDN(dnId) {
                    this.expandedDNs[dnId] = !this.expandedDNs[dnId];
                }
            }" class="space-y-6">
                @if(count($summaryData) > 0)
                    @php
                        // Group data by project -> sub_project -> cluster -> dn_number -> materials
                        $groupedData = collect($summaryData)->groupBy('project_name')->map(function($projectItems) {
                            return $projectItems->groupBy('sub_project_name')->map(function($subProjectItems) {
                                return $subProjectItems->groupBy('cluster')->map(function($clusterItems) {
                                    return $clusterItems->groupBy('dn_number')->map(function($dnItems) {
                                        return $dnItems;
                                    });
                                });
                            });
                        });
                    @endphp

                    @foreach($groupedData as $projectName => $subProjects)
                        <!-- Project Level Card -->
                        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 cursor-pointer"
                                 @click="toggleProject('{{ Str::slug($projectName) }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-white transition-transform duration-200"
                                             :class="expandedProjects['{{ Str::slug($projectName) }}'] ? 'rotate-90' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-white">📂 {{ $projectName }}</h3>
                                    </div>
                                    <div class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                        <span class="text-white text-sm font-medium">
                                            {{ $subProjects->flatten(3)->count() }} Materials
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub Projects Container -->
                            <div x-show="expandedProjects['{{ Str::slug($projectName) }}']"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100 max-h-screen"
                                 class="overflow-hidden">

                                @foreach($subProjects as $subProjectName => $clusters)
                                    <!-- Sub Project Level -->
                                    <div class="border-l-4 border-blue-300 ml-6">
                                        <div class="bg-green-50 border border-green-200 mx-4 my-2 rounded-lg">
                                            <div class="px-4 py-3 cursor-pointer border-green-300 bg-green-100"
                                                 @click="toggleSubProject('{{ Str::slug($projectName.$subProjectName) }}')">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-green-600 transition-transform duration-200"
                                                             :class="expandedSubProjects['{{ Str::slug($projectName.$subProjectName) }}'] ? 'rotate-90' : ''"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <h4 class="text-md font-semibold text-green-800">📁 {{ $subProjectName }}</h4>
                                                    </div>
                                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        {{ $clusters->flatten(2)->count() }} Materials
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Clusters Container -->
                                            <div x-show="expandedSubProjects['{{ Str::slug($projectName.$subProjectName) }}']"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 max-h-0"
                                                 x-transition:enter-end="opacity-100 max-h-screen"
                                                 class="overflow-hidden p-4 space-y-3">

                                                @foreach($clusters as $clusterName => $dns)
                                                    <!-- Cluster Level -->
                                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg">
                                                        <div class="px-4 py-3 cursor-pointer bg-yellow-100 rounded-t-lg"
                                                             @click="toggleCluster('{{ Str::slug($projectName.$subProjectName.$clusterName) }}')">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center space-x-3">
                                                                    <svg class="w-4 h-4 text-yellow-600 transition-transform duration-200"
                                                                         :class="expandedClusters['{{ Str::slug($projectName.$subProjectName.$clusterName) }}'] ? 'rotate-90' : ''"
                                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                    </svg>
                                                                    <h5 class="text-sm font-semibold text-yellow-800">🏗️ Cluster: {{ $clusterName ?: 'No Cluster' }}</h5>
                                                                </div>
                                                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                                                    {{ $dns->flatten(1)->count() }} Materials
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- DN Numbers Container -->
                                                        <div x-show="expandedClusters['{{ Str::slug($projectName.$subProjectName.$clusterName) }}']"
                                                             x-transition:enter="transition ease-out duration-300"
                                                             x-transition:enter-start="opacity-0 max-h-0"
                                                             x-transition:enter-end="opacity-100 max-h-screen"
                                                             class="overflow-hidden p-3 space-y-2">

                                                            @foreach($dns as $dnNumber => $materials)
                                                                <!-- DN Level -->
                                                                <div class="bg-gray-50 border border-gray-200 rounded-lg">
                                                                    <div class="px-3 py-2 cursor-pointer bg-gray-100 rounded-t-lg"
                                                                         @click="toggleDN('{{ Str::slug($projectName.$subProjectName.$clusterName.$dnNumber) }}')">
                                                                        <div class="flex items-center justify-between">
                                                                            <div class="flex items-center space-x-2">
                                                                                <svg class="w-4 h-4 text-gray-600 transition-transform duration-200"
                                                                                     :class="expandedDNs['{{ Str::slug($projectName.$subProjectName.$clusterName.$dnNumber) }}'] ? 'rotate-90' : ''"
                                                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                                </svg>
                                                                                <h6 class="text-sm font-medium text-gray-800">📋 DN: {{ $dnNumber ?: 'No DN' }}</h6>
                                                                            </div>
                                                                            <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                                                                                {{ count($materials) }} Materials
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Material Cards -->
                                                                    <div x-show="expandedDNs['{{ Str::slug($projectName.$subProjectName.$clusterName.$dnNumber) }}']"
                                                                         x-transition:enter="transition ease-out duration-300"
                                                                         x-transition:enter-start="opacity-0 max-h-0"
                                                                         x-transition:enter-end="opacity-100 max-h-screen"
                                                                         class="overflow-hidden p-3 space-y-2">

                                                                        @foreach($materials as $material)
                                                                            <!-- Material Card -->
                                                                            <div class="bg-white border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                                                                <div class="p-4">
                                                                                    <!-- Material Header -->
                                                                                    <div class="flex items-start justify-between mb-3">
                                                                                        <div>
                                                                                            <h6 class="font-semibold text-gray-900 text-sm">{{ $material['material_name'] }}</h6>
                                                                                            <p class="text-xs text-gray-500">{{ $material['category_name'] ?: 'No Category' }} • Unit: {{ $material['unit'] }}</p>
                                                                                        </div>
                                                                                        <!-- Status Badge -->
                                                                                        @if($material['received_quantity'] == $material['actual_usage'])
                                                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                                                ✅ Aktual
                                                                                            </span>
                                                                                        @elseif($material['remaining_stock'] > 0)
                                                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                                                📦 Sisa Stok
                                                                                            </span>
                                                                                        @else
                                                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                                                ⚠️ Kelebihan Pakai
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>

                                                                                    <!-- Material Metrics -->
                                                                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-center">
                                                                                        <!-- Diterima -->
                                                                                        <div class="bg-blue-50 p-2 rounded-lg">
                                                                                            <div class="text-lg font-bold text-blue-600">{{ number_format($material['received_quantity'], 2) }}</div>
                                                                                            <div class="text-xs text-blue-800 font-medium">Diterima</div>
                                                                                        </div>

                                                                                        <!-- Terpakai -->
                                                                                        <div class="bg-green-50 p-2 rounded-lg">
                                                                                            <div class="text-lg font-bold text-green-600">{{ number_format($material['actual_usage'], 2) }}</div>
                                                                                            <div class="text-xs text-green-800 font-medium">Terpakai</div>
                                                                                        </div>

                                                                                        <!-- BOQ Actual -->
                                                                                        <div class="bg-purple-50 p-2 rounded-lg">
                                                                                            <div class="text-lg font-bold text-purple-600">{{ number_format($material['boq_actual_quantity'] ?? 0, 2) }}</div>
                                                                                            <div class="text-xs text-purple-800 font-medium">BOQ Actual</div>
                                                                                        </div>

                                                                                        <!-- Sisa -->
                                                                                        <div class="bg-orange-50 p-2 rounded-lg">
                                                                                            <div class="text-lg font-bold {{ $material['remaining_stock'] >= 0 ? 'text-orange-600' : 'text-red-600' }}">
                                                                                                {{ number_format($material['remaining_stock'], 2) }}
                                                                                            </div>
                                                                                            <div class="text-xs text-orange-800 font-medium">Sisa</div>
                                                                                        </div>

                                                                                        <!-- Status Detail -->
                                                                                        <div class="bg-gray-50 p-2 rounded-lg">
                                                                                            @if($material['received_quantity'] == $material['actual_usage'])
                                                                                                <div class="text-lg font-bold text-green-600">100%</div>
                                                                                                <div class="text-xs text-green-800 font-medium">Aktual</div>
                                                                                            @elseif($material['remaining_stock'] > 0)
                                                                                                <div class="text-lg font-bold text-orange-600">
                                                                                                    {{ $material['received_quantity'] > 0 ? round(($material['actual_usage'] / $material['received_quantity']) * 100, 1) : 0 }}%
                                                                                                </div>
                                                                                                <div class="text-xs text-orange-800 font-medium">Terpakai</div>
                                                                                            @else
                                                                                                <div class="text-lg font-bold text-red-600">
                                                                                                    {{ $material['received_quantity'] > 0 ? round(($material['actual_usage'] / $material['received_quantity']) * 100, 1) : 0 }}%
                                                                                                </div>
                                                                                                <div class="text-xs text-red-800 font-medium">Lebih</div>
                                                                                            @endif
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Summary Statistics -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">📊 Ringkasan Statistik</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                                <div class="text-2xl font-bold text-blue-600">{{ count($summaryData) }}</div>
                                <div class="text-sm text-gray-600 font-medium">Total Material</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['received_quantity'] == $item['actual_usage']; })) }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">Aktual (100%)</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-orange-100">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['remaining_stock'] > 0; })) }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">Ada Sisa</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ count(array_filter($summaryData, function($item) { return $item['remaining_stock'] < 0; })) }}
                                </div>
                                <div class="text-sm text-gray-600 font-medium">Kelebihan Pakai</div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Data State -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="text-center py-16">
                            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="mt-6 text-lg font-medium text-gray-900">📋 Belum ada data summary</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                                Data summary akan muncul setelah ada transaksi penerimaan material dan BOQ Actual yang telah diinput.
                            </p>
                            <div class="mt-8 flex justify-center space-x-4">
                                <a href="{{ route('admin.boq-actuals.create') }}"
                                   class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Tambah BOQ Actual
                                </a>
                                <a href="{{ route('admin.boq-actuals.index') }}"
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Kelola BOQ Actual
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
