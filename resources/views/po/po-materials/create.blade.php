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
                <div>
                    <x-section-header 
                        title="Informasi Material"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'></path></svg>'" />
                    
                    <div class="mt-6">
                        <!-- Keterangan (Nama Material) -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan (Nama Material) *
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      required 
                                      rows="4"
                                      placeholder="Deskripsi material yang dibutuhkan"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none {{ $errors->has('description') ? 'border-red-500 bg-red-50' : 'bg-white' }}">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kuantitas *
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       required 
                                       min="0" 
                                       step="0.01"
                                       value="{{ old('quantity') }}"
                                       placeholder="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('quantity') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                @error('quantity')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Satuan *
                                </label>
                                <select name="unit" 
                                        id="unit" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 {{ $errors->has('unit') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                    <option value="">Pilih Satuan</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>gram</option>
                                    <option value="ton" {{ old('unit') == 'ton' ? 'selected' : '' }}>ton</option>
                                    <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>meter</option>
                                    <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>cm</option>
                                    <option value="mm" {{ old('unit') == 'mm' ? 'selected' : '' }}>mm</option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>liter</option>
                                    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ml</option>
                                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>unit</option>
                                    <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>box</option>
                                    <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>pack</option>
                                </select>
                                @error('unit')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
