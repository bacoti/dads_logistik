<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah BOQ Actual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-white">Tambah Data BOQ Actual</h3>
                            <p class="text-green-100 text-sm">Input data pemakaian material aktual per proyek</p>
                        </div>
                        <a href="{{ route('admin.boq-actuals.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-sm font-medium text-white hover:bg-opacity-30 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.boq-actuals.store') }}" 
                      method="POST" 
                      x-data="boqActualForm()" 
                      @submit="loading = true"
                      class="p-6 space-y-6">
                    @csrf

                    <!-- Project Selection Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Project -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Proyek <span class="text-red-500">*</span>
                            </label>
                            <select name="project_id" 
                                    id="project_id" 
                                    x-model="selectedProject"
                                    @change="onProjectChange()"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('project_id') border-red-500 @enderror">
                                <option value="">Pilih Proyek...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub Project -->
                        <div>
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Proyek <span class="text-red-500">*</span>
                            </label>
                            <select name="sub_project_id" 
                                    id="sub_project_id" 
                                    x-model="selectedSubProject"
                                    @change="onSubProjectChange()"
                                    :disabled="!selectedProject || loadingSubProjects"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 disabled:bg-gray-100 disabled:cursor-not-allowed @error('sub_project_id') border-red-500 @enderror">
                                <option value="">
                                    <span x-show="!selectedProject">Pilih proyek terlebih dahulu...</span>
                                    <span x-show="selectedProject && !loadingSubProjects">Pilih Sub Proyek...</span>
                                    <span x-show="loadingSubProjects">Loading...</span>
                                </option>
                                <template x-for="subProject in subProjects" :key="subProject.id">
                                    <option :value="subProject.id" x-text="subProject.name"></option>
                                </template>
                            </select>
                            @error('sub_project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location and DN Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cluster -->
                        <div>
                            <label for="cluster" class="block text-sm font-medium text-gray-700 mb-2">
                                Cluster <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="cluster" 
                                   id="cluster" 
                                   value="{{ old('cluster') }}"
                                   required
                                   placeholder="Masukkan cluster..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('cluster') border-red-500 @enderror">
                            @error('cluster')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DN Number -->
                        <div>
                            <label for="dn_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor DN <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="dn_number" 
                                   id="dn_number" 
                                   value="{{ old('dn_number') }}"
                                   required
                                   placeholder="Masukkan nomor Delivery Note..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('dn_number') border-red-500 @enderror">
                            @error('dn_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Material Selection -->
                    <div>
                        <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Material <span class="text-red-500">*</span>
                        </label>
                        <select name="material_id" 
                                id="material_id" 
                                x-model="selectedMaterial"
                                :disabled="!selectedSubProject || loadingMaterials"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 disabled:bg-gray-100 disabled:cursor-not-allowed @error('material_id') border-red-500 @enderror">
                            <option value="">
                                <span x-show="!selectedSubProject">Pilih sub proyek terlebih dahulu...</span>
                                <span x-show="selectedSubProject && !loadingMaterials">Pilih Material...</span>
                                <span x-show="loadingMaterials">Loading materials...</span>
                            </option>
                            <template x-for="material in materials" :key="material.id">
                                <option :value="material.id">
                                    <span x-text="material.name"></span> (<span x-text="material.category"></span>) - <span x-text="material.unit"></span>
                                </option>
                            </template>
                        </select>
                        @error('material_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity and Date Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Actual Quantity -->
                        <div>
                            <label for="actual_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity Terpakai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="actual_quantity" 
                                       id="actual_quantity" 
                                       value="{{ old('actual_quantity') }}"
                                       step="0.01"
                                       min="0"
                                       required
                                       placeholder="0.00"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('actual_quantity') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm" x-text="selectedMaterialUnit || 'unit'"></span>
                                </div>
                            </div>
                            @error('actual_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Usage Date -->
                        <div>
                            <label for="usage_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pemakaian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="usage_date" 
                                   id="usage_date" 
                                   value="{{ old('usage_date', date('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('usage_date') border-red-500 @enderror">
                            @error('usage_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  placeholder="Tambahkan catatan atau keterangan tambahan..."
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.boq-actuals.index') }}" 
                           class="inline-flex items-center px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="submit" 
                                :disabled="loading"
                                class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out">
                            <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-show="!loading">Simpan BOQ Actual</span>
                            <span x-show="loading">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function boqActualForm() {
            return {
                selectedProject: '{{ old('project_id') }}',
                selectedSubProject: '{{ old('sub_project_id') }}',
                selectedMaterial: '{{ old('material_id') }}',
                selectedMaterialUnit: '',
                subProjects: [],
                materials: [],
                loading: false,
                loadingSubProjects: false,
                loadingMaterials: false,

                async onProjectChange() {
                    this.selectedSubProject = '';
                    this.selectedMaterial = '';
                    this.subProjects = [];
                    this.materials = [];
                    this.selectedMaterialUnit = '';

                    if (!this.selectedProject) return;

                    this.loadingSubProjects = true;
                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/sub-projects/${this.selectedProject}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading sub-projects', response.status, text);
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data sub proyek. Silakan coba lagi.' });
                        } else {
                            const data = await response.json();
                            this.subProjects = data;
                        }
                    } catch (error) {
                        console.error('Error loading sub projects:', error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data sub proyek. Silakan coba lagi.' });
                    } finally {
                        this.loadingSubProjects = false;
                    }
                },

                async onSubProjectChange() {
                    this.selectedMaterial = '';
                    this.materials = [];
                    this.selectedMaterialUnit = '';

                    if (!this.selectedSubProject) return;

                    this.loadingMaterials = true;
                    try {
                        const response = await fetch(`/admin/boq-actuals/ajax/materials/${this.selectedSubProject}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Failed loading materials', response.status, text);
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data material. Silakan coba lagi.' });
                        } else {
                            const data = await response.json();
                            this.materials = data;
                        }
                    } catch (error) {
                        console.error('Error loading materials:', error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data material. Silakan coba lagi.' });
                    } finally {
                        this.loadingMaterials = false;
                    }
                },

                // Watch for material selection to update unit display
                init() {
                    this.$watch('selectedMaterial', (value) => {
                        if (value) {
                            const material = this.materials.find(m => m.id == value);
                            this.selectedMaterialUnit = material ? material.unit : '';
                        } else {
                            this.selectedMaterialUnit = '';
                        }
                    });

                    // Load sub projects if project is already selected (from old input)
                    if (this.selectedProject) {
                        this.onProjectChange().then(() => {
                            // Load materials if sub project is already selected
                            if (this.selectedSubProject) {
                                this.onSubProjectChange();
                            }
                        });
                    }
                }
            }
        }

        // Show validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
        @endif
    </script>
    @endpush
</x-admin-layout>