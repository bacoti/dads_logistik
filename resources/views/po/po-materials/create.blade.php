<x-app-layout>
    <x-page-header
        title="Tambah PO Material"
        subtitle="Buat permintaan purchase order material baru"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('po.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'PO Material', 'url' => route('po.po-materials.index')],
            ['title' => 'Tambah PO']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button
                variant="secondary"
                href="{{ route('po.po-materials.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('po.po-materials.store') }}" method="POST"
              class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8"
              id="po-material-form">
            @csrf

            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <x-section-header
                        title="Informasi Dasar"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- No. PO -->
                        <div>
                            <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">
                                No. PO *
                            </label>
                            <input type="text"
                                   name="po_number"
                                   id="po_number"
                                   required
                                   value="{{ old('po_number') }}"
                                   placeholder="Masukkan nomor PO"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('po_number') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('po_number')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                                Supplier *
                            </label>
                            <input type="text"
                                   name="supplier"
                                   id="supplier"
                                   required
                                   value="{{ old('supplier') }}"
                                   placeholder="Nama supplier"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('supplier') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('supplier')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Rilis -->
                        <div>
                            <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Rilis *
                            </label>
                            <input type="date"
                                   name="release_date"
                                   id="release_date"
                                   required
                                   value="{{ old('release_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('release_date') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('release_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi *
                            </label>
                            <input type="text"
                                   name="location"
                                   id="location"
                                   required
                                   value="{{ old('location') }}"
                                   placeholder="Lokasi pengiriman"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('location') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('location')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Project Information -->
                <div x-data="{ projects: @js($projects), subProjects: [], selectedProject: '{{ old('project_id') }}' }" x-init="
                    if (selectedProject) {
                        const project = projects.find(p => p.id == selectedProject);
                        if (project) subProjects = project.sub_projects;
                    }
                ">
                    <x-section-header
                        title="Informasi Proyek"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Project -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Proyek *
                            </label>
                            <select name="project_id"
                                    id="project_id"
                                    required
                                    x-model="selectedProject"
                                    x-on:change="
                                        const project = projects.find(p => p.id == selectedProject);
                                        if (project) {
                                            subProjects = project.sub_projects;
                                        } else {
                                            subProjects = [];
                                        }
                                        document.querySelector('select[name=sub_project_id]').value = '';
                                    "
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('project_id') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                <option value="">Pilih Proyek</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub Project -->
                        <div>
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Proyek (Opsional)
                            </label>
                            <select name="sub_project_id"
                                    id="sub_project_id"
                                    x-bind:disabled="!selectedProject || subProjects.length === 0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed {{ $errors->has('sub_project_id') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                <option value="">Pilih Sub Proyek (Opsional)</option>
                                <template x-for="subProject in subProjects" :key="subProject.id">
                                    <option x-bind:value="subProject.id"
                                            x-text="subProject.name"
                                            x-bind:selected="subProject.id == '{{ old('sub_project_id') }}'">
                                    </option>
                                </template>
                            </select>
                            @error('sub_project_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Material Information -->
                <div x-data="{
                    materials: [
                        {
                            id: Date.now(),
                            description: '',
                            quantity: '',
                            unit: '',
                            collapsed: false
                        }
                    ],

                    addMaterial() {
                        this.materials.push({
                            id: Date.now() + Math.random(),
                            description: '',
                            quantity: '',
                            unit: '',
                            collapsed: false
                        });

                        // Auto scroll ke material baru
                        this.$nextTick(() => {
                            const newMaterial = document.querySelector(`[data-material-index='${this.materials.length - 1}']`);
                            if (newMaterial) {
                                newMaterial.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                const textarea = newMaterial.querySelector('textarea');
                                if (textarea) {
                                    setTimeout(() => textarea.focus(), 300);
                                }
                            }
                        });
                    },

                    removeMaterial(index) {
                        if (this.materials.length > 1) {
                            this.materials.splice(index, 1);
                        }
                    },

                    duplicateMaterial(index) {
                        const material = this.materials[index];
                        this.materials.splice(index + 1, 0, {
                            id: Date.now() + Math.random(),
                            description: material.description,
                            quantity: material.quantity,
                            unit: material.unit,
                            collapsed: false
                        });
                        this.$nextTick(() => {
                            const newMaterial = document.querySelector(`[data-material-index='${index + 1}']`);
                            if (newMaterial) {
                                newMaterial.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    },

                    toggleCollapse(index) {
                        this.materials[index].collapsed = !this.materials[index].collapsed;
                    }
                }" x-init="console.log('Material manager initialized with', materials.length, 'materials')">

                    <x-section-header
                        title="Informasi Material"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'></path></svg>'" />

                    <div class="mt-6 relative">
                        <!-- Sticky Add Button -->
                        <div class="sticky top-4 z-10 mb-4 flex justify-end">
                            <button
                                type="button"
                                @click="addMaterial(); $dispatch('material-added')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Material (<span x-text="materials.length"></span>)
                            </button>
                        </div>

                        <!-- Material Items Container -->
                        <div id="material-items-container" class="space-y-3">
                            <template x-for="(material, index) in materials" :key="material.id">
                                <div :data-material-index="index"
                                     class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">

                                    <!-- Material Header - Compact -->
                                    <div class="flex items-center justify-between p-4 border-b border-gray-100 cursor-pointer"
                                         @click="toggleCollapse(index)">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-blue-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold text-sm">
                                                <span x-text="index + 1"></span>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">
                                                    <span x-show="!material.description || material.description.length === 0">Material <span x-text="index + 1"></span></span>
                                                    <span x-show="material.description && material.description.length > 0"
                                                          x-text="material.description.length > 40 ? material.description.substring(0, 40) + '...' : material.description"
                                                          class="text-gray-700"></span>
                                                </h4>
                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    <span x-show="material.quantity">Qty: <span x-text="material.quantity"></span></span>
                                                    <span x-show="material.unit" x-text="material.unit"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2">
                                            <!-- Duplicate Button -->
                                            <button
                                                type="button"
                                                @click.stop="duplicateMaterial(index)"
                                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors duration-200"
                                                title="Duplikat Material">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>

                                            <!-- Remove Button -->
                                            <button
                                                type="button"
                                                x-show="materials.length > 1"
                                                @click.stop="removeMaterial(index)"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors duration-200"
                                                title="Hapus Material">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>

                                            <!-- Collapse Toggle -->
                                            <div class="p-1.5 text-gray-400">
                                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                                     :class="material.collapsed ? 'rotate-180' : ''"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Material Form - Collapsible -->
                                    <div x-show="!material.collapsed"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-y-95"
                                         x-transition:enter-end="opacity-100 transform scale-y-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-y-100"
                                         x-transition:leave-end="opacity-0 transform scale-y-95"
                                         class="p-4 space-y-4">

                                        <!-- Nama Material -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Material *
                                            </label>
                                            <textarea
                                                :name="`materials[${index}][description]`"
                                                x-model="material.description"
                                                required
                                                rows="3"
                                                placeholder="Deskripsi material yang dibutuhkan"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none text-sm">
                                            </textarea>
                                        </div>

                                        <!-- Quantity & Unit - Inline -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Quantity -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Kuantitas *
                                                </label>
                                                <input
                                                    type="number"
                                                    :name="`materials[${index}][quantity]`"
                                                    x-model="material.quantity"
                                                    required
                                                    min="0"
                                                    step="0.01"
                                                    placeholder="0"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm">
                                            </div>

                                            <!-- Satuan -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Satuan *
                                                </label>
                                                <select
                                                    :name="`materials[${index}][unit]`"
                                                    x-model="material.unit"
                                                    required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm">
                                                    <option value="">Pilih Satuan</option>
                                                    <option value="kg">kg</option>
                                                    <option value="gram">gram</option>
                                                    <option value="ton">ton</option>
                                                    <option value="meter">meter</option>
                                                    <option value="cm">cm</option>
                                                    <option value="mm">mm</option>
                                                    <option value="liter">liter</option>
                                                    <option value="ml">ml</option>
                                                    <option value="pcs">pcs</option>
                                                    <option value="unit">unit</option>
                                                    <option value="box">box</option>
                                                    <option value="pack">pack</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Material Summary Info -->
                        <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        <span class="font-semibold" x-text="materials.length"></span> material siap diajukan
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-blue-600">
                                    <kbd class="px-2 py-1 bg-white rounded border border-blue-200">Click header</kbd>
                                    <span>untuk collapse</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <x-section-header
                        title="Informasi Tambahan"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'" />

                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  placeholder="Catatan tambahan (opsional)"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none {{ $errors->has('notes') ? 'border-red-500 bg-red-50' : 'bg-white' }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <x-button
                        variant="secondary"
                        href="{{ route('po.po-materials.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                        Batal
                    </x-button>

                    <x-button
                        type="submit"
                        id="submit-btn"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'"
                        class="bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submit-text">Simpan PO Material</span>
                        <span id="loading-text" class="hidden">Menyimpan...</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth transitions */
        .material-item {
            transition: all 0.3s ease;
        }

        /* Focus ring improvements */
        .focus\:ring-2:focus {
            ring-offset-color: white;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('po-material-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');

            if (!form || !submitBtn) {
                console.error('❌ Form elements not found');
                return;
            }

            // Form submission handler with loading state
            form.addEventListener('submit', function(e) {
                // Disable submit button and show loading
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');

                // Validate required fields
                const requiredFields = ['po_number', 'supplier', 'release_date', 'location', 'project_id'];
                let hasError = false;

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field && !field.value.trim()) {
                        hasError = true;
                    }
                });

                // Validate materials
                const descriptionInputs = document.querySelectorAll('textarea[name*="[description]"]');
                const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
                const unitSelects = document.querySelectorAll('select[name*="[unit]"]');

                if (descriptionInputs.length === 0) {
                    alert('Mohon tambahkan minimal satu material!');
                    hasError = true;
                } else {
                    // Check each material
                    for (let i = 0; i < descriptionInputs.length; i++) {
                        if (!descriptionInputs[i].value.trim()) {
                            alert(`Material ${i + 1}: Nama material harus diisi!`);
                            hasError = true;
                            break;
                        }
                        if (!quantityInputs[i] || !quantityInputs[i].value || quantityInputs[i].value <= 0) {
                            alert(`Material ${i + 1}: Kuantitas harus diisi dan lebih dari 0!`);
                            hasError = true;
                            break;
                        }
                        if (!unitSelects[i] || !unitSelects[i].value) {
                            alert(`Material ${i + 1}: Satuan harus dipilih!`);
                            hasError = true;
                            break;
                        }
                    }
                }

                if (hasError) {
                    e.preventDefault();

                    // Re-enable button
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    return false;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
