<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit PO Transport</h2>
                            <p class="text-gray-600 mt-1">Perbarui informasi PO Transport</p>
                        </div>
                        <a href="{{ route('user.po-transports.show', $poTransport) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <!-- Information Card -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-amber-900 mb-2">Informasi Penting</h3>
                                <ul class="text-amber-800 space-y-1">
                                    <li class="flex items-start">
                                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        <span>Hanya PO Transport dengan status "Menunggu" yang dapat diedit</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        <span>Jika mengubah dokumen, file lama akan diganti dengan file baru</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="w-1.5 h-1.5 bg-amber-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        <span>Perubahan akan direview ulang oleh admin</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('user.po-transports.update', $poTransport) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- PO Number -->
                        <div>
                            <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor PO Transport 
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <input type="text" name="po_number" id="po_number" value="{{ old('po_number', $poTransport->po_number) }}" required
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('po_number') border-red-300 @enderror"
                                       placeholder="Contoh: PO-TRANSPORT-2025-001">
                            </div>
                            @error('po_number')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Current Document Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen Saat Ini
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $poTransport->document_name }}</p>
                                            <p class="text-sm text-gray-500">File Excel saat ini</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.po-transports.download', $poTransport) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload (Optional for Update) -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                                Ganti Dokumen Excel
                                <span class="text-gray-500 text-sm">(Opsional - kosongkan jika tidak ingin mengganti)</span>
                            </label>
                            <div class="mt-1">
                                <div class="flex justify-center px-6 pt-8 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-300 @error('document') border-red-300 @enderror">
                                    <div class="space-y-2 text-center">
                                        <div class="flex justify-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2">
                                                <span>Pilih file Excel baru</span>
                                                <input id="document" name="document" type="file" class="sr-only" 
                                                       accept=".xlsx,.xls"
                                                       onchange="showFileName(this)">
                                            </label>
                                            <p class="pl-1">atau drag dan drop di sini</p>
                                        </div>
                                        <p class="text-xs text-gray-500">Hanya file Excel (.xlsx, .xls) hingga 10MB</p>
                                        <div id="file-info" class="mt-4" style="display: none;">
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm font-medium text-green-800" id="file-name"></span>
                                                </div>
                                                <p class="text-xs text-green-600 mt-1" id="file-size"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('document')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan Tambahan
                            </label>
                            <div class="relative">
                                <textarea name="description" id="description" rows="4"
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror resize-none"
                                          placeholder="Tambahkan keterangan atau catatan khusus untuk PO Transport ini...">{{ old('description', $poTransport->description) }}</textarea>
                                <div class="absolute bottom-2 right-2 text-xs text-gray-400" id="char-count">
                                    {{ strlen($poTransport->description ?? '') }} / 1000
                                </div>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('user.po-transports.show', $poTransport) }}" 
                               class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="submit-btn"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all ease-in-out duration-150 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Perbarui PO Transport
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show file information when file is selected
        function showFileName(input) {
            const file = input.files[0];
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            if (file) {
                // Show file info
                fileInfo.style.display = 'block';
                fileName.textContent = file.name;
                
                // Format file size
                const size = file.size;
                let sizeText = '';
                if (size < 1024) {
                    sizeText = size + ' bytes';
                } else if (size < 1048576) {
                    sizeText = (size / 1024).toFixed(2) + ' KB';
                } else {
                    sizeText = (size / 1048576).toFixed(2) + ' MB';
                }
                fileSize.textContent = `Ukuran: ${sizeText}`;
                
                // Validate file size (10MB = 10485760 bytes)
                if (size > 10485760) {
                    fileInfo.className = 'bg-red-50 border border-red-200 rounded-lg p-3';
                    fileName.className = 'text-sm font-medium text-red-800';
                    fileSize.className = 'text-xs text-red-600 mt-1';
                    fileSize.textContent = `Ukuran: ${sizeText} - File terlalu besar!`;
                    document.getElementById('submit-btn').disabled = true;
                } else {
                    fileInfo.className = 'bg-green-50 border border-green-200 rounded-lg p-3';
                    fileName.className = 'text-sm font-medium text-green-800';
                    fileSize.className = 'text-xs text-green-600 mt-1';
                    document.getElementById('submit-btn').disabled = false;
                }
            } else {
                fileInfo.style.display = 'none';
                document.getElementById('submit-btn').disabled = false;
            }
        }

        // Character counter for description
        document.getElementById('description').addEventListener('input', function() {
            const charCount = document.getElementById('char-count');
            const length = this.value.length;
            charCount.textContent = `${length} / 1000`;
            
            if (length > 1000) {
                charCount.className = 'absolute bottom-2 right-2 text-xs text-red-500';
                this.className = this.className.replace('border-gray-300', 'border-red-300');
            } else {
                charCount.className = 'absolute bottom-2 right-2 text-xs text-gray-400';
                this.className = this.className.replace('border-red-300', 'border-gray-300');
            }
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('[id="document"]').closest('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const fileInput = document.getElementById('document');
                fileInput.files = files;
                showFileName(fileInput);
            }
        }

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const poNumber = document.getElementById('po_number').value.trim();
            
            if (!poNumber) {
                e.preventDefault();
                alert('Nomor PO wajib diisi!');
                document.getElementById('po_number').focus();
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memperbarui...
            `;
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>
