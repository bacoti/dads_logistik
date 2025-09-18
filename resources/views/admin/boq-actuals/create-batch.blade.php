<x-admin-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Input BOQ Actual (Batch Mode)</h2>
                            <p class="text-gray-600 mt-1">Input pemakaian material actual dengan sistem batch input yang lebih efisien</p>
                        </div>
                        <a href="{{ route('admin.boq-actuals.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    <div x-data="boqActualBatchInput()" class="space-y-6">
                        <!-- Step 1: Project Selection -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-800 mb-4">
                                <span class="bg-blue-600 text-white rounded-full w-6 h-6 inline-flex items-center justify-center text-sm mr-2">1</span>
                                Pilih Project & Detail
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Project -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Project *</label>
                                    <select x-model="selectedProject" 
                                            @change="onProjectChange()"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sub Project -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sub Project *</label>
                                    <select x-model="selectedSubProject" 
                                            @change="onSubProjectChange()"
                                            :disabled="!selectedProject || isLoadingSubProjects"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100">
                                        <option value="">Pilih Sub Project</option>
                                        <template x-for="subProject in subProjects" :key="subProject.id">
                                            <option :value="subProject.id" x-text="subProject.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingSubProjects" class="text-sm text-blue-600 mt-1">Loading...</div>
                                </div>

                                <!-- Cluster -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cluster *</label>
                                    <select x-model="selectedCluster" 
                                            @change="onClusterChange()"
                                            :disabled="!selectedSubProject || isLoadingClusters"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100">
                                        <option value="">Pilih Cluster</option>
                                        <template x-for="cluster in clusters" :key="cluster">
                                            <option :value="cluster" x-text="cluster"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingClusters" class="text-sm text-blue-600 mt-1">Loading...</div>
                                </div>

                                <!-- DN Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">DN Number *</label>
                                    <select x-model="selectedDNNumber" 
                                            @change="onDNNumberChange()"
                                            :disabled="!selectedCluster || isLoadingDNNumbers"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:bg-gray-100">
                                        <option value="">Pilih DN Number</option>
                                        <template x-for="dnNumber in dnNumbers" :key="dnNumber">
                                            <option :value="dnNumber" x-text="dnNumber"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingDNNumbers" class="text-sm text-blue-600 mt-1">Loading...</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pemakaian *</label>
                                    <input type="date" 
                                           x-model="usageDate"
                                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           :value="new Date().toISOString().split('T')[0]">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Materials Input Table -->
                        <div x-show="showMaterialsTable" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">
                                <span class="bg-green-600 text-white rounded-full w-6 h-6 inline-flex items-center justify-center text-sm mr-2">2</span>
                                Input Pemakaian Material
                            </h3>

                            <div x-show="isLoadingMaterials" class="text-center py-8">
                                <div class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading materials...
                                </div>
                            </div>

                            <div x-show="!isLoadingMaterials && materials.length > 0" class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diterima (DO)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BOQ Actual Sebelumnya</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Input Actual Baru</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="(material, index) in materials" :key="material.id">
                                            <tr :class="{'bg-red-50': material.remaining_stock < 0}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="material.name"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="material.category"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="material.unit"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatNumber(material.received_quantity)"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="formatNumber(material.previous_actual)"></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           x-model="material.actual_quantity"
                                                           @input="calculateRemainingStock(index)"
                                                           class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                           placeholder="0.00">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                                    :class="material.remaining_stock < 0 ? 'text-red-600' : 'text-green-600'">
                                                    <span x-text="formatNumber(material.remaining_stock)"></span>
                                                    <span class="text-xs text-gray-500 ml-1" x-text="material.unit"></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <div x-show="!isLoadingMaterials && materials.length === 0" class="text-center py-8 text-gray-500">
                                Tidak ada material yang ditemukan untuk kombinasi yang dipilih.
                            </div>

                            <!-- Notes -->
                            <div x-show="materials.length > 0" class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea x-model="notes" 
                                          rows="3" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Tambahkan catatan untuk input BOQ Actual ini..."></textarea>
                            </div>

                            <!-- Save Button -->
                            <div x-show="materials.length > 0" class="mt-6 flex justify-end space-x-3">
                                <button type="button" 
                                        @click="resetForm()"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                    Reset
                                </button>
                                <button type="button" 
                                        @click="saveBatchInput()"
                                        :disabled="isSaving || !hasValidInput()"
                                        :class="isSaving || !hasValidInput() ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                                        class="text-white font-bold py-2 px-6 rounded">
                                    <span x-show="!isSaving">Simpan Semua</span>
                                    <span x-show="isSaving">Menyimpan...</span>
                                </button>
                            </div>
                        </div>

                        <!-- Success/Error Messages -->
                        <div x-show="successMessage" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:leave="transition ease-in duration-200"
                             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <span x-text="successMessage"></span>
                        </div>

                        <div x-show="errorMessage" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:leave="transition ease-in duration-200"
                             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <span x-text="errorMessage"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function boqActualBatchInput() {
            return {
                // Form data
                selectedProject: '',
                selectedSubProject: '',
                selectedCluster: '',
                selectedDNNumber: '',
                usageDate: new Date().toISOString().split('T')[0],
                notes: '',

                // Options data
                subProjects: [],
                clusters: [],
                dnNumbers: [],
                materials: [],

                // Loading states
                isLoadingSubProjects: false,
                isLoadingClusters: false,
                isLoadingDNNumbers: false,
                isLoadingMaterials: false,
                isSaving: false,

                // UI states
                showMaterialsTable: false,
                successMessage: '',
                errorMessage: '',

                    async onProjectChange() {
                    this.selectedSubProject = '';
                    this.selectedCluster = '';
                    this.selectedDNNumber = '';
                    this.subProjects = [];
                    this.clusters = [];
                    this.dnNumbers = [];
                    this.materials = [];
                    this.showMaterialsTable = false;

                    if (!this.selectedProject) return;

                    this.isLoadingSubProjects = true;
                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/sub-projects/${this.selectedProject}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading sub-projects', response.status, text);
                            this.showError('Gagal memuat sub projects');
                        } else {
                            this.subProjects = await response.json();
                        }

                    } catch (error) {
                        console.error('Error fetching sub-projects', error);
                        this.showError('Gagal memuat sub projects');
                    }
                    this.isLoadingSubProjects = false;
                },

                async onSubProjectChange() {
                    this.selectedCluster = '';
                    this.selectedDNNumber = '';
                    this.clusters = [];
                    this.dnNumbers = [];
                    this.materials = [];
                    this.showMaterialsTable = false;

                    if (!this.selectedSubProject) return;

                    this.isLoadingClusters = true;
                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/clusters?project_id=${this.selectedProject}&sub_project_id=${this.selectedSubProject}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading clusters', response.status, text);
                            this.showError('Gagal memuat clusters');
                        } else {
                            this.clusters = await response.json();
                        }
                    } catch (error) {
                        console.error('Error fetching clusters', error);
                        this.showError('Gagal memuat clusters');
                    }
                    this.isLoadingClusters = false;
                },

                async onClusterChange() {
                    this.selectedDNNumber = '';
                    this.dnNumbers = [];
                    this.materials = [];
                    this.showMaterialsTable = false;

                    if (!this.selectedCluster) return;

                    this.isLoadingDNNumbers = true;
                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/dn-numbers?project_id=${this.selectedProject}&sub_project_id=${this.selectedSubProject}&cluster=${this.selectedCluster}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading DN numbers', response.status, text);
                            this.showError('Gagal memuat DN numbers');
                        } else {
                            this.dnNumbers = await response.json();
                        }
                    } catch (error) {
                        console.error('Error fetching DN numbers', error);
                        this.showError('Gagal memuat DN numbers');
                    }
                    this.isLoadingDNNumbers = false;
                },

                async onDNNumberChange() {
                    this.materials = [];
                    this.showMaterialsTable = false;

                    if (!this.selectedDNNumber) return;

                    await this.loadMaterials();
                },

                async loadMaterials() {
                    this.isLoadingMaterials = true;
                    this.showMaterialsTable = true;

                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/materials-with-quantities?project_id=${this.selectedProject}&sub_project_id=${this.selectedSubProject}&cluster=${this.selectedCluster}&dn_number=${this.selectedDNNumber}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading materials', response.status, text);
                            this.showError('Gagal memuat materials');
                        } else {
                            this.materials = await response.json();
                            // Initialize actual_quantity and calculate remaining stock
                            this.materials.forEach((material, index) => {
                                material.actual_quantity = 0;
                                this.calculateRemainingStock(index);
                            });
                        }
                    } catch (error) {
                        console.error('Error fetching materials', error);
                        this.showError('Gagal memuat materials');
                    }
                    this.isLoadingMaterials = false;
                },

                calculateRemainingStock(index) {
                    const material = this.materials[index];
                    const actualQuantity = parseFloat(material.actual_quantity) || 0;
                    material.remaining_stock = material.received_quantity - material.previous_actual - actualQuantity;
                },

                hasValidInput() {
                    return this.materials.some(material => 
                        parseFloat(material.actual_quantity) > 0
                    );
                },

                async saveBatchInput() {
                    if (!this.hasValidInput()) {
                        this.showError('Silakan input minimal satu material');
                        return;
                    }

                    this.isSaving = true;
                    this.clearMessages();

                    const inputData = {
                        project_id: this.selectedProject,
                        sub_project_id: this.selectedSubProject,
                        cluster: this.selectedCluster,
                        dn_number: this.selectedDNNumber,
                        usage_date: this.usageDate,
                        notes: this.notes,
                        materials: this.materials.filter(material => 
                            parseFloat(material.actual_quantity) > 0
                        ).map(material => ({
                            material_id: material.id,
                            actual_quantity: parseFloat(material.actual_quantity)
                        }))
                    };

                    try {
                        const response = await fetch('/admin/boq-actuals/batch-store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(inputData)
                        });

                        const result = await response.json();

                        if (response.ok) {
                            this.showSuccess(result.message || 'Data BOQ Actual berhasil disimpan!');
                            setTimeout(() => {
                                window.location.href = '/admin/boq-actuals';
                            }, 2000);
                        } else {
                            this.showError(result.message || 'Terjadi kesalahan saat menyimpan data');
                        }
                    } catch (error) {
                        this.showError('Terjadi kesalahan saat menyimpan data');
                    }
                    this.isSaving = false;
                },

                resetForm() {
                    this.selectedProject = '';
                    this.selectedSubProject = '';
                    this.selectedCluster = '';
                    this.selectedDNNumber = '';
                    this.usageDate = new Date().toISOString().split('T')[0];
                    this.notes = '';
                    this.subProjects = [];
                    this.clusters = [];
                    this.dnNumbers = [];
                    this.materials = [];
                    this.showMaterialsTable = false;
                    this.clearMessages();
                },

                showSuccess(message) {
                    this.successMessage = message;
                    this.errorMessage = '';
                    setTimeout(() => this.successMessage = '', 5000);
                },

                showError(message) {
                    this.errorMessage = message;
                    this.successMessage = '';
                    setTimeout(() => this.errorMessage = '', 5000);
                },

                clearMessages() {
                    this.successMessage = '';
                    this.errorMessage = '';
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(number || 0);
                }
            }
        }
    </script>
</x-admin-layout>