<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Buat Pengajuan MFO Baru</h2>
                            <p class="text-gray-600 mt-1">Buat pengajuan Material Field Order baru</p>
                        </div>
                        <a href="{{ route('user.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Kembali
                        </a>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('user.mfo-requests.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Project Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700">Proyek *</label>
                                <select name="project_id" id="project_id" required 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('project_id') border-red-300 @enderror">
                                    <option value="">Pilih Proyek</option>
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

                            <div>
                                <label for="sub_project_id" class="block text-sm font-medium text-gray-700">Sub Proyek</label>
                                <select name="sub_project_id" id="sub_project_id" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('sub_project_id') border-red-300 @enderror">
                                    <option value="">Pilih Sub Proyek</option>
                                </select>
                                @error('sub_project_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_location" class="block text-sm font-medium text-gray-700">Lokasi Proyek *</label>
                                <input type="text" name="project_location" id="project_location" value="{{ old('project_location') }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('project_location') border-red-300 @enderror"
                                       placeholder="Masukkan lokasi proyek">
                                @error('project_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cluster" class="block text-sm font-medium text-gray-700">Cluster</label>
                                <input type="text" name="cluster" id="cluster" value="{{ old('cluster') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cluster') border-red-300 @enderror"
                                       placeholder="Masukkan cluster (opsional)">
                                @error('cluster')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Request Date -->
                        <div>
                            <label for="request_date" class="block text-sm font-medium text-gray-700">Tanggal Pengajuan *</label>
                            <input type="date" name="request_date" id="request_date" value="{{ old('request_date', date('Y-m-d')) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('request_date') border-red-300 @enderror">
                            @error('request_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan *</label>
                            <textarea name="description" id="description" rows="4" required
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                                      placeholder="Deskripsikan detail pengajuan MFO Anda...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Upload -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700">Upload Dokumen</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload dokumen</span>
                                            <input id="document" name="document" type="file" class="sr-only" 
                                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                   onchange="showFileName(this)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG hingga 10MB</p>
                                    <p id="file-name" class="text-sm text-indigo-600 font-medium"></p>
                                </div>
                            </div>
                            @error('document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('user.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Buat Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load sub projects when project is selected
        document.getElementById('project_id').addEventListener('change', function() {
            const projectId = this.value;
            const subProjectSelect = document.getElementById('sub_project_id');
            
            // Clear existing options
            subProjectSelect.innerHTML = '<option value="">Pilih Sub Proyek</option>';
            
            if (projectId) {
                fetch(`{{ route('user.ajax.sub-projects') }}?project_id=${projectId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subProject => {
                            const option = document.createElement('option');
                            option.value = subProject.id;
                            option.textContent = subProject.name;
                            subProjectSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Show file name when file is selected
        function showFileName(input) {
            const fileName = input.files[0]?.name;
            const fileNameElement = document.getElementById('file-name');
            if (fileName) {
                fileNameElement.textContent = `File terpilih: ${fileName}`;
            } else {
                fileNameElement.textContent = '';
            }
        }
    </script>
</x-app-layout>
