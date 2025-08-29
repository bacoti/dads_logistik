<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah PO Material') }}
            </h2>
            <a href="{{ route('po.po-materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('po.po-materials.store') }}" method="POST" class="p-6 lg:p-8" id="po-material-form">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- No. PO -->
                        <div class="col-span-1">
                            <label for="po_number" class="block text-sm font-medium text-gray-700 mb-1">
                                No. PO <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="po_number" id="po_number" required
                                   value="{{ old('po_number') }}"
                                   placeholder="Masukkan nomor PO"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('po_number') border-red-300 @enderror">
                            @error('po_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div class="col-span-1">
                            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">
                                Supplier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="supplier" id="supplier" required
                                   value="{{ old('supplier') }}"
                                   placeholder="Nama supplier"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('supplier') border-red-300 @enderror">
                            @error('supplier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Rilis -->
                        <div class="col-span-1">
                            <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Rilis <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="release_date" id="release_date" required
                                   value="{{ old('release_date') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('release_date') border-red-300 @enderror">
                            @error('release_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="col-span-1">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location" required
                                   value="{{ old('location') }}"
                                   placeholder="Lokasi pengiriman"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('location') border-red-300 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project -->
                        <div class="col-span-1">
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Project <span class="text-red-500">*</span>
                            </label>
                            <select name="project_id" id="project_id" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('project_id') border-red-300 @enderror">
                                <option value="">Pilih Project</option>
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
                        <div class="col-span-1">
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Sub Project
                            </label>
                            <select name="sub_project_id" id="sub_project_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sub_project_id') border-red-300 @enderror">
                                <option value="">Pilih Sub Project (Opsional)</option>
                                <!-- Will be populated via JavaScript -->
                            </select>
                            @error('sub_project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan (Nama Material) -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Keterangan (Nama Material) <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" required rows="3"
                                      placeholder="Deskripsi material yang dibutuhkan"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                Qty <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="quantity" id="quantity" required min="0" step="0.01"
                                   value="{{ old('quantity') }}"
                                   placeholder="0"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('quantity') border-red-300 @enderror">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div class="col-span-1">
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <select name="unit" id="unit" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('unit') border-red-300 @enderror">
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
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      placeholder="Catatan tambahan (opsional)"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('po.po-materials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit" id="submit-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="submit-text">Simpan PO Material</span>
                            <span id="loading-text" class="hidden">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectSelect = document.getElementById('project_id');
            const subProjectSelect = document.getElementById('sub_project_id');
            const form = document.getElementById('po-material-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');

            // Debug console
            console.log('PO Material Form initialized');
            console.log('Form action:', form.action);

            // Form submission handling
            form.addEventListener('submit', function(e) {
                console.log('Form submitted');

                // Disable button and show loading
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

            // Project change handler
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                console.log('Project selected:', projectId);

                subProjectSelect.innerHTML = '<option value="">Pilih Sub Project (Opsional)</option>';

                if (projectId) {
                    const url = `{{ route('po.ajax.sub-projects') }}?project_id=${projectId}`;
                    console.log('Fetching sub projects from:', url);

                    fetch(url)
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Sub projects received:', data);
                            data.forEach(subProject => {
                                const option = document.createElement('option');
                                option.value = subProject.id;
                                option.textContent = subProject.name;
                                subProjectSelect.appendChild(option);
                            });

                            // Restore old value if it exists
                            const oldValue = '{{ old('sub_project_id') }}';
                            if (oldValue) {
                                subProjectSelect.value = oldValue;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching sub projects:', error);
                            alert('Gagal mengambil data sub project. Silakan refresh halaman.');
                        });
                }
            });

            // Load sub projects for existing project selection
            if (projectSelect.value) {
                console.log('Loading sub projects for existing selection');
                projectSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endpush
</x-app-layout>
