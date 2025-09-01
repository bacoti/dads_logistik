<x-admin-layout>
    <x-page-header
        title="Edit Laporan Bulanan"
        subtitle="Edit informasi laporan bulanan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Bulanan', 'url' => route('admin.monthly-reports.index')],
            ['title' => 'Edit Laporan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button
                variant="secondary"
                href="{{ route('admin.monthly-reports.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('admin.monthly-reports.update', $monthlyReport) }}"
              enctype="multipart/form-data"
              class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <x-section-header
                        title="Informasi Dasar"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>'" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="report_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Laporan *
                            </label>
                            <input type="date"
                                   id="report_date"
                                   name="report_date"
                                   value="{{ old('report_date', $monthlyReport->report_date->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->has('report_date') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('report_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="report_period" class="block text-sm font-medium text-gray-700 mb-2">
                                Periode Laporan *
                            </label>
                            <input type="text"
                                   id="report_period"
                                   name="report_period"
                                   value="{{ old('report_period', $monthlyReport->report_period) }}"
                                   required
                                   placeholder="Contoh: September 2024"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->has('report_period') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                            @error('report_period')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Project Information -->
                <div>
                    <x-section-header
                        title="Informasi Proyek"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Proyek *
                            </label>
                            <select id="project_id"
                                    name="project_id"
                                    required
                                    onchange="updateSubProjects()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->has('project_id') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                <option value="">Pilih Proyek</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}"
                                            data-sub-projects="{{ json_encode($project->subProjects) }}"
                                            {{ old('project_id', $monthlyReport->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Proyek *
                            </label>
                            <select id="sub_project_id"
                                    name="sub_project_id"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->has('sub_project_id') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                                <option value="">Pilih Sub Proyek</option>
                            </select>
                            @error('sub_project_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="project_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Proyek *
                        </label>
                        <input type="text"
                               id="project_location"
                               name="project_location"
                               value="{{ old('project_location', $monthlyReport->project_location) }}"
                               required
                               placeholder="Masukkan lokasi proyek"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->has('project_location') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                        @error('project_location')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <x-section-header
                        title="Catatan"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />

                    <div class="mt-6">
                        <textarea id="notes"
                                  name="notes"
                                  rows="4"
                                  placeholder="Catatan tambahan untuk laporan bulanan (opsional)"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none {{ $errors->has('notes') ? 'border-red-500 bg-red-50' : 'bg-white' }}">{{ old('notes', $monthlyReport->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- File Upload -->
                <div>
                    <x-section-header
                        title="File Excel"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z\'></path></svg>'" />

                    <div class="mt-6">
                        @if($monthlyReport->excel_file_path)
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-green-800">File saat ini:</p>
                                            <p class="text-sm text-green-600">{{ basename($monthlyReport->excel_file_path) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.monthly-reports.download', $monthlyReport) }}"
                                       class="text-green-600 hover:text-green-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <label class="block border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-red-400 transition-colors cursor-pointer">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-lg font-medium text-gray-700 mb-2">
                                {{ $monthlyReport->excel_file_path ? 'Ganti File Excel' : 'Upload File Excel' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Klik untuk memilih file atau drag & drop
                            </p>
                            <p class="text-xs text-gray-400 mt-2">
                                Format yang didukung: .xlsx, .xls, .csv (Max: 10MB)
                            </p>
                            <input type="file"
                                   name="excel_file"
                                   accept=".xlsx,.xls,.csv"
                                   class="hidden">
                        </label>
                        @error('excel_file')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <x-button
                        variant="secondary"
                        href="{{ route('admin.monthly-reports.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                        Batal
                    </x-button>

                    <x-button
                        type="submit"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'"
                        class="bg-red-600 hover:bg-red-700 text-white">
                        Update Laporan
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Initialize sub-projects on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSubProjects();
        });

        function updateSubProjects() {
            const projectSelect = document.getElementById('project_id');
            const subProjectSelect = document.getElementById('sub_project_id');
            const selectedSubProjectId = '{{ old("sub_project_id", $monthlyReport->sub_project_id) }}';

            // Clear existing options
            subProjectSelect.innerHTML = '<option value="">Pilih Sub Proyek</option>';

            if (projectSelect.value) {
                const selectedOption = projectSelect.options[projectSelect.selectedIndex];
                const subProjects = JSON.parse(selectedOption.getAttribute('data-sub-projects') || '[]');

                subProjects.forEach(subProject => {
                    const option = document.createElement('option');
                    option.value = subProject.id;
                    option.textContent = subProject.name;
                    if (subProject.id == selectedSubProjectId) {
                        option.selected = true;
                    }
                    subProjectSelect.appendChild(option);
                });
            }
        }
    </script>
</x-admin-layout>
