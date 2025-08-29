<x-app-layout>
    <x-page-header 
        title="Edit Laporan Bulanan" 
        subtitle="Perbarui informasi laporan bulanan Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Bulanan', 'url' => route('user.monthly-reports.index')],
            ['title' => 'Edit Laporan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button 
                variant="secondary"
                href="{{ route('user.monthly-reports.show', $report) }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="min-h-screen bg-gray-50 py-8" x-data="reportForm()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Edit Laporan Bulanan</h2>
                            <p class="text-indigo-100 mt-1">Perbarui informasi laporan {{ $report->formatted_period }}</p>
                        </div>
                    </div>
                </div>

                <!-- Alert for edit restrictions -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 m-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800 font-medium">
                                <strong>Perhatian:</strong> Laporan hanya dapat diedit selama status masih "Pending". Setelah direview admin, laporan tidak dapat diubah.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form action="{{ route('user.monthly-reports.update', $report) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</h4>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Basic Information -->
                    <div>
                        <x-section-header 
                            title="Informasi Dasar"
                            subtitle="Perbarui periode dan tanggal laporan"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>'" />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <x-form-input 
                                type="date"
                                label="Tanggal Laporan"
                                name="report_date" 
                                :value="old('report_date', $report->report_date->format('Y-m-d'))"
                                required
                                help="Tanggal pembuatan laporan" />

                            <x-form-input 
                                type="select"
                                label="Periode Laporan"
                                name="report_period"
                                :options="[
                                    'January' => 'Januari',
                                    'February' => 'Februari', 
                                    'March' => 'Maret',
                                    'April' => 'April',
                                    'May' => 'Mei',
                                    'June' => 'Juni',
                                    'July' => 'Juli',
                                    'August' => 'Agustus',
                                    'September' => 'September',
                                    'October' => 'Oktober',
                                    'November' => 'November',
                                    'December' => 'Desember'
                                ]"
                                :value="old('report_period', $report->report_period)"
                                placeholder="Pilih bulan laporan"
                                required
                                help="Bulan yang dilaporkan" />
                        </div>
                    </div>

                    <!-- Project Information -->
                    <div>
                        <x-section-header 
                            title="Informasi Proyek"
                            subtitle="Perbarui proyek dan sub proyek"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'" />
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div x-data="{ projects: @js($projects), subProjects: [], selectedProject: '{{ old('project_id', $report->project_id) }}' }" x-init="
                                if (selectedProject) {
                                    const project = projects.find(p => p.id == selectedProject);
                                    if (project) subProjects = project.sub_projects;
                                }
                            ">
                                <x-form-input 
                                    type="select"
                                    label="Proyek"
                                    name="project_id"
                                    :options="$projects->pluck('name', 'id')->toArray()"
                                    :value="old('project_id', $report->project_id)"
                                    placeholder="Pilih proyek"
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
                                    help="Pilih proyek yang akan dilaporkan" />

                                <div class="mt-6">
                                    <x-form-input 
                                        type="select"
                                        label="Sub Proyek"
                                        name="sub_project_id"
                                        :value="old('sub_project_id', $report->sub_project_id)"
                                        placeholder="Pilih sub proyek"
                                        required
                                        help="Sub proyek terkait"
                                        x-bind:disabled="!selectedProject || subProjects.length === 0">
                                        <option value="">Pilih sub proyek</option>
                                        <template x-for="subProject in subProjects" :key="subProject.id">
                                            <option x-bind:value="subProject.id" 
                                                    x-text="subProject.name"
                                                    x-bind:selected="subProject.id == '{{ old('sub_project_id', $report->sub_project_id) }}'">
                                            </option>
                                        </template>
                                    </x-form-input>
                                </div>
                            </div>

                            <x-form-input 
                                type="textarea"
                                label="Lokasi Proyek"
                                name="project_location"
                                :value="old('project_location', $report->project_location)"
                                placeholder="Alamat lengkap lokasi proyek..."
                                rows="6"
                                required
                                help="Alamat lengkap lokasi proyek yang dilaporkan" />
                        </div>
                    </div>

                    <!-- Report Details -->
                    <div>
                        <x-section-header 
                            title="Detail Laporan"
                            subtitle="Perbarui catatan dan file excel laporan"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />
                        
                        <div class="space-y-6 mt-6">
                            <x-form-input 
                                type="textarea"
                                label="Catatan Laporan"
                                name="notes"
                                :value="old('notes', $report->notes)"
                                placeholder="Tulis catatan tambahan, ringkasan aktivitas, masalah yang ditemui, atau informasi penting lainnya..."
                                rows="6"
                                help="Catatan tambahan atau ringkasan kegiatan bulan ini" />

                            <!-- Current File Info -->
                            @if($report->excel_file_path)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 rounded-lg p-2 mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-green-800">File Saat Ini</p>
                                            <p class="text-sm text-green-600">{{ basename($report->excel_file_path) }}</p>
                                        </div>
                                        <a href="{{ route('user.monthly-reports.download', $report) }}" 
                                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New File -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $report->excel_file_path ? 'Upload File Baru (Opsional)' : 'File Excel Laporan' }} 
                                    @if(!$report->excel_file_path) <span class="text-red-500">*</span> @endif
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-indigo-400 transition-colors duration-200"
                                     x-data="fileUpload()"
                                     x-on:drop.prevent="handleDrop($event)"
                                     x-on:dragover.prevent
                                     x-on:dragenter.prevent>
                                    <div class="space-y-1 text-center">
                                        <div class="mx-auto h-12 w-12 text-gray-400">
                                            <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="excel_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload file</span>
                                                <input id="excel_file" name="excel_file" type="file" class="sr-only" accept=".xlsx,.xls" @if(!$report->excel_file_path) required @endif x-on:change="handleFileSelect($event)">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">Excel file (.xlsx, .xls) hingga 10MB</p>
                                        <div x-show="fileName" x-text="fileName" class="text-sm text-indigo-600 font-medium mt-2"></div>
                                    </div>
                                </div>
                                @error('excel_file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    @if($report->excel_file_path)
                                        Upload file baru untuk mengganti file yang ada, atau kosongkan untuk tetap menggunakan file lama.
                                    @else
                                        Upload file excel yang berisi detail laporan bulanan Anda.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status akan tetap "Pending" setelah update
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <x-button 
                                variant="secondary"
                                type="button"
                                href="{{ route('user.monthly-reports.show', $report) }}"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M6 18L18 6M6 6l12 12\'></path></svg>'">
                                Batal
                            </x-button>
                            <x-button 
                                type="submit"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white">
                                Update Laporan
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function reportForm() {
            return {
                // Form data and methods can be added here
            }
        }

        function fileUpload() {
            return {
                fileName: '',
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                    }
                },
                handleDrop(event) {
                    const files = event.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        // Check if it's an Excel file
                        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
                        if (allowedTypes.includes(file.type)) {
                            // Set the file to the input
                            const fileInput = document.getElementById('excel_file');
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            fileInput.files = dataTransfer.files;
                            this.fileName = file.name;
                        } else {
                            alert('Hanya file Excel (.xlsx, .xls) yang diperbolehkan');
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
