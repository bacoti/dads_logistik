<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Edit Pengajuan MFO</h2>
                            <p class="text-gray-600 mt-1">ID: #{{ $mfoRequest->id }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('user.mfo-requests.show', $mfoRequest) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Detail
                            </a>
                            <a href="{{ route('user.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ← Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Status Alert for Rejected Requests -->
                    @if($mfoRequest->status === 'rejected')
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium">Pengajuan Ditolak</h3>
                                    <div class="mt-2 text-sm">
                                        <p>Pengajuan ini telah ditolak. Anda dapat mengubah informasi dan mengirim ulang untuk review.</p>
                                        @if($mfoRequest->admin_notes)
                                            <p class="mt-2"><strong>Catatan Admin:</strong> {{ $mfoRequest->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('user.mfo-requests.update', $mfoRequest) }}" method="POST" enctype="multipart/form-data" id="mfoForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Informasi Proyek -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Informasi Proyek</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="project_id" class="block text-sm font-medium text-gray-700">Proyek <span class="text-red-500">*</span></label>
                                        <select name="project_id" id="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Pilih Proyek</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ (old('project_id', $mfoRequest->project_id) == $project->id) ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="sub_project_id" class="block text-sm font-medium text-gray-700">Sub Proyek <span class="text-red-500">*</span></label>
                                        <select name="sub_project_id" id="sub_project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Pilih Sub Proyek</option>
                                            @if($mfoRequest->subProject)
                                                <option value="{{ $mfoRequest->subProject->id }}" selected>{{ $mfoRequest->subProject->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="project_location" class="block text-sm font-medium text-gray-700">Lokasi Proyek <span class="text-red-500">*</span></label>
                                        <input type="text" name="project_location" id="project_location" value="{{ old('project_location', $mfoRequest->project_location) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                               placeholder="Masukkan lokasi proyek" required>
                                    </div>
                                    
                                    <div>
                                        <label for="cluster" class="block text-sm font-medium text-gray-700">Cluster</label>
                                        <input type="text" name="cluster" id="cluster" value="{{ old('cluster', $mfoRequest->cluster) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                               placeholder="Masukkan cluster (opsional)">
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pengajuan -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Informasi Pengajuan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="date_needed" class="block text-sm font-medium text-gray-700">Tanggal Dibutuhkan <span class="text-red-500">*</span></label>
                                        <input type="text" name="date_needed" id="date_needed" value="{{ old('date_needed', $mfoRequest->date_needed) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 flatpickr" 
                                               placeholder="Pilih tanggal" required>
                                    </div>
                                    
                                    <div>
                                        <label for="purpose" class="block text-sm font-medium text-gray-700">Tujuan Penggunaan <span class="text-red-500">*</span></label>
                                        <input type="text" name="purpose" id="purpose" value="{{ old('purpose', $mfoRequest->purpose) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                               placeholder="Masukkan tujuan penggunaan" required>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                              placeholder="Masukkan deskripsi tambahan (opsional)">{{ old('description', $mfoRequest->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Dokumen Pendukung -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Dokumen Pendukung</h3>
                                
                                @if($mfoRequest->document_path)
                                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <h4 class="text-sm font-medium text-blue-800 mb-2">Dokumen Saat Ini:</h4>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="h-6 w-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-blue-800">{{ basename($mfoRequest->document_path) }}</span>
                                            </div>
                                            <a href="{{ route('user.mfo-requests.download', $mfoRequest) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Download
                                            </a>
                                        </div>
                                        <p class="text-xs text-blue-600 mt-2">Upload file baru untuk mengganti dokumen yang ada</p>
                                    </div>
                                @endif
                                
                                <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors cursor-pointer" id="uploadArea">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600">
                                            <button type="button" class="font-medium text-indigo-600 hover:text-indigo-500">Upload file baru</button>
                                            atau drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG hingga 5MB</p>
                                    </div>
                                    <input type="file" name="document" id="document" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                                <div id="filePreview" class="mt-4 hidden">
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex items-center">
                                            <svg class="h-6 w-6 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                            </svg>
                                            <span id="fileName" class="text-sm text-gray-900"></span>
                                        </div>
                                        <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-6">
                                <button type="button" id="resetBtn" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Reset Form
                                </button>
                                
                                @if($mfoRequest->status === 'rejected')
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Kirim Ulang Pengajuan
                                    </button>
                                @else
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Update Pengajuan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .upload-area.dragover {
        border-color: #4f46e5;
        background-color: #eff6ff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize flatpickr
    flatpickr("#date_needed", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    // Project change handler
    document.getElementById('project_id').addEventListener('change', function() {
        const projectId = this.value;
        const subProjectSelect = document.getElementById('sub_project_id');
        
        subProjectSelect.innerHTML = '<option value="">Pilih Sub Proyek</option>';
        
        if (projectId) {
            fetch(`{{ url('/user/mfo-requests/ajax/sub-projects') }}/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subProject => {
                        const option = document.createElement('option');
                        option.value = subProject.id;
                        option.textContent = subProject.name;
                        if (subProject.id == '{{ old("sub_project_id", $mfoRequest->sub_project_id) }}') {
                            option.selected = true;
                        }
                        subProjectSelect.appendChild(option);
                    });
                });
        }
    });

    // Trigger project change on page load to populate sub projects
    const projectSelect = document.getElementById('project_id');
    if (projectSelect.value) {
        projectSelect.dispatchEvent(new Event('change'));
    }

    // File upload handlers
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('document');
    const filePreview = document.getElementById('filePreview');

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFilePreview(files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showFilePreview(e.target.files[0]);
        }
    });

    function showFilePreview(file) {
        document.getElementById('fileName').textContent = file.name;
        filePreview.classList.remove('hidden');
    }

    // Reset form
    document.getElementById('resetBtn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin mereset form?')) {
            document.getElementById('mfoForm').reset();
            filePreview.classList.add('hidden');
            document.getElementById('sub_project_id').innerHTML = '<option value="">Pilih Sub Proyek</option>';
        }
    });

    // Form validation
    document.getElementById('mfoForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
    });
});

function removeFile() {
    document.getElementById('document').value = '';
    document.getElementById('filePreview').classList.add('hidden');
}
</script>
@endpush
</x-app-layout>
