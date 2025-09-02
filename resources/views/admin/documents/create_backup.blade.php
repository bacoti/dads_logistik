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
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('category') border-red-300 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="template" {{ old('category') == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="manual" {{ old('category') == 'manual' ? 'selected' : '' }}>Manual</option>
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
                            
                            <div onclick="document.getElementById('file').click()" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-red-600 hover:text-red-500">Klik untuk upload</span> atau drag & drop file
                                </p>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Maks. 20MB)</p>
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
                const uploadArea = e.target.parentElement;
                uploadArea.innerHTML = `
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-900 font-medium">${file.name}</p>
                        <p class="text-xs text-gray-500">Ukuran: ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                `;
            }
        });
    </script>
</x-admin-layout>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        Tambah Dokumen Baru
                    </h1>
                    <p class="text-indigo-100 text-lg">Upload dokumen atau template untuk user lapangan</p>
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
                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4 rounded-2xl shadow-lg mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-white hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden" x-data="{
                dragover: false,
                fileName: '',
                fileSize: 0,
                previewFile: null,

                handleFiles(files) {
                    if (files.length > 0) {
                        const file = files[0];
                        this.fileName = file.name;
                        this.fileSize = this.formatFileSize(file.size);
                        this.previewFile = file;
                        document.getElementById('file').files = files;
                    }
                },

                formatFileSize(bytes) {
                    if (bytes >= 1073741824) {
                        return (bytes / 1073741824).toFixed(2) + ' GB';
                    } else if (bytes >= 1048576) {
                        return (bytes / 1048576).toFixed(2) + ' MB';
                    } else if (bytes >= 1024) {
                        return (bytes / 1024).toFixed(2) + ' KB';
                    } else {
                        return bytes + ' bytes';
                    }
                },

                removeFile() {
                    this.fileName = '';
                    this.fileSize = 0;
                    this.previewFile = null;
                    document.getElementById('file').value = '';
                }
            }">
                <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                    <h2 class="text-2xl font-bold text-gray-900">Informasi Dokumen</h2>
                    <p class="text-gray-600 mt-2">Lengkapi informasi berikut untuk menambah dokumen baru</p>
                </div>

                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Judul Dokumen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200 @error('title') border-red-300 @enderror"
                                   placeholder="Masukkan judul dokumen yang jelas dan deskriptif">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200 @error('category') border-red-300 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="template" {{ old('category') == 'template' ? 'selected' : '' }}>Template</option>
                                <option value="manual" {{ old('category') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="form" {{ old('category') == 'form' ? 'selected' : '' }}>Form</option>
                                <option value="document" {{ old('category') == 'document' ? 'selected' : '' }}>Dokumen</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Status Dokumen
                            </label>
                            <div class="flex items-center space-x-4 mt-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" checked
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan dokumen (user dapat mendownload)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Deskripsi
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200 @error('description') border-red-300 @enderror"
                                  placeholder="Berikan deskripsi singkat mengenai dokumen ini (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload File <span class="text-red-500">*</span>
                        </label>

                        <!-- Drop Zone -->
                        <div class="relative"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="dragover = false; handleFiles($event.dataTransfer.files)">

                            <!-- File Input (hidden) -->
                            <input type="file" name="file" id="file" required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                                   class="hidden @error('file') border-red-300 @enderror"
                                   @change="handleFiles($event.target.files)">

                            <!-- Drop Zone Display -->
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors duration-200"
                                 :class="{'border-indigo-400 bg-indigo-50': dragover}"
                                 @click="document.getElementById('file').click()">

                                <!-- Preview when file selected -->
                                <div x-show="fileName" class="space-y-4">
                                    <div class="mx-auto w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900" x-text="fileName"></p>
                                        <p class="text-sm text-gray-500" x-text="fileSize"></p>
                                    </div>
                                    <button type="button" @click.stop="removeFile()"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus File
                                    </button>
                                </div>

                                <!-- Default state -->
                                <div x-show="!fileName" class="space-y-4">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">Klik untuk upload atau drag & drop file</p>
                                        <p class="text-sm text-gray-500 mt-2">Maksimal ukuran file 20MB</p>
                                        <p class="text-sm text-gray-500">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.documents.index') }}"
                           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
