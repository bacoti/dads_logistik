<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit PO Material') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Ubah data permintaan material untuk proyek
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('po.po-materials.show', $poMaterial) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    Detail
                </a>
                <a href="{{ route('po.po-materials.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Status Permintaan</h3>
                            <p class="mt-1 text-sm text-gray-600">PO Material #{{ $poMaterial->po_number }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            {!! $poMaterial->status_badge !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">Edit Data PO Material</h3>
                            <p class="text-sm text-gray-500">Perbarui informasi permintaan material</p>
                        </div>
                    </div>
                </div>

                <form id="po-material-form" action="{{ route('po.po-materials.update', $poMaterial) }}" method="POST" class="px-6 py-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Dasar</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- No. PO -->
                                <div>
                                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        No. PO <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="po_number" id="po_number" required
                                               value="{{ old('po_number', $poMaterial->po_number) }}"
                                               placeholder="Masukkan nomor PO"
                                               class="block w-full pl-3 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('po_number') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    </div>
                                    @error('po_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Supplier -->
                                <div>
                                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                                        Supplier <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="supplier" id="supplier" required
                                               value="{{ old('supplier', $poMaterial->supplier) }}"
                                               placeholder="Nama supplier"
                                               class="block w-full pl-3 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('supplier') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    </div>
                                    @error('supplier')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Rilis -->
                                <div>
                                    <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Rilis <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="date" name="release_date" id="release_date" required
                                               value="{{ old('release_date', $poMaterial->release_date ? $poMaterial->release_date->format('Y-m-d') : '') }}"
                                               class="block w-full pl-3 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('release_date') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    </div>
                                    @error('release_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Lokasi -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="location" id="location" required
                                               value="{{ old('location', $poMaterial->location) }}"
                                               placeholder="Lokasi pengiriman"
                                               class="block w-full pl-3 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('location') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    </div>
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Project Information -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Proyek</h4>
                            <div x-data="{
                                projects: @js($projects),
                                selectedProject: '{{ old('project_id', $poMaterial->project_id) }}',
                                selectedSubProject: '{{ old('sub_project_id', $poMaterial->sub_project_id) }}',
                                get subProjects() {
                                    const project = this.projects.find(p => p.id == this.selectedProject);
                                    return project ? project.sub_projects : [];
                                }
                            }" x-init="
                                $watch('selectedProject', () => {
                                    const validSubProjects = subProjects;
                                    if (selectedSubProject && !validSubProjects.find(sp => sp.id == selectedSubProject)) {
                                        selectedSubProject = '';
                                    }
                                });
                            ">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Project -->
                                    <div>
                                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Project <span class="text-red-500">*</span>
                                        </label>
                                        <select name="project_id" id="project_id" x-model="selectedProject" required
                                                class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('project_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="">Pilih Project</option>
                                            <template x-for="project in projects" :key="project.id">
                                                <option :value="project.id" x-text="project.name"></option>
                                            </template>
                                        </select>
                                        @error('project_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sub Project -->
                                    <div>
                                        <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Sub Project <span class="text-gray-400 text-xs">(Opsional)</span>
                                        </label>
                                        <select name="sub_project_id" id="sub_project_id" x-model="selectedSubProject"
                                                class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sub_project_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="">Pilih Sub Project (Opsional)</option>
                                            <template x-for="subProject in subProjects" :key="subProject.id">
                                                <option :value="subProject.id" x-text="subProject.name"></option>
                                            </template>
                                        </select>
                                        @error('sub_project_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Material Details -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Detail Material</h4>
                            <div class="space-y-6">
                                <!-- Keterangan -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Keterangan (Nama Material) <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="description" id="description" required rows="4"
                                              placeholder="Deskripsi detail material yang dibutuhkan..."
                                              class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm resize-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description', $poMaterial->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Quantity -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jumlah <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="quantity" id="quantity" required min="0" step="0.01"
                                                   value="{{ old('quantity', $poMaterial->quantity) }}"
                                                   placeholder="0"
                                                   class="block w-full pl-3 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('quantity') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        </div>
                                        @error('quantity')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Unit -->
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                            Satuan <span class="text-red-500">*</span>
                                        </label>
                                        <select name="unit" id="unit" required
                                                class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('unit') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="">Pilih Satuan</option>
                                            <option value="kg" {{ old('unit', $poMaterial->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                            <option value="gram" {{ old('unit', $poMaterial->unit) == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                            <option value="ton" {{ old('unit', $poMaterial->unit) == 'ton' ? 'selected' : '' }}>Ton</option>
                                            <option value="meter" {{ old('unit', $poMaterial->unit) == 'meter' ? 'selected' : '' }}>Meter (m)</option>
                                            <option value="cm" {{ old('unit', $poMaterial->unit) == 'cm' ? 'selected' : '' }}>Centimeter (cm)</option>
                                            <option value="mm" {{ old('unit', $poMaterial->unit) == 'mm' ? 'selected' : '' }}>Millimeter (mm)</option>
                                            <option value="liter" {{ old('unit', $poMaterial->unit) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                            <option value="ml" {{ old('unit', $poMaterial->unit) == 'ml' ? 'selected' : '' }}>Milliliter (mL)</option>
                                            <option value="pcs" {{ old('unit', $poMaterial->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                            <option value="unit" {{ old('unit', $poMaterial->unit) == 'unit' ? 'selected' : '' }}>Unit</option>
                                            <option value="box" {{ old('unit', $poMaterial->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                            <option value="pack" {{ old('unit', $poMaterial->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                        </select>
                                        @error('unit')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              placeholder="Catatan tambahan untuk material ini..."
                                              class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm resize-none @error('notes') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('notes', $poMaterial->notes) }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('po.po-materials.show', $poMaterial) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Batal
                            </a>
                            <button type="submit" id="submit-btn"
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <span id="submit-text">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586l-1.293-1.293z"/>
                                    </svg>
                                    Update PO Material
                                </span>
                                <span id="loading-text" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memperbarui...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('po-material-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');

            // Form submission handler with loading state
            form.addEventListener('submit', function(e) {
                console.log('Form submitted');

                // Disable submit button and show loading
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');

                // Validate required fields
                const requiredFields = ['po_number', 'supplier', 'release_date', 'location', 'project_id', 'description', 'quantity', 'unit'];
                let hasError = false;

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field && !field.value.trim()) {
                        console.error('Field ' + fieldName + ' is empty');
                        hasError = true;
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');

                    // Re-enable button
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    return false;
                }

                console.log('Form validation passed, submitting...');
            });
        });
    </script>
    @endpush
</x-app-layout>
