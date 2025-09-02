<x-admin-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl px-8 py-6 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Tambah Dokumen Baru</h1>
                    <p class="text-red-100 text-lg">Upload dokumen atau template untuk user lapangan</p>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('admin.documents.index') }}"
                       class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-xl font-semibold hover:bg-opacity-30 transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Error Messages -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('title') border-red-300 @enderror"
                               placeholder="Masukkan judul dokumen yang jelas dan deskriptif">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category and Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('category') border-red-300 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="template" {{ old('category') == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="manual" {{ old('category') == 'manual' ? 'selected' : '' }}>Manual/Panduan</option>
                                <option value="form" {{ old('category') == 'form' ? 'selected' : '' }}>Form</option>
                                <option value="document" {{ old('category') == 'document' ? 'selected' : '' }}>Dokumen</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Dokumen</label>
                            <div class="flex items-center mt-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" checked
                                           class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Aktifkan dokumen (user dapat mendownload)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('description') border-red-300 @enderror"
                                  placeholder="Berikan deskripsi singkat mengenai dokumen ini (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File <span class="text-red-500">*</span>
                        </label>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <input type="file" name="file" id="file" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                                   class="hidden @error('file') border-red-300 @enderror">

                            <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('file').click()">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-red-600 hover:text-red-500">Klik untuk upload file</span>
                                </p>
                                <p class="text-xs text-gray-500">Atau drag & drop file di sini</p>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Max 20MB)</p>
                            </div>
                        </div>

                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.documents.index') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-3 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const uploadArea = document.getElementById('upload-area');
                uploadArea.innerHTML = `
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-900 font-medium">${file.name}</p>
                        <p class="text-xs text-gray-500">Ukuran: ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        <button type="button" onclick="resetFileInput()" class="mt-2 text-xs text-red-600 hover:text-red-800">Hapus file</button>
                    </div>
                `;
            }
        });

        function resetFileInput() {
            document.getElementById('file').value = '';
            document.getElementById('upload-area').innerHTML = `
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <span class="font-medium text-red-600 hover:text-red-500">Klik untuk upload file</span>
                </p>
                <p class="text-xs text-gray-500">Atau drag & drop file di sini</p>
                <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Max 20MB)</p>
            `;
        }
    </script>
</x-admin-layout>
