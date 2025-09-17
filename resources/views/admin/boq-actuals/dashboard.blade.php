<x-admin-layout>
    <div class="min-h-screen bg-gray-50" x-data="boqDashboard()">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">BOQ Actual Dashboard</h1>
                        <p class="text-gray-600 mt-1">Monitor and manage material usage across projects</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.boq-actuals.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Quick Input
                        </a>
                        <a href="{{ route('admin.boq-actuals.summary') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>Summary Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex h-screen">
            <!-- Sidebar - Project Tree -->
            <div class="w-1/3 bg-white border-r border-gray-200 overflow-y-auto">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Projects Overview</h2>
                    
                    <!-- Project Cards -->
                    <div class="space-y-4">
                        @foreach($projects as $project)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer"
                             @click="selectProject({{ json_encode($project) }})"
                             :class="selectedProject && selectedProject.id === {{ $project['id'] }} ? 'border-blue-500 bg-blue-50' : ''">
                            
                            <!-- Project Header -->
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-medium text-gray-900 text-sm">{{ $project['name'] }}</h3>
                                <span class="text-xs text-gray-500">{{ $project['cluster_count'] }} clusters</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $project['progress_percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $project['progress_percentage'] }}%"></div>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="text-center p-2 bg-gray-50 rounded">
                                    <div class="font-semibold text-gray-900">{{ $project['total_materials'] }}</div>
                                    <div class="text-gray-600">Total Materials</div>
                                </div>
                                <div class="text-center p-2 bg-gray-50 rounded">
                                    <div class="font-semibold text-gray-900">{{ $project['materials_with_boq'] }}</div>
                                    <div class="text-gray-600">With BOQ</div>
                                </div>
                            </div>

                            <!-- Sub Projects Preview -->
                            @if(count($project['sub_projects']) > 0)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="text-xs text-gray-600 mb-2">Sub Projects:</div>
                                <div class="space-y-1">
                                    @foreach($project['sub_projects'] as $subProject)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-700">{{ $subProject['name'] }}</span>
                                        <span class="text-gray-500">{{ $subProject['progress'] }}%</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex">
                <!-- Tree View -->
                <div class="w-1/2 bg-white border-r border-gray-200 overflow-y-auto">
                    <div class="p-4">
                        <!-- Project Header -->
                        <div x-show="selectedProject" class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900" x-text="selectedProject ? selectedProject.name : ''"></h3>
                            <p class="text-sm text-gray-600" x-text="selectedProject ? selectedProject.code : ''"></p>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loading" class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Loading hierarchy...</span>
                        </div>

                        <!-- Tree View -->
                        <div x-show="!loading && hierarchy.length > 0" class="space-y-2">
                            <template x-for="cluster in hierarchy" :key="cluster.name">
                                <div class="border border-gray-200 rounded-lg">
                                    <!-- Cluster Header -->
                                    <div class="p-3 bg-gray-50 border-b border-gray-200 cursor-pointer"
                                         @click="cluster.expanded = !cluster.expanded">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-chevron-right transform transition-transform"
                                                   :class="cluster.expanded ? 'rotate-90' : ''"></i>
                                                <i class="fas fa-layer-group ml-2 text-blue-600"></i>
                                                <span class="ml-2 font-medium" x-text="cluster.name"></span>
                                            </div>
                                            <span class="text-sm text-gray-500" x-text="cluster.categories.length + ' categories'"></span>
                                        </div>
                                    </div>

                                    <!-- Categories -->
                                    <div x-show="cluster.expanded" x-collapse class="p-2">
                                        <template x-for="category in cluster.categories" :key="category.name">
                                            <div class="mb-2">
                                                <!-- Category Header -->
                                                <div class="p-2 bg-gray-100 rounded cursor-pointer"
                                                     @click="category.expanded = !category.expanded">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-chevron-right transform transition-transform text-sm"
                                                               :class="category.expanded ? 'rotate-90' : ''"></i>
                                                            <i class="fas fa-tags ml-2 text-purple-600"></i>
                                                            <span class="ml-2 text-sm font-medium" x-text="category.name"></span>
                                                        </div>
                                                        <span class="text-xs text-gray-500" x-text="category.materials.length + ' materials'"></span>
                                                    </div>
                                                </div>

                                                <!-- Materials -->
                                                <div x-show="category.expanded" x-collapse class="ml-4 mt-1 space-y-1">
                                                    <template x-for="material in category.materials" :key="material.id">
                                                        <div class="p-2 border border-gray-200 rounded cursor-pointer hover:bg-blue-50 transition-colors"
                                                             @click="selectMaterial(material)"
                                                             :class="selectedMaterial && selectedMaterial.id === material.id ? 'bg-blue-50 border-blue-300' : ''">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center">
                                                                    <div class="w-3 h-3 rounded-full mr-2"
                                                                         :class="{
                                                                             'bg-red-500': material.status === 'critical',
                                                                             'bg-yellow-500': material.status === 'warning', 
                                                                             'bg-green-500': material.status === 'available'
                                                                         }"></div>
                                                                    <span class="text-sm" x-text="material.name"></span>
                                                                </div>
                                                                <div class="text-xs text-gray-500">
                                                                    <span x-text="material.usage_percentage + '%'"></span>
                                                                </div>
                                                            </div>
                                                            <!-- Mini Progress Bar -->
                                                            <div class="mt-1">
                                                                <div class="w-full bg-gray-200 rounded-full h-1">
                                                                    <div class="h-1 rounded-full transition-all"
                                                                         :class="{
                                                                             'bg-red-500': material.status === 'critical',
                                                                             'bg-yellow-500': material.status === 'warning',
                                                                             'bg-green-500': material.status === 'available'
                                                                         }"
                                                                         :style="'width: ' + material.usage_percentage + '%'"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div x-show="!loading && !selectedProject" class="text-center py-8">
                            <i class="fas fa-project-diagram text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Select a project to view material hierarchy</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Panel -->
                <div class="w-1/2 bg-gray-50 overflow-y-auto">
                    <div class="p-4">
                        <!-- Material Detail -->
                        <div x-show="selectedMaterial && materialDetail" class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <!-- Header -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900" x-text="materialDetail ? materialDetail.material.name : ''"></h3>
                                        <p class="text-sm text-gray-600">
                                            <span x-text="materialDetail ? materialDetail.material.category : ''"></span> • 
                                            <span x-text="materialDetail ? materialDetail.cluster : ''"></span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-4 h-4 rounded-full inline-block mr-2"
                                             :class="{
                                                 'bg-red-500': materialDetail && materialDetail.status === 'critical',
                                                 'bg-yellow-500': materialDetail && materialDetail.status === 'warning',
                                                 'bg-green-500': materialDetail && materialDetail.status === 'available'
                                             }"></div>
                                        <span class="text-sm font-medium"
                                              :class="{
                                                  'text-red-600': materialDetail && materialDetail.status === 'critical',
                                                  'text-yellow-600': materialDetail && materialDetail.status === 'warning',
                                                  'text-green-600': materialDetail && materialDetail.status === 'available'
                                              }"
                                              x-text="materialDetail ? materialDetail.usage_percentage + '% Used' : ''"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600" x-text="materialDetail ? formatNumber(materialDetail.received_quantity) : '0'"></div>
                                        <div class="text-sm text-gray-600">Received</div>
                                        <div class="text-xs text-gray-500" x-text="materialDetail ? materialDetail.material.unit : ''"></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-orange-600" x-text="materialDetail ? formatNumber(materialDetail.total_used) : '0'"></div>
                                        <div class="text-sm text-gray-600">Used</div>
                                        <div class="text-xs text-gray-500" x-text="materialDetail ? materialDetail.material.unit : ''"></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600" x-text="materialDetail ? formatNumber(materialDetail.remaining_quantity) : '0'"></div>
                                        <div class="text-sm text-gray-600">Remaining</div>
                                        <div class="text-xs text-gray-500" x-text="materialDetail ? materialDetail.material.unit : ''"></div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Usage Progress</span>
                                        <span x-text="materialDetail ? materialDetail.usage_percentage + '%' : '0%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full transition-all"
                                             :class="{
                                                 'bg-red-500': materialDetail && materialDetail.status === 'critical',
                                                 'bg-yellow-500': materialDetail && materialDetail.status === 'warning',
                                                 'bg-green-500': materialDetail && materialDetail.status === 'available'
                                             }"
                                             :style="materialDetail ? 'width: ' + materialDetail.usage_percentage + '%' : 'width: 0%'"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex space-x-2">
                                    <button @click="addUsage()" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Add Usage
                                    </button>
                                    <button @click="editMaterial()" 
                                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </button>
                                </div>
                            </div>

                            <!-- Usage History -->
                            <div class="p-4">
                                <h4 class="font-medium text-gray-900 mb-3">Usage History</h4>
                                <div x-show="materialDetail && materialDetail.usage_history.length > 0" class="space-y-2 max-h-64 overflow-y-auto">
                                    <template x-for="usage in (materialDetail ? materialDetail.usage_history : [])" :key="usage.id">
                                        <div class="p-3 bg-gray-50 rounded border">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-medium text-sm" x-text="formatNumber(usage.quantity) + ' ' + (materialDetail ? materialDetail.material.unit : '')"></div>
                                                    <div class="text-xs text-gray-600" x-text="usage.usage_date"></div>
                                                    <div class="text-xs text-gray-500" x-text="'DN: ' + usage.dn_number"></div>
                                                </div>
                                                <div class="text-xs text-gray-500" x-text="usage.user_name"></div>
                                            </div>
                                            <div x-show="usage.notes" class="mt-2 text-xs text-gray-600" x-text="usage.notes"></div>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="!materialDetail || materialDetail.usage_history.length === 0" class="text-center py-4">
                                    <i class="fas fa-history text-2xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500 text-sm">No usage history available</p>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div x-show="!selectedMaterial" class="text-center py-12">
                            <i class="fas fa-cube text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Select a material to view details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function boqDashboard() {
        return {
            selectedProject: null,
            selectedMaterial: null,
            materialDetail: null,
            hierarchy: [],
            loading: false,

            selectProject(project) {
                this.selectedProject = project;
                this.selectedMaterial = null;
                this.materialDetail = null;
                this.loadProjectHierarchy(project.id);
            },

            async loadProjectHierarchy(projectId) {
                this.loading = true;
                try {
                    const response = await fetch(`/admin/boq-actuals/ajax/project-hierarchy/${projectId}`);
                    const data = await response.json();
                    this.hierarchy = data.hierarchy.map(cluster => ({
                        ...cluster,
                        expanded: false,
                        categories: cluster.categories.map(category => ({
                            ...category,
                            expanded: false
                        }))
                    }));
                } catch (error) {
                    console.error('Error loading project hierarchy:', error);
                } finally {
                    this.loading = false;
                }
            },

            async selectMaterial(material) {
                this.selectedMaterial = material;
                await this.loadMaterialDetail(material);
            },

            async loadMaterialDetail(material) {
                try {
                    const response = await fetch(`/admin/boq-actuals/ajax/material-detail?material_id=${material.id}&project_id=${this.selectedProject.id}&cluster=${material.cluster}`);
                    this.materialDetail = await response.json();
                } catch (error) {
                    console.error('Error loading material detail:', error);
                }
            },

            formatNumber(num) {
                return new Intl.NumberFormat().format(num);
            },

            addUsage() {
                if (this.materialDetail) {
                    window.location.href = `/admin/boq-actuals/create?material_id=${this.materialDetail.material.id}&project_id=${this.materialDetail.project_id}&cluster=${this.materialDetail.cluster}`;
                }
            },

            editMaterial() {
                // This could open a modal or redirect to edit page
                console.log('Edit material functionality');
            }
        }
    }
    </script>
</x-admin-layout>