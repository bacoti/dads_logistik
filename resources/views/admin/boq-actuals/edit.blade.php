<x-admin-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit BOQ Actual</h1>
                        <p class="text-gray-600 mt-2">Edit data BOQ Actual untuk material yang telah digunakan</p>
                    </div>
                    <a href="{{ route('admin.boq-actuals.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Main Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form method="POST" action="{{ route('admin.boq-actuals.update', $boqActual) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Project Selection -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Project <span class="text-red-500">*</span>
                            </label>
                            <select name="project_id" id="project_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">Pilih Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $boqActual->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub Project Selection -->
                        <div>
                            <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub Project <span class="text-red-500">*</span>
                            </label>
                            <select name="sub_project_id" id="sub_project_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">Pilih Sub Project</option>
                                @foreach($projects as $project)
                                    @foreach($project->subProjects as $subProject)
                                        <option value="{{ $subProject->id }}" {{ old('sub_project_id', $boqActual->sub_project_id) == $subProject->id ? 'selected' : '' }}>
                                            {{ $subProject->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('sub_project_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Material Selection -->
                        <div>
                            <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Material <span class="text-red-500">*</span>
                            </label>
                            <select name="material_id" id="material_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="">Pilih Material</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" {{ old('material_id', $boqActual->material_id) == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }} ({{ $material->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cluster -->
                        <div>
                            <label for="cluster" class="block text-sm font-medium text-gray-700 mb-2">
                                Cluster <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="cluster" id="cluster" value="{{ old('cluster', $boqActual->cluster) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Contoh: Cluster A" required>
                            @error('cluster')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DN Number -->
                        <div>
                            <label for="dn_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor DN <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dn_number" id="dn_number" value="{{ old('dn_number', $boqActual->dn_number) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Contoh: DN-001/2025" required>
                            @error('dn_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actual Quantity -->
                        <div>
                            <label for="actual_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity Actual <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <input type="number" name="actual_quantity" id="actual_quantity" 
                                       value="{{ old('actual_quantity', $boqActual->actual_quantity) }}" step="0.01" min="0"
                                       class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2" placeholder="0.00" required>
                                <span class="inline-flex items-center px-3 border border-l-0 border-gray-300 rounded-r-lg bg-gray-50 text-gray-500 text-sm">
                                    {{ $boqActual->material->unit }}
                                </span>
                            </div>
                            @error('actual_quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Usage Date -->
                        <div>
                            <label for="usage_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pemakaian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="usage_date" id="usage_date" 
                                   value="{{ old('usage_date', $boqActual->usage_date->format('Y-m-d')) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                            @error('usage_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                                  placeholder="Catatan tambahan (opsional)">{{ old('notes', $boqActual->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.boq-actuals.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Update BOQ Actual
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>