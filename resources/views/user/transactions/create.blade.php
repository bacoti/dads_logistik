<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl px-8 py-6 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        Form {{ ucfirst($type) }} Material
                    </h1>
                    <p class="text-blue-100 text-lg">Sistem penerimaan material dengan data master terintegrasi</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Panel -->
            <div class="mb-8 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800 mb-2">Petunjuk Penggunaan</h3>
                        <p class="text-green-700 text-sm leading-relaxed">
                            Sistem ini menggunakan data master yang telah diatur oleh admin. Setelah memilih proyek dan sub proyek, 
                            sistem akan menampilkan daftar material yang tersedia. Anda hanya perlu mengisi jumlah material yang diterima 
                            menggunakan tombol <strong>(-)</strong> dan <strong>(+)</strong> atau input manual pada field quantity.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Display General Errors -->
                    @if($errors->has('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-700 font-medium">{{ $errors->first('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('user.transactions.store') }}" method="POST" enctype="multipart/form-data"
                          x-data="materialReceiptForm()" x-init="init()">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b border-gray-200 pb-2">
                                Informasi Dasar Transaksi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Tanggal Transaksi -->
                                <div>
                                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Transaksi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="transaction_date" id="transaction_date"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                    @error('transaction_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Vendor -->
                                <div>
                                    <label for="vendor_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Vendor <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="vendor_name" id="vendor_name"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('vendor_name') }}" placeholder="Masukkan nama vendor" required>
                                    @error('vendor_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Lokasi -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="location" id="location"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('location') }}" placeholder="Masukkan lokasi" required>
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Project -->
                                <div>
                                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Proyek Utama <span class="text-red-500">*</span>
                                    </label>
                                    <select name="project_id" id="project_id" x-model="selectedProject" @change="loadSubProjects()"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih Proyek</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }} ({{ $project->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sub Project -->
                                <div>
                                    <label for="sub_project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sub Proyek <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sub_project_id" id="sub_project_id" x-model="selectedSubProject" @change="loadMaterials()"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih Sub Proyek</option>
                                        <template x-for="subProject in subProjects" :key="subProject.id">
                                            <option x-bind:value="subProject.id" x-text="subProject.name"></option>
                                        </template>
                                    </select>
                                    @error('sub_project_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cluster & Site ID -->
                                <div>
                                    <label for="cluster" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cluster / Site ID
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="cluster" id="cluster"
                                               class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               value="{{ old('cluster') }}" placeholder="Cluster">
                                        <input type="text" name="site_id" id="site_id"
                                               class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               value="{{ old('site_id') }}" placeholder="Site ID">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Material Master List -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                    Daftar Material (Data Master)
                                </h3>
                                <div class="text-sm text-gray-600">
                                    <span x-text="getTotalSelectedMaterials()"></span> material dipilih
                                </div>
                            </div>

                            <!-- Loading State -->
                            <div x-show="isLoadingMaterials" class="text-center py-8">
                                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 bg-blue-100">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memuat daftar material...
                                </div>
                            </div>

                            <!-- Material tidak tersedia -->
                            <div x-show="!isLoadingMaterials && !selectedProject" class="text-center py-12">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada material</h3>
                                <p class="mt-1 text-sm text-gray-500">Silakan pilih proyek dan sub proyek terlebih dahulu untuk melihat daftar material.</p>
                            </div>

                            <!-- Materials List -->
                            <div x-show="!isLoadingMaterials && Object.keys(materialsByCategory).length > 0" class="space-y-6">
                                <template x-for="(materials, categoryName) in materialsByCategory" :key="categoryName">
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-800 mb-4 text-lg" x-text="categoryName"></h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <template x-for="material in materials" :key="material.id">
                                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div class="flex-1">
                                                            <h5 class="font-medium text-gray-900 text-sm" x-text="material.name"></h5>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Unit: <span x-text="material.unit" class="font-medium"></span>
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1" x-text="material.description" x-show="material.description"></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Quantity Controls -->
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-2">
                                                            <!-- Minus Button -->
                                                            <button type="button" 
                                                                    @click="updateQuantity(material.id, -1)"
                                                                    :disabled="getQuantity(material.id) <= 0"
                                                                    class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                </svg>
                                                            </button>

                                                            <!-- Quantity Input -->
                                                            <input type="number" 
                                                                   :value="getQuantity(material.id)"
                                                                   @input="setQuantity(material.id, $event.target.value)"
                                                                   min="0"
                                                                   class="w-16 text-center border border-gray-300 rounded-lg text-sm py-1 focus:border-blue-500 focus:ring-blue-500">

                                                            <!-- Plus Button -->
                                                            <button type="button" 
                                                                    @click="updateQuantity(material.id, 1)"
                                                                    class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <!-- Status Badge -->
                                                        <span x-show="getQuantity(material.id) > 0" 
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Dipilih
                                                        </span>
                                                    </div>

                                                    <!-- Hidden input for form submission -->
                                                    <input type="hidden" 
                                                           x-show="getQuantity(material.id) > 0"
                                                           :name="'materials[' + material.id + '][material_id]'" 
                                                           :value="material.id">
                                                    <input type="hidden" 
                                                           x-show="getQuantity(material.id) > 0"
                                                           :name="'materials[' + material.id + '][quantity]'" 
                                                           :value="getQuantity(material.id)">
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Upload Bukti & Keterangan -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b border-gray-200 pb-2">
                                Upload Dokumen & Keterangan
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Upload Bukti -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label for="proof_path" class="block text-sm font-semibold text-gray-800">
                                                Upload Bukti Transaksi
                                            </label>
                                            <p class="text-xs text-gray-600">PDF, JPG, PNG (Max: 2MB)</p>
                                        </div>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="file" name="proof_path" id="proof_path" accept=".pdf,.jpg,.jpeg,.png"
                                               class="w-full px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg text-sm
                                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                                                      file:text-sm file:font-medium file:bg-blue-600 file:text-white
                                                      hover:file:bg-blue-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                                      transition-all duration-200"
                                               onchange="updateFileName(this)">
                                        
                                        <div id="file-info" class="hidden mt-3 p-3 bg-white border border-blue-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-sm text-gray-700" id="selected-file-name"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('proof_path')
                                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            </div>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Keterangan -->
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label for="notes" class="block text-sm font-semibold text-gray-800">
                                                Keterangan & Catatan
                                            </label>
                                            <p class="text-xs text-gray-600">Tambahkan informasi tambahan jika diperlukan</p>
                                        </div>
                                    </div>
                                    
                                    <textarea name="notes" id="notes" rows="6"
                                              class="w-full px-4 py-3 border-2 border-green-200 rounded-lg text-sm
                                                     focus:border-green-500 focus:ring-2 focus:ring-green-200
                                                     resize-none transition-all duration-200"
                                              placeholder="Contoh:&#10;- Kondisi barang: Baik&#10;- Catatan khusus: -&#10;- Keterangan tambahan: Material diterima sesuai spesifikasi..."
                                              oninput="updateCharCount(this)">{{ old('notes') }}</textarea>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs text-gray-500" id="char-count">0 karakter</span>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Opsional
                                        </div>
                                    </div>
                                    
                                    @error('notes')
                                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div x-show="getTotalSelectedMaterials() > 0" class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">Ringkasan Material Dipilih</h4>
                            <div class="text-sm text-blue-700">
                                Total <span x-text="getTotalSelectedMaterials()" class="font-semibold"></span> jenis material dengan total 
                                <span x-text="getTotalQuantity()" class="font-semibold"></span> unit akan diinput.
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('user.dashboard') }}"
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                    :disabled="getTotalSelectedMaterials() === 0"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors">
                                <span x-text="getTotalSelectedMaterials() > 0 ? 'Simpan Transaksi (' + getTotalSelectedMaterials() + ' material)' : 'Pilih Material Terlebih Dahulu'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function materialReceiptForm() {
            return {
                selectedProject: '',
                selectedSubProject: '',
                subProjects: [],
                materialsByCategory: {},
                materialQuantities: {},
                isLoadingMaterials: false,

                init() {
                    // Load old values if validation failed
                    @if(old('project_id'))
                        this.selectedProject = '{{ old('project_id') }}';
                        this.loadSubProjects();
                    @endif
                },

                async loadSubProjects() {
                    if (this.selectedProject) {
                        try {
                            const response = await fetch(`/user/projects/${this.selectedProject}/sub-projects`);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            this.subProjects = await response.json();
                            this.selectedSubProject = '';
                            this.materialsByCategory = {};
                            this.materialQuantities = {};
                        } catch (error) {
                            console.error('Error loading sub projects:', error);
                            this.showErrorMessage('Gagal memuat sub proyek. Silakan refresh halaman dan coba lagi.');
                            this.subProjects = [];
                        }
                    } else {
                        this.subProjects = [];
                        this.materialsByCategory = {};
                        this.materialQuantities = {};
                    }
                },

                async loadMaterials() {
                    if (this.selectedProject && this.selectedSubProject) {
                        this.isLoadingMaterials = true;
                        try {
                            const response = await fetch(`/user/projects/${this.selectedProject}/sub-projects/${this.selectedSubProject}/materials`);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            this.materialsByCategory = await response.json();
                            this.materialQuantities = {};
                        } catch (error) {
                            console.error('Error loading materials:', error);
                            this.showErrorMessage('Gagal memuat material. Silakan refresh halaman dan coba lagi.');
                            this.materialsByCategory = {};
                        } finally {
                            this.isLoadingMaterials = false;
                        }
                    } else {
                        this.materialsByCategory = {};
                        this.materialQuantities = {};
                    }
                },

                showErrorMessage(message) {
                    // Create and show error notification
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    errorDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${message}
                        </div>
                    `;
                    
                    document.body.appendChild(errorDiv);
                    
                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        if (errorDiv.parentNode) {
                            errorDiv.parentNode.removeChild(errorDiv);
                        }
                    }, 5000);
                },

                getQuantity(materialId) {
                    return this.materialQuantities[materialId] || 0;
                },

                setQuantity(materialId, value) {
                    const quantity = parseInt(value) || 0;
                    if (quantity <= 0) {
                        delete this.materialQuantities[materialId];
                    } else {
                        this.materialQuantities[materialId] = quantity;
                    }
                },

                updateQuantity(materialId, change) {
                    const currentQty = this.getQuantity(materialId);
                    const newQty = Math.max(0, currentQty + change);
                    this.setQuantity(materialId, newQty);
                },

                getTotalSelectedMaterials() {
                    return Object.keys(this.materialQuantities).filter(id => this.materialQuantities[id] > 0).length;
                },

                getTotalQuantity() {
                    return Object.values(this.materialQuantities).reduce((total, qty) => total + qty, 0);
                }
            }
        }

        // File upload functionality
        function updateFileName(input) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('selected-file-name');
            
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                fileInfo.classList.remove('hidden');
            } else {
                fileInfo.classList.add('hidden');
            }
        }

        // Character count functionality
        function updateCharCount(textarea) {
            const charCount = document.getElementById('char-count');
            const length = textarea.value.length;
            charCount.textContent = length + ' karakter';
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Initialize character count on page load
        document.addEventListener('DOMContentLoaded', function() {
            const notesTextarea = document.getElementById('notes');
            if (notesTextarea) {
                updateCharCount(notesTextarea);
            }
        });
    </script>
</x-app-layout>
