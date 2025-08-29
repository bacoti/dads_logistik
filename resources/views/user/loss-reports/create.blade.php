<x-app-layout>
    <x-page-header
        title="Buat Laporan Kehilangan"
        subtitle="Laporkan kehilangan material proyek"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Kehilangan', 'url' => route('user.loss-reports.index')],
            ['title' => 'Buat Laporan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button
                variant="secondary"
                href="{{ route('user.loss-reports.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Formulir Laporan Kehilangan</h2>
                            <p class="text-red-100 mt-1">Lengkapi semua informasi yang diperlukan</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form action="{{ route('user.loss-reports.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf

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

                    <!-- Project Information -->
                    <div>
                        <x-section-header
                            title="Informasi Proyek"
                            subtitle="Pilih proyek dan sub proyek terkait kehilangan"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div x-data="{ projects: @js($projects), subProjects: [], selectedProject: '{{ old('project_id') }}' }" x-init="
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
                                    :value="old('project_id')"
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
                                    help="Pilih proyek tempat kejadian kehilangan" />

                                <div class="mt-6">
                                    <x-form-input
                                        type="select"
                                        label="Sub Proyek"
                                        name="sub_project_id"
                                        :value="old('sub_project_id')"
                                        placeholder="Pilih sub proyek"
                                        required
                                        help="Sub proyek terkait"
                                        x-bind:disabled="!selectedProject || subProjects.length === 0">
                                        <option value="">Pilih sub proyek</option>
                                        <template x-for="subProject in subProjects" :key="subProject.id">
                                            <option x-bind:value="subProject.id"
                                                    x-text="subProject.name"
                                                    x-bind:selected="subProject.id == '{{ old('sub_project_id') }}'">
                                            </option>
                                        </template>
                                    </x-form-input>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <x-form-input
                                    type="textarea"
                                    label="Lokasi Proyek"
                                    name="project_location"
                                    :value="old('project_location')"
                                    placeholder="Alamat lengkap lokasi proyek..."
                                    rows="3"
                                    required
                                    help="Alamat lengkap lokasi proyek" />

                                <x-form-input
                                    type="text"
                                    label="Cluster"
                                    name="cluster"
                                    :value="old('cluster')"
                                    placeholder="Nama cluster atau area spesifik..."
                                    required
                                    help="Cluster atau area spesifik tempat kehilangan" />
                            </div>
                        </div>
                    </div>

                    <!-- Loss Details -->
                    <div>
                        <x-section-header
                            title="Detail Kehilangan"
                            subtitle="Informasi spesifik tentang material yang hilang"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>'" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <x-form-input
                                type="date"
                                label="Tanggal Kehilangan"
                                name="loss_date"
                                :value="old('loss_date', date('Y-m-d'))"
                                required
                                help="Tanggal terjadinya kehilangan" />

                            <x-form-input
                                type="textarea"
                                label="Jenis Material yang Hilang"
                                name="material_type"
                                :value="old('material_type')"
                                placeholder="Sebutkan material yang hilang secara detail..."
                                rows="3"
                                required
                                help="Detail material yang hilang (jenis, spesifikasi, jumlah, dll)" />
                        </div>
                    </div>

                    <!-- Incident Report -->
                    <div>
                        <x-section-header
                            title="Kronologi Kejadian"
                            subtitle="Ceritakan secara detail bagaimana kehilangan terjadi"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />

                        <div class="space-y-6 mt-6">
                            <x-form-input
                                type="textarea"
                                label="Kronologi Kehilangan"
                                name="loss_chronology"
                                :value="old('loss_chronology')"
                                placeholder="Ceritakan secara detail kronologi kejadian kehilangan: kapan pertama kali menyadari, kondisi terakhir material, dugaan penyebab, dll..."
                                rows="6"
                                required
                                help="Ceritakan kronologi kejadian secara detail" />

                            <x-form-input
                                type="textarea"
                                label="Keterangan Tambahan"
                                name="additional_notes"
                                :value="old('additional_notes')"
                                placeholder="Tambahkan informasi lain yang relevan seperti: tindakan yang sudah dilakukan, saksi, kondisi keamanan, dll..."
                                rows="4"
                                help="Informasi tambahan yang relevan (opsional)" />
                        </div>
                    </div>

                    <!-- Supporting Document -->
                    <div>
                        <x-section-header
                            title="Dokumen Pendukung"
                            subtitle="Upload dokumen seperti BA kehilangan, surat kepolisian, atau foto"
                            :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13\'></path></svg>'" />

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Dokumen Pendukung
                                <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-red-400 transition-colors duration-200"
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
                                        <label for="supporting_document" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                            <span>Upload file</span>
                                            <input id="supporting_document" name="supporting_document" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" x-on:change="handleFileSelect($event)">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG hingga 10MB</p>
                                    <div x-show="fileName" x-text="fileName" class="text-sm text-green-600 font-medium mt-2"></div>
                                </div>
                            </div>
                            @error('supporting_document')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                <strong>Jika sudah dibuatkan BA dan surat kepolisian, mohon diupload.</strong><br>
                                Format yang didukung: PDF, DOC, DOCX, JPG, PNG
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Laporan akan berstatus "Pending" menunggu review admin
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <x-button
                                variant="secondary"
                                type="button"
                                href="{{ route('user.loss-reports.index') }}"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M6 18L18 6M6 6l12 12\'></path></svg>'">
                                Batal
                            </x-button>
                            <x-button
                                type="submit"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'">
                                Simpan Laporan
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
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
                        // Check if it's an allowed file
                        const allowedTypes = [
                            'application/pdf', 
                            'image/jpeg', 
                            'image/jpg', 
                            'image/png',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ];
                        if (allowedTypes.includes(file.type)) {
                            // Set the file to the input
                            const fileInput = document.getElementById('supporting_document');
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            fileInput.files = dataTransfer.files;
                            this.fileName = file.name;
                        } else {
                            alert('Hanya file PDF, DOC, DOCX, JPG, PNG yang diperbolehkan');
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
