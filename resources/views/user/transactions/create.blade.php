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
                                    <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Vendor <span class="text-red-500">*</span>
                                    </label>
                                    <select name="vendor_id" id="vendor_id"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Upload Bukti -->
                            <div>
                                <label for="proof_path" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti (PDF, JPG, PNG)
                                </label>
                                <input type="file" name="proof_path" id="proof_path" accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('proof_path')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan Umum
                                </label>
                                <textarea name="notes" id="notes" rows="4"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Tambahkan keterangan atau catatan tambahan...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                            this.subProjects = await response.json();
                            this.selectedSubProject = '';
                            this.materialsByCategory = {};
                            this.materialQuantities = {};
                        } catch (error) {
                            console.error('Error loading sub projects:', error);
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
                            this.materialsByCategory = await response.json();
                            this.materialQuantities = {};
                        } catch (error) {
                            console.error('Error loading materials:', error);
                            this.materialsByCategory = {};
                        } finally {
                            this.isLoadingMaterials = false;
                        }
                    } else {
                        this.materialsByCategory = {};
                        this.materialQuantities = {};
                    }
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
    </script>
</x-app-layout>
