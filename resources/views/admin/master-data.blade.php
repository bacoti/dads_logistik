<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Master') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid Layout Responsif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Sisi Kiri (1/3 lebar) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Kartu Daftar Vendor -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Vendor</h3>
                            
                            <!-- Input Pencarian Vendor -->
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="searchVendor"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Cari vendor...">
                            </div>
                            
                            <!-- Daftar Vendor -->
                            <div id="vendorList" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                                <!-- Items akan dimuat dengan JavaScript -->
                            </div>
                            
                            <!-- Input Tambah Vendor Baru -->
                            <div class="border-t pt-4">
                                <div class="flex space-x-2">
                                    <input type="text" 
                                           id="newVendor"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Nama Vendor Baru">
                                    <button type="button" 
                                            id="addVendorBtn"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Proyek Utama -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Proyek Utama</h3>
                            
                            <!-- Input Pencarian Proyek -->
                            <div class="relative mb-4">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="searchProject"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Cari proyek...">
                            </div>
                            
                            <!-- Daftar Proyek -->
                            <div id="projectList" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                                <!-- Items akan dimuat dengan JavaScript -->
                            </div>
                            
                            <!-- Input Tambah Proyek Baru -->
                            <div class="border-t pt-4">
                                <div class="flex space-x-2">
                                    <input type="text" 
                                           id="newProject"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Nama Proyek Baru">
                                    <button type="button" 
                                            id="addProjectBtn"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sisi Kanan (2/3 lebar) -->
                <div class="lg:col-span-2">
                    
                    <!-- Kartu Sub Proyek & Material (Sticky) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:sticky lg:top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sub Proyek & Material</h3>
                            
                            <!-- Area Konten Dinamis -->
                            <div id="subProjectArea">
                                <!-- State Awal -->
                                <div id="initialState" class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Pilih sebuah proyek utama untuk memulai</p>
                                    <p class="text-gray-500">Klik salah satu proyek dari daftar di sebelah kiri untuk mengelola sub proyek dan materialnya.</p>
                                </div>

                                <!-- State Aktif (Hidden by default) -->
                                <div id="activeState" class="hidden">
                                    <div class="mb-6">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2M5 21h2m0 0h2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900" id="selectedProjectName">Proyek Terpilih</h4>
                                                <p class="text-sm text-gray-600">Kelola sub proyek dan material</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Navigation -->
                                    <div class="border-b border-gray-200 mb-6">
                                        <nav class="-mb-px flex space-x-8">
                                            <button class="tab-btn active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-tab="subprojects">
                                                Sub Proyek
                                            </button>
                                            <button class="tab-btn py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="materials">
                                                Material
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Tab Content: Sub Proyek -->
                                    <div id="subprojectsTab" class="tab-content">
                                        <div class="space-y-4">
                                            <!-- Pencarian Sub Proyek -->
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </div>
                                                <input type="text" 
                                                       id="searchSubProject"
                                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                       placeholder="Cari sub proyek...">
                                            </div>

                                            <!-- Daftar Sub Proyek -->
                                            <div id="subProjectList" class="space-y-2 max-h-64 overflow-y-auto">
                                                <!-- Items akan dimuat dengan JavaScript -->
                                            </div>

                                            <!-- Input Tambah Sub Proyek Baru -->
                                            <div class="border-t pt-4">
                                                <div class="flex space-x-2">
                                                    <input type="text" 
                                                           id="newSubProject"
                                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                           placeholder="Nama Sub Proyek Baru">
                                                    <button type="button" 
                                                            id="addSubProjectBtn"
                                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-medium">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Content: Material -->
                                    <div id="materialsTab" class="tab-content hidden">
                                        <div class="space-y-4">
                                            <!-- Material Sub-Tab Navigation -->
                                            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                                                <button id="categoryTabBtn" class="material-tab-btn active flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors bg-purple-600 text-white">
                                                    <i class="fas fa-tags mr-2"></i>Kategori
                                                </button>
                                                <button id="materialTabBtn" class="material-tab-btn flex-1 py-2 px-3 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-800">
                                                    <i class="fas fa-box mr-2"></i>Material
                                                </button>
                                            </div>

                                            <!-- Category Management Panel -->
                                            <div id="categoryPanel" class="material-panel">
                                                <!-- Search Category -->
                                                <div class="relative mb-4">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" id="searchCategory" 
                                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                                           placeholder="Cari kategori...">
                                                </div>

                                                <!-- Add Category Form -->
                                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                                    <h4 class="text-sm font-medium text-purple-700 mb-3">
                                                        <i class="fas fa-plus-circle mr-2"></i>Tambah Kategori Baru
                                                    </h4>
                                                    <div class="flex space-x-2">
                                                        <input type="text" id="newCategory" 
                                                               class="flex-1 px-3 py-2 text-sm border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                               placeholder="Nama kategori material...">
                                                        <button id="addCategoryBtn" 
                                                                class="bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 text-sm font-medium transition-colors whitespace-nowrap">
                                                            <i class="fas fa-plus mr-2"></i>Tambah
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Category List -->
                                                <div id="categoryList" class="space-y-2 max-h-64 overflow-y-auto">
                                                    <p class="text-gray-500 text-sm text-center py-8">
                                                        <i class="fas fa-spinner fa-spin mr-2"></i>Memuat kategori...
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Material Management Panel -->
                                            <div id="materialPanel" class="material-panel hidden">
                                                <!-- Search Material -->
                                                <div class="relative mb-4">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" id="searchMaterial"
                                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                                           placeholder="Cari material...">
                                                </div>

                                                <!-- Add Material Form -->
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                                    <h4 class="text-sm font-medium text-green-700 mb-3">
                                                        <i class="fas fa-plus-circle mr-2"></i>Tambah Material Baru
                                                    </h4>
                                                    <div class="space-y-3">
                                                        <select id="materialCategory" 
                                                                class="w-full px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                            <option value="">Pilih Kategori Material...</option>
                                                        </select>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                            <input type="text" id="newMaterialName" 
                                                                   class="px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                   placeholder="Nama material...">
                                                            <input type="text" id="newMaterialUnit" 
                                                                   class="px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                   placeholder="Satuan (kg, pcs, m, dll)...">
                                                        </div>
                                                        <button id="addMaterialBtn" 
                                                                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">
                                                            <i class="fas fa-plus mr-2"></i>Tambah Material
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Material List -->
                                                <div id="materialList" class="space-y-2 max-h-64 overflow-y-auto">
                                                    <p class="text-gray-500 text-sm text-center py-8">
                                                        <i class="fas fa-info-circle mr-2"></i>Pilih kategori terlebih dahulu, lalu tambahkan material
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Apakah Anda yakin?</h3>
                <p class="text-sm text-gray-500 mb-6" id="deleteMessage">
                    Item ini akan dihapus secara permanen dan tidak dapat dikembalikan.
                </p>
                <div class="flex justify-center space-x-3">
                    <button id="cancelDeleteBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Interaktivitas -->
    <script>
        // Global Variables
        let vendors = [];
        let projects = [];
        let subProjects = [];
        let materials = [];
        let categories = [];
        let selectedProjectId = null;
        let selectedSubProjectId = null;  // New: Track selected sub project
        let deleteTarget = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Master Data page loaded');
            
            // Check if required elements exist
            const requiredElements = ['vendorList', 'projectList', 'newVendor', 'newProject'];
            const missingElements = [];
            
            requiredElements.forEach(id => {
                if (!document.getElementById(id)) {
                    missingElements.push(id);
                }
            });
            
            if (missingElements.length > 0) {
                console.error('Missing required elements:', missingElements);
                showNotification('Ada masalah dengan tampilan halaman', 'error');
                return;
            }
            
            loadInitialData();
            bindEvents();
        });

        // Load Initial Data
        function loadInitialData() {
            console.log('Starting loadInitialData...');
            showLoadingState();
            
            console.log('Making request to /api/master-data/init');
            fetch('/api/master-data/init', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                console.log('Response headers:', [...response.headers.entries()]);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Get the raw text first to debug
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    
                    if (!text.trim()) {
                        throw new Error('Empty response from server');
                    }
                    
                    try {
                        return JSON.parse(text);
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error(`Invalid JSON response: ${text.substring(0, 200)}...`);
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                
                if (data.success) {
                    vendors = data.vendors || [];
                    projects = data.projects || [];
                    // Categories are now loaded per sub-project
                    console.log(`Loaded ${vendors.length} vendors and ${projects.length} projects`);
                    
                    renderVendors();
                    renderProjects();
                    hideLoadingState();
                    console.log('Data loaded successfully');
                } else {
                    throw new Error(data.message || 'Failed to load data');
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                showNotification('Gagal memuat data awal: ' + error.message, 'error');
                hideLoadingState();
                
                // Show fallback empty state
                vendors = [];
                projects = [];
                renderVendors();
                renderProjects();
            });
        }

        function showLoadingState() {
            // Show loading indicators
            const lists = ['vendorList', 'projectList'];
            lists.forEach(listId => {
                const element = document.getElementById(listId);
                if (element) {
                    element.innerHTML = `
                        <div class="flex items-center justify-center py-8">
                            <i class="fas fa-spinner fa-spin text-gray-400 text-xl mr-2"></i>
                            <span class="text-gray-500">Memuat data...</span>
                        </div>
                    `;
                }
            });
        }

        function hideLoadingState() {
            // Loading states will be hidden when data is rendered
            console.log('Loading state hidden');
        }

        // Bind Events
        function bindEvents() {
            console.log('Binding events...');
            
            // Search functionality
            const searchElements = [
                { id: 'searchVendor', handler: filterVendors },
                { id: 'searchProject', handler: filterProjects },
                { id: 'searchSubProject', handler: filterSubProjects },
                { id: 'searchMaterial', handler: filterMaterials },
                { id: 'searchCategory', handler: filterCategories }
            ];
            
            searchElements.forEach(({ id, handler }) => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', handler);
                    console.log(`Bound search handler for ${id}`);
                } else {
                    console.warn(`Search element ${id} not found`);
                }
            });

            // Add buttons
            const buttonElements = [
                { id: 'addVendorBtn', handler: addVendor },
                { id: 'addProjectBtn', handler: addProject },
                { id: 'addSubProjectBtn', handler: addSubProject },
                { id: 'addMaterialBtn', handler: addMaterial },
                { id: 'addCategoryBtn', handler: addCategory }
            ];
            
            buttonElements.forEach(({ id, handler }) => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('click', handler);
                    console.log(`Bound click handler for ${id}`);
                } else {
                    console.warn(`Button element ${id} not found`);
                }
            });

            // Tab switching
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', switchTab);
            });
            
            // Material sub-tab switching
            document.querySelectorAll('.material-tab-btn').forEach(btn => {
                btn.addEventListener('click', switchMaterialTab);
            });

            // Modal events
            const modalElements = [
                { id: 'cancelDeleteBtn', handler: hideDeleteModal },
                { id: 'confirmDeleteBtn', handler: confirmDelete }
            ];
            
            modalElements.forEach(({ id, handler }) => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('click', handler);
                } else {
                    console.warn(`Modal element ${id} not found`);
                }
            });

            // Enter key listeners
            const enterKeyElements = [
                { id: 'newVendor', handler: () => addVendor() },
                { id: 'newProject', handler: () => addProject() },
                { id: 'newSubProject', handler: () => addSubProject() },
                { id: 'newCategory', handler: () => addCategory() }
            ];
            
            enterKeyElements.forEach(({ id, handler }) => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') handler();
                    });
                } else {
                    console.warn(`Enter key element ${id} not found`);
                }
            });
            
            // Material form enter key support
            const materialNameEl = document.getElementById('newMaterialName');
            const materialUnitEl = document.getElementById('newMaterialUnit');
            
            if (materialNameEl) {
                materialNameEl.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        if (materialUnitEl) materialUnitEl.focus();
                    }
                });
            }
            
            if (materialUnitEl) {
                materialUnitEl.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') addMaterial();
                });
            }
            
            console.log('Event binding completed');
        }

        // Render Functions with better error handling
        function renderVendors(filteredVendors = vendors) {
            const list = document.getElementById('vendorList');
            if (!list) {
                console.error('Vendor list element not found');
                return;
            }

            if (filteredVendors.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Belum ada vendor</p>';
                return;
            }

            list.innerHTML = filteredVendors.map(vendor => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="text-sm font-medium text-gray-900" title="${vendor.name}">${vendor.name}</span>
                    <button onclick="showDeleteModal('vendor', ${vendor.id}, '${vendor.name.replace(/'/g, '&apos;')}')" 
                            class="text-red-500 hover:text-red-700 p-1 rounded"
                            title="Hapus vendor ${vendor.name}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        function renderProjects(filteredProjects = projects) {
            const list = document.getElementById('projectList');
            if (!list) {
                console.error('Project list element not found');
                return;
            }

            if (filteredProjects.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Belum ada proyek</p>';
                return;
            }

            list.innerHTML = filteredProjects.map(project => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer ${project.id === selectedProjectId ? 'bg-blue-50 border-2 border-blue-200' : ''}" 
                     onclick="selectProject(${project.id})"
                     title="Klik untuk memilih proyek ${project.name}">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-900">${project.name}</span>
                        <span class="text-xs text-gray-500">${project.code || ''}</span>
                    </div>
                    <button onclick="event.stopPropagation(); showDeleteModal('project', ${project.id}, '${project.name.replace(/'/g, '&apos;')}')" 
                            class="text-red-500 hover:text-red-700 p-1 rounded"
                            title="Hapus proyek ${project.name}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        function renderSubProjects(filteredSubProjects = subProjects) {
            const list = document.getElementById('subProjectList');
            if (filteredSubProjects.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Belum ada sub proyek</p>';
                return;
            }

            list.innerHTML = filteredSubProjects.map(subProject => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer ${subProject.id === selectedSubProjectId ? 'bg-green-50 border-2 border-green-200' : ''}" 
                     onclick="selectSubProject(${subProject.id})"
                     title="Klik untuk memilih sub proyek ${subProject.name}">
                    <div class="flex flex-col flex-1">
                        <span class="text-sm font-medium text-gray-900">${subProject.name}</span>
                        ${subProject.id === selectedSubProjectId ? '<span class="text-xs text-green-600 font-medium">✓ Terpilih</span>' : ''}
                    </div>
                    <button onclick="event.stopPropagation(); showDeleteModal('subproject', ${subProject.id}, '${subProject.name.replace(/'/g, '&apos;')}')" 
                            class="text-red-500 hover:text-red-700 p-1 rounded"
                            title="Hapus sub proyek ${subProject.name}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        function renderMaterials(filteredMaterials = materials) {
            const list = document.getElementById('materialList');
            if (!list) {
                console.error('Material list element not found');
                return;
            }

            if (filteredMaterials.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Belum ada material</p>';
                return;
            }

            list.innerHTML = filteredMaterials.map(material => `
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <i class="fas fa-box text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-green-900">${material.name}</span>
                            <span class="text-xs text-green-600 ml-2 bg-green-200 px-2 py-1 rounded-full">${material.unit}</span>
                        </div>
                        ${material.category ? `
                            <div class="mt-1 flex items-center">
                                <i class="fas fa-tag text-purple-500 mr-1 text-xs"></i>
                                <span class="text-xs text-purple-600">${material.category.name}</span>
                            </div>
                        ` : ''}
                    </div>
                    <button onclick="showDeleteModal('material', ${material.id}, '${material.name.replace(/'/g, '&apos;')}')" 
                            class="text-red-500 hover:text-red-700 p-1 rounded"
                            title="Hapus material ${material.name}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        // Filter Functions
        function filterVendors() {
            const search = document.getElementById('searchVendor').value.toLowerCase();
            const filtered = vendors.filter(v => v.name.toLowerCase().includes(search));
            renderVendors(filtered);
        }

        function filterProjects() {
            const search = document.getElementById('searchProject').value.toLowerCase();
            const filtered = projects.filter(p => p.name.toLowerCase().includes(search));
            renderProjects(filtered);
        }

        function filterSubProjects() {
            const search = document.getElementById('searchSubProject').value.toLowerCase();
            const filtered = subProjects.filter(sp => sp.name.toLowerCase().includes(search));
            renderSubProjects(filtered);
        }

        function filterMaterials() {
            const search = document.getElementById('searchMaterial').value.toLowerCase();
            const filtered = materials.filter(m => m.name.toLowerCase().includes(search));
            renderMaterials(filtered);
        }

        // Add Functions
        function addVendor() {
            const input = document.getElementById('newVendor');
            const name = input.value.trim();
            if (!name) {
                showNotification('Nama vendor tidak boleh kosong', 'warning');
                return;
            }

            // Check if vendor already exists locally
            if (vendors.some(v => v.name.toLowerCase() === name.toLowerCase())) {
                showNotification('Vendor dengan nama tersebut sudah ada', 'warning');
                return;
            }

            // Show loading state
            const btn = document.getElementById('addVendorBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin w-5 h-5"></i>';
            btn.disabled = true;

            console.log('Making request to create vendor:', name);
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/api/master-data/vendors', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ name: name })
            })
            .then(response => {
                console.log('Vendor response status:', response.status);
                console.log('Vendor response headers:', [...response.headers.entries()]);
                
                return response.text().then(text => {
                    console.log('Raw vendor response:', text);
                    
                    if (!text.trim()) {
                        throw new Error('Empty response from server');
                    }
                    
                    try {
                        const data = JSON.parse(text);
                        return { ...data, ok: response.ok };
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error(`Invalid JSON response: ${text.substring(0, 200)}...`);
                    }
                });
            })
            .then(result => {
                console.log('Parsed vendor result:', result);
                
                if (!result.ok) {
                    throw new Error(`HTTP ${result.status || 'error'}: ${result.message || 'Request failed'}`);
                }
                
                if (result.success) {
                    vendors.push(result.vendor);
                    renderVendors();
                    input.value = '';
                    showNotification('Vendor berhasil ditambahkan', 'success');
                } else {
                    showNotification(result.message || 'Gagal menambahkan vendor', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating vendor:', error);
                showNotification('Gagal menambahkan vendor: ' + error.message, 'error');
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function addProject() {
            console.log('addProject() called');
            const input = document.getElementById('newProject');
            const name = input.value.trim();
            
            console.log('Project name input:', name);
            
            if (!name) {
                console.warn('Project name is empty');
                showNotification('Nama proyek tidak boleh kosong', 'warning');
                return;
            }

            // Check if project already exists locally
            const existingProject = projects.find(p => p.name.toLowerCase() === name.toLowerCase());
            if (existingProject) {
                console.warn('Project already exists:', existingProject);
                showNotification('Proyek dengan nama tersebut sudah ada', 'warning');
                return;
            }

            // Show loading state
            const btn = document.getElementById('addProjectBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin w-5 h-5"></i>';
            btn.disabled = true;

            console.log('Making API request to create project:', { name });
            
            fetch('/api/master-data/projects', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ name: name })
            })
            .then(response => {
                console.log('Project creation response:', response.status, response.statusText);
                
                // Get raw text first for debugging
                return response.text().then(text => {
                    console.log('Raw project response:', text);
                    
                    if (!text.trim()) {
                        throw new Error('Empty response from server');
                    }
                    
                    try {
                        const data = JSON.parse(text);
                        return { ...data, ok: response.ok };
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error(`Invalid JSON response: ${text.substring(0, 200)}...`);
                    }
                });
            })
            .then(result => {
                console.log('Parsed project result:', result);
                
                if (!result.ok) {
                    throw new Error(`HTTP ${result.status || 'error'}: ${result.message || 'Request failed'}`);
                }
                
                if (result.success) {
                    console.log('Project created successfully:', result.project);
                    projects.push(result.project);
                    renderProjects();
                    input.value = '';
                    showNotification('Proyek berhasil ditambahkan', 'success');
                } else {
                    console.error('API returned error:', result.message);
                    showNotification(result.message || 'Gagal menambahkan proyek', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating project:', error);
                showNotification('Terjadi kesalahan', 'error');
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function addSubProject() {
            if (!selectedProjectId) {
                showNotification('Pilih proyek utama terlebih dahulu', 'warning');
                return;
            }

            const input = document.getElementById('newSubProject');
            const name = input.value.trim();
            if (!name) {
                showNotification('Nama sub proyek tidak boleh kosong', 'warning');
                return;
            }

            // Check if sub project already exists locally
            if (subProjects.some(sp => sp.name.toLowerCase() === name.toLowerCase())) {
                showNotification('Sub proyek dengan nama tersebut sudah ada', 'warning');
                return;
            }

            // Show loading state
            const btn = document.getElementById('addSubProjectBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin w-5 h-5"></i>';
            btn.disabled = true;

            fetch('/api/master-data/sub-projects', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    name: name,
                    project_id: selectedProjectId 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    subProjects.push(data.subProject);
                    renderSubProjects();
                    input.value = '';
                    showNotification('Sub proyek berhasil ditambahkan', 'success');
                } else {
                    showNotification(data.message || 'Gagal menambahkan sub proyek', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            })
            .finally(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function addMaterial() {
            console.log('addMaterial() called');
            
            if (!selectedSubProjectId) {
                showNotification('Pilih sub proyek terlebih dahulu', 'warning');
                return;
            }
            
            const nameInput = document.getElementById('newMaterialName');
            const unitInput = document.getElementById('newMaterialUnit');
            const categorySelect = document.getElementById('materialCategory');
            
            const name = nameInput.value.trim();
            const unit = unitInput.value.trim();
            const categoryId = categorySelect.value;
            
            console.log('Material inputs:', { name, unit, categoryId, sub_project_id: selectedSubProjectId });
            
            if (!name || !unit) {
                console.warn('Name or unit is empty');
                showNotification('Nama dan satuan material harus diisi', 'warning');
                return;
            }
            
            if (!categoryId) {
                console.warn('Category not selected');
                showNotification('Kategori material harus dipilih', 'warning');
                return;
            }

            // Check if material already exists locally for this sub project
            const existingMaterial = materials.find(m => m.name.toLowerCase() === name.toLowerCase());
            if (existingMaterial) {
                console.warn('Material already exists:', existingMaterial);
                showNotification('Material dengan nama tersebut sudah ada untuk sub proyek ini', 'warning');
                return;
            }

            // Show loading state
            const btn = document.getElementById('addMaterialBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambah...';
            btn.disabled = true;

            console.log('Making API request to create material:', { 
                name, 
                unit, 
                category_id: categoryId,
                sub_project_id: selectedSubProjectId
            });

            fetch('/api/master-data/materials', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ 
                    name: name,
                    unit: unit,
                    category_id: parseInt(categoryId),
                    sub_project_id: selectedSubProjectId
                })
            })
            .then(response => {
                console.log('Material creation response:', response.status, response.statusText);
                
                return response.text().then(text => {
                    console.log('Raw material response:', text);
                    
                    if (!text.trim()) {
                        throw new Error('Empty response from server');
                    }
                    
                    try {
                        const data = JSON.parse(text);
                        return { ...data, ok: response.ok };
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error(`Invalid JSON response: ${text.substring(0, 200)}...`);
                    }
                });
            })
            .then(result => {
                console.log('Parsed material result:', result);
                
                if (!result.ok) {
                    throw new Error(`HTTP ${result.status || 'error'}: ${result.message || 'Request failed'}`);
                }
                
                if (result.success) {
                    console.log('Material created successfully:', result.material);
                    materials.push(result.material);
                    renderMaterials();
                    nameInput.value = '';
                    unitInput.value = '';
                    categorySelect.value = '';
                    showNotification('Material berhasil ditambahkan', 'success');
                } else {
                    console.error('API returned error:', result.message);
                    showNotification(result.message || 'Gagal menambahkan material', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating material:', error);
                showNotification('Gagal menambahkan material: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        // Project Selection
        function selectProject(projectId) {
            selectedProjectId = projectId;
            selectedSubProjectId = null; // Reset sub project selection
            const project = projects.find(p => p.id === projectId);
            
            // Update UI
            document.getElementById('initialState').classList.add('hidden');
            document.getElementById('activeState').classList.remove('hidden');
            document.getElementById('selectedProjectName').textContent = project.name;
            
            // Re-render projects to show selection
            renderProjects();
            
            // Load sub projects for this project
            loadProjectData(projectId);
            
            // Clear categories and materials since no sub project is selected
            categories = [];
            materials = [];
            renderCategories();
            renderMaterials();
            updateMaterialCategorySelect();
            
            // Show message to select sub project first
            showSubProjectSelectionMessage();
        }

        // New: Sub Project Selection
        function selectSubProject(subProjectId) {
            console.log('selectSubProject() called with ID:', subProjectId);
            
            selectedSubProjectId = subProjectId;
            const subProject = subProjects.find(sp => sp.id === subProjectId);
            
            console.log('Sub project selected:', subProject);
            console.log('selectedSubProjectId is now set to:', selectedSubProjectId);
            
            // Re-render sub projects to show selection
            renderSubProjects();
            
            // Load categories and materials for this sub project
            loadSubProjectData(subProjectId);
            
            // Hide sub project selection message
            hideSubProjectSelectionMessage();
        }

        function showSubProjectSelectionMessage() {
            const categoryPanel = document.getElementById('categoryPanel');
            const materialPanel = document.getElementById('materialPanel');
            
            if (categoryPanel) {
                categoryPanel.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-blue-500 text-2xl mb-3"></i>
                        <p class="text-gray-600 font-medium">Pilih Sub Proyek Terlebih Dahulu</p>
                        <p class="text-gray-500 text-sm mt-1">Kategori material berbeda untuk setiap sub proyek</p>
                    </div>
                `;
            }
            
            if (materialPanel) {
                materialPanel.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-blue-500 text-2xl mb-3"></i>
                        <p class="text-gray-600 font-medium">Pilih Sub Proyek Terlebih Dahulu</p>
                        <p class="text-gray-500 text-sm mt-1">Material berbeda untuk setiap sub proyek</p>
                    </div>
                `;
            }
        }

        function hideSubProjectSelectionMessage() {
            console.log('hideSubProjectSelectionMessage() called');
            
            // Reset category panel to show normal add form
            const categoryPanel = document.getElementById('categoryPanel');
            if (categoryPanel) {
                console.log('Restoring category panel HTML');
                categoryPanel.innerHTML = `
                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" id="newCategory" placeholder="Nama kategori baru" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button id="addCategoryBtn" onclick="addCategory()" 
                                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors whitespace-nowrap">
                                <i class="fas fa-plus mr-1"></i>Tambah
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <input type="text" id="searchCategory" placeholder="Cari kategori..." onkeyup="filterCategories()" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div id="categoryList" class="space-y-2 max-h-60 overflow-y-auto"></div>
                `;
            }
            
            // Reset material panel to show normal add form  
            const materialPanel = document.getElementById('materialPanel');
            if (materialPanel) {
                console.log('Restoring material panel HTML');
                materialPanel.innerHTML = `
                    <!-- Search Material -->
                    <div class="relative mb-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchMaterial"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                               placeholder="Cari material...">
                    </div>

                    <!-- Add Material Form -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-medium text-green-700 mb-3">
                            <i class="fas fa-plus-circle mr-2"></i>Tambah Material Baru
                        </h4>
                        <div class="space-y-3">
                            <select id="materialCategory" 
                                    class="w-full px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Kategori Material...</option>
                            </select>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <input type="text" id="newMaterialName" 
                                       class="px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Nama material...">
                                <input type="text" id="newMaterialUnit" 
                                       class="px-3 py-2 text-sm border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Satuan (kg, pcs, m, dll)...">
                            </div>
                            <button id="addMaterialBtn" onclick="addMaterial()" 
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Tambah Material
                            </button>
                        </div>
                    </div>

                    <!-- Material List -->
                    <div id="materialList" class="space-y-2 max-h-64 overflow-y-auto">
                        <p class="text-gray-500 text-sm text-center py-8">
                            <i class="fas fa-info-circle mr-2"></i>Pilih kategori terlebih dahulu, lalu tambahkan material
                        </p>
                    </div>
                `;
            }
            
            // Render the data immediately
            renderCategories();
            renderMaterials();
            updateMaterialCategorySelect();
        }

        function loadSubProjectData(subProjectId) {
            console.log('Loading sub project data for sub project ID:', subProjectId);
            
            Promise.all([
                fetch(`/api/master-data/sub-projects/${subProjectId}/categories`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }).then(response => {
                    console.log('Categories response:', response.status, response.statusText);
                    return response.text().then(text => {
                        console.log('Raw categories response:', text);
                        if (!text.trim()) {
                            throw new Error('Empty response for categories');
                        }
                        return JSON.parse(text);
                    });
                }),
                fetch(`/api/master-data/sub-projects/${subProjectId}/materials`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }).then(response => {
                    console.log('Materials response:', response.status, response.statusText);
                    return response.text().then(text => {
                        console.log('Raw materials response:', text);
                        if (!text.trim()) {
                            throw new Error('Empty response for materials');
                        }
                        return JSON.parse(text);
                    });
                })
            ])
            .then(([categoriesData, materialsData]) => {
                console.log('Categories data:', categoriesData);
                console.log('Materials data:', materialsData);
                
                if (categoriesData.success) {
                    categories = categoriesData.categories || [];
                    console.log(`Loaded ${categories.length} categories for sub project`);
                } else {
                    throw new Error(categoriesData.message || 'Failed to load categories');
                }
                
                if (materialsData.success) {
                    materials = materialsData.materials || [];
                    console.log(`Loaded ${materials.length} materials for sub project`);
                } else {
                    throw new Error(materialsData.message || 'Failed to load materials');
                }
                
                renderCategories();
                renderMaterials();
                updateMaterialCategorySelect();
                console.log('Sub project data loaded successfully');
            })
            .catch(error => {
                console.error('Error loading sub project data:', error);
                showNotification('Gagal memuat data sub proyek: ' + error.message, 'error');
                
                // Show empty states
                categories = [];
                materials = [];
                renderCategories();
                renderMaterials();
                updateMaterialCategorySelect();
            });
        }

        function loadProjectData(projectId) {
            console.log('Loading project data for project ID:', projectId);
            
            fetch(`/api/master-data/projects/${projectId}/sub-projects`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Sub-projects response:', response.status, response.statusText);
                return response.text().then(text => {
                    console.log('Raw sub-projects response:', text);
                    if (!text.trim()) {
                        throw new Error('Empty response for sub-projects');
                    }
                    return JSON.parse(text);
                });
            })
            .then(subProjectsData => {
                console.log('Sub-projects data:', subProjectsData);
                
                if (subProjectsData.success) {
                    subProjects = subProjectsData.subProjects || [];
                    console.log(`Loaded ${subProjects.length} sub-projects`);
                } else {
                    throw new Error(subProjectsData.message || 'Failed to load sub-projects');
                }
                
                renderSubProjects();
                console.log('Project data loaded successfully');
            })
            .catch(error => {
                console.error('Error loading project data:', error);
                showNotification('Gagal memuat data proyek: ' + error.message, 'error');
                
                // Show empty states
                subProjects = [];
                renderSubProjects();
            });
        }

        // Tab Switching
        function switchTab(event) {
            const targetTab = event.target.getAttribute('data-tab');
            
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
            
            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(targetTab + 'Tab').classList.remove('hidden');
        }

        // Material Sub-Tab Switching
        function switchMaterialTab(event) {
            console.log('Switching material tab to:', event.target.id);
            
            // Remove active class from all material tabs
            document.querySelectorAll('.material-tab-btn').forEach(btn => {
                btn.classList.remove('bg-purple-600', 'text-white');
                btn.classList.add('text-gray-600');
            });

            // Add active class to clicked tab
            event.target.classList.remove('text-gray-600');
            event.target.classList.add('bg-purple-600', 'text-white');

            // Hide all material panels
            document.querySelectorAll('.material-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            // Show selected panel
            if (event.target.id === 'categoryTabBtn') {
                document.getElementById('categoryPanel').classList.remove('hidden');
                
                // Check if sub project is selected before rendering categories
                if (selectedSubProjectId) {
                    renderCategories(); // Load categories when switching to category tab
                } else {
                    showSubProjectSelectionMessage();
                }
            } else if (event.target.id === 'materialTabBtn') {
                document.getElementById('materialPanel').classList.remove('hidden');
                
                // Check if sub project is selected before rendering materials
                if (selectedSubProjectId) {
                    updateMaterialCategorySelect(); // Update category dropdown
                    renderMaterials(); // Load materials when switching to material tab
                } else {
                    showSubProjectSelectionMessage();
                }
            }
        }

        // Category Management Functions
        function renderCategories(filteredCategories = categories) {
            const list = document.getElementById('categoryList');
            if (!list) {
                console.error('Category list element not found');
                return;
            }

            if (filteredCategories.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Belum ada kategori material</p>';
                return;
            }

            list.innerHTML = filteredCategories.map(category => `
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <span class="text-sm font-medium text-purple-900" title="${category.name}">
                        <i class="fas fa-tag mr-2 text-purple-600"></i>${category.name}
                    </span>
                    <button onclick="showDeleteModal('category', ${category.id}, '${category.name.replace(/'/g, '&apos;')}')" 
                            class="text-red-500 hover:text-red-700 p-1 rounded"
                            title="Hapus kategori ${category.name}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        function filterCategories() {
            const searchTerm = document.getElementById('searchCategory').value.toLowerCase();
            const filtered = categories.filter(category => 
                category.name.toLowerCase().includes(searchTerm)
            );
            renderCategories(filtered);
        }

        function addCategory() {
            console.log('addCategory() called');
            console.log('selectedSubProjectId:', selectedSubProjectId);
            
            if (!selectedSubProjectId) {
                console.warn('No sub project selected');
                showNotification('Pilih sub proyek terlebih dahulu', 'warning');
                return;
            }
            
            const input = document.getElementById('newCategory');
            const name = input.value.trim();
            
            console.log('Category name input:', name);
            
            if (!name) {
                console.warn('Category name is empty');
                showNotification('Nama kategori tidak boleh kosong', 'warning');
                return;
            }

            // Check if category already exists locally for this sub project
            const existingCategory = categories.find(c => c.name.toLowerCase() === name.toLowerCase());
            if (existingCategory) {
                console.warn('Category already exists:', existingCategory);
                showNotification('Kategori dengan nama tersebut sudah ada untuk sub proyek ini', 'warning');
                return;
            }

            // Show loading state
            const btn = document.getElementById('addCategoryBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambah...';
            btn.disabled = true;

            console.log('Making API request to create category:', { name, sub_project_id: selectedSubProjectId });
            
            fetch('/api/master-data/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ 
                    name: name,
                    sub_project_id: selectedSubProjectId
                })
            })
            .then(response => {
                console.log('Category creation response:', response.status, response.statusText);
                
                return response.text().then(text => {
                    console.log('Raw category response:', text);
                    
                    if (!text.trim()) {
                        throw new Error('Empty response from server');
                    }
                    
                    try {
                        const data = JSON.parse(text);
                        return { ...data, ok: response.ok };
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error(`Invalid JSON response: ${text.substring(0, 200)}...`);
                    }
                });
            })
            .then(result => {
                console.log('Parsed category result:', result);
                
                if (!result.ok) {
                    throw new Error(`HTTP ${result.status || 'error'}: ${result.message || 'Request failed'}`);
                }
                
                if (result.success) {
                    console.log('Category created successfully:', result.category);
                    categories.push(result.category);
                    renderCategories();
                    updateMaterialCategorySelect(); // Update dropdown in material tab
                    input.value = '';
                    showNotification('Kategori berhasil ditambahkan', 'success');
                } else {
                    console.error('API returned error:', result.message);
                    showNotification(result.message || 'Gagal menambahkan kategori', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating category:', error);
                showNotification('Gagal menambahkan kategori: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function updateMaterialCategorySelect() {
            const select = document.getElementById('materialCategory');
            if (!select) return;

            // Clear existing options
            select.innerHTML = '<option value="">Pilih Kategori Material...</option>';

            // Add category options
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                select.appendChild(option);
            });

            console.log(`Updated material category select with ${categories.length} categories`);
        }

        // Modal Functions
        function showDeleteModal(type, id, name) {
            deleteTarget = { type, id, name };
            document.getElementById('deleteMessage').textContent = `${name} akan dihapus secara permanen dan tidak dapat dikembalikan.`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            deleteTarget = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function confirmDelete() {
            if (!deleteTarget) return;

            const { type, id, name } = deleteTarget;
            
            // Map the type name to proper endpoint names
            let endpointType = type;
            if (type === 'subproject') {
                endpointType = 'sub-projects';
            } else if (type === 'category') {
                endpointType = 'categories';
            } else if (type === 'material') {
                endpointType = 'materials';
            } else {
                endpointType = type + 's'; // vendor -> vendors, project -> projects
            }
            
            fetch(`/api/master-data/${endpointType}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from local array
                    switch(type) {
                        case 'vendor':
                            vendors = vendors.filter(v => v.id !== id);
                            renderVendors();
                            break;
                        case 'project':
                            projects = projects.filter(p => p.id !== id);
                            renderProjects();
                            if (selectedProjectId === id) {
                                selectedProjectId = null;
                                document.getElementById('activeState').classList.add('hidden');
                                document.getElementById('initialState').classList.remove('hidden');
                            }
                            break;
                        case 'subproject':
                            subProjects = subProjects.filter(sp => sp.id !== id);
                            renderSubProjects();
                            break;
                        case 'material':
                            materials = materials.filter(m => m.id !== id);
                            renderMaterials();
                            break;
                    }
                    showNotification(`${name} berhasil dihapus`, 'success');
                } else {
                    showNotification(data.message || 'Gagal menghapus item', 'error');
                }
                hideDeleteModal();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
                hideDeleteModal();
            });
        }

        // Notification Function
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            const bgColor = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            }[type];
            
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</x-admin-layout>
