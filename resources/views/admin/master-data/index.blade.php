<x-admin-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl px-8 py-6 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </div>
                        Master Data Management
                    </h1>
                    <p class="text-red-100 text-lg">Kelola seluruh data referensi sistem logistik dengan mudah dan terorganisir</p>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-4 py-3">
                        <div class="text-white text-sm font-medium">Status Sinkronisasi</div>
                        <div class="flex items-center mt-1">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-green-100 text-sm">Tersinkronisasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        activeTab: '{{ $activeTab }}',
        showAddModal: false,
        showEditModal: false,
        editData: {},
        modalType: '',
        subProjects: [''],
        viewMode: 'grid', // grid or table
        searchQuery: '{{ $search }}',
        showDeleteModal: false,
        deleteData: {},
        isLoading: false,

        // Statistics
        stats: {
            vendors: {{ $vendors->count() }},
            projects: {{ $projects->count() }},
            categories: {{ $categories->count() }},
            materials: {{ $materials->count() }}
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-blue-100 hover:border-blue-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                         @click="activeTab = 'vendors'">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-blue-600 font-bold text-3xl mb-1" x-text="stats.vendors">{{ $vendors->count() }}</div>
                                <div class="text-gray-600 text-sm font-medium">Total Vendor</div>
                            </div>
                            <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="font-medium">Terdaftar & Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-green-100 hover:border-green-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                         @click="activeTab = 'projects'">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-green-600 font-bold text-3xl mb-1" x-text="stats.projects">{{ $projects->count() }}</div>
                                <div class="text-gray-600 text-sm font-medium">Total Proyek</div>
                            </div>
                            <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Dalam Progres</span>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-purple-100 hover:border-purple-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                         @click="activeTab = 'categories'">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-purple-600 font-bold text-3xl mb-1" x-text="stats.categories">{{ $categories->count() }}</div>
                                <div class="text-gray-600 text-sm font-medium">Total Kategori</div>
                            </div>
                            <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-purple-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span class="font-medium">Terklasifikasi</span>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-6 shadow-lg border border-orange-100 hover:border-orange-300 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                         @click="activeTab = 'materials'">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-orange-600 font-bold text-3xl mb-1" x-text="stats.materials">{{ $materials->count() }}</div>
                                <div class="text-gray-600 text-sm font-medium">Total Material</div>
                            </div>
                            <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-orange-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <span class="font-medium">Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <!-- Tab Navigation with Enhanced Visual Design -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="px-8 py-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Manajemen Data Master</h2>
                                <p class="text-gray-600">Kelola seluruh data referensi sistem dalam satu tempat yang terintegrasi</p>
                            </div>
                        </div>

                        <!-- Enhanced Tab Navigation -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                            <nav class="flex space-x-1 bg-white rounded-xl p-1 shadow-inner border border-gray-200">
                                <button @click="activeTab = 'vendors'"
                                        :class="activeTab === 'vendors' ? 'bg-blue-500 text-white shadow-md' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'"
                                        class="flex items-center px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Vendor
                                    <span :class="activeTab === 'vendors' ? 'bg-white text-blue-500' : 'bg-gray-200 text-gray-600'"
                                          class="ml-2 px-2 py-1 rounded-full text-xs font-bold"
                                          x-text="stats.vendors"></span>
                                </button>
                                <button @click="activeTab = 'projects'"
                                        :class="activeTab === 'projects' ? 'bg-green-500 text-white shadow-md' : 'text-gray-600 hover:text-green-600 hover:bg-green-50'"
                                        class="flex items-center px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Proyek
                                    <span :class="activeTab === 'projects' ? 'bg-white text-green-500' : 'bg-gray-200 text-gray-600'"
                                          class="ml-2 px-2 py-1 rounded-full text-xs font-bold"
                                          x-text="stats.projects"></span>
                                </button>
                                <button @click="activeTab = 'categories'"
                                        :class="activeTab === 'categories' ? 'bg-purple-500 text-white shadow-md' : 'text-gray-600 hover:text-purple-600 hover:bg-purple-50'"
                                        class="flex items-center px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Kategori
                                    <span :class="activeTab === 'categories' ? 'bg-white text-purple-500' : 'bg-gray-200 text-gray-600'"
                                          class="ml-2 px-2 py-1 rounded-full text-xs font-bold"
                                          x-text="stats.categories"></span>
                                </button>
                                <button @click="activeTab = 'materials'"
                                        :class="activeTab === 'materials' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50'"
                                        class="flex items-center px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 whitespace-nowrap">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Material
                                    <span :class="activeTab === 'materials' ? 'bg-white text-orange-500' : 'bg-gray-200 text-gray-600'"
                                          class="ml-2 px-2 py-1 rounded-full text-xs font-bold"
                                          x-text="stats.materials"></span>
                                </button>
                            </nav>

                            <!-- View Mode Toggle -->
                            <div class="flex items-center space-x-2 bg-white rounded-lg p-1 shadow-inner border border-gray-200">
                                <button @click="viewMode = 'grid'"
                                        :class="viewMode === 'grid' ? 'bg-red-500 text-white' : 'text-gray-600 hover:text-red-600'"
                                        class="p-2 rounded-md transition-all duration-200"
                                        title="Tampilan Grid">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </button>
                                <button @click="viewMode = 'table'"
                                        :class="viewMode === 'table' ? 'bg-red-500 text-white' : 'text-gray-600 hover:text-red-600'"
                                        class="p-2 rounded-md transition-all duration-200"
                                        title="Tampilan Tabel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="px-8 py-6">
                    <!-- Success/Error Messages with Enhanced Design -->
                    @if(session('success'))
                        <div class="mb-6 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-lg border border-green-300"
                             x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-white hover:text-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-lg border border-red-300"
                             x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-white hover:text-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Search and Actions Toolbar -->
                    <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 mb-8">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-4 lg:space-y-0 lg:space-x-6">
                            <!-- Search Section -->
                            <div class="flex-1 w-full lg:max-w-md">
                                <form method="GET" class="relative">
                                    <input type="hidden" name="tab" x-bind:value="activeTab">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input type="text"
                                               name="search"
                                               x-model="searchQuery"
                                               value="{{ $search }}"
                                               placeholder="Cari data master..."
                                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm">
                                        @if($search)
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                                <a href="{{ route('admin.master-data.index', ['tab' => request('tab')]) }}"
                                                   class="text-gray-400 hover:text-gray-600 transition-colors"
                                                   title="Clear search">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex space-x-3">
                                        <button type="submit"
                                                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                                Cari
                                            </div>
                                        </button>
                                        @if($search)
                                            <a href="{{ route('admin.master-data.index', ['tab' => request('tab')]) }}"
                                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition-all duration-200">
                                                Reset
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-4">
                                <!-- Refresh Button -->
                                <button onclick="location.reload()"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
                                        title="Refresh Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>

                                <!-- Export Button -->
                                <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
                                        title="Export Data"
                                        @click="alert('Fitur export akan segera tersedia!')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>

                                <!-- Add Button with Dynamic Color -->
                                <button @click="showAddModal = true; modalType = activeTab; editData = {}; subProjects = ['']"
                                        :class="{
                                            'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700': activeTab === 'vendors',
                                            'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700': activeTab === 'projects',
                                            'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700': activeTab === 'categories',
                                            'from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700': activeTab === 'materials'
                                        }"
                                        class="bg-gradient-to-r text-white font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span x-text="'Tambah ' + (activeTab === 'vendors' ? 'Vendor' : activeTab === 'projects' ? 'Proyek' : activeTab === 'categories' ? 'Kategori' : 'Material')"></span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Stats Bar -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-center space-x-8 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    <span>Vendor: <strong x-text="stats.vendors"></strong></span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span>Proyek: <strong x-text="stats.projects"></strong></span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                    <span>Kategori: <strong x-text="stats.categories"></strong></span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                                    <span>Material: <strong x-text="stats.materials"></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Tab Content -->
                    <div x-show="activeTab === 'vendors'" class="space-y-4">
                        @include('admin.master-data.partials.vendors-table')
                    </div>

                    <!-- Projects Tab Content -->
                    <div x-show="activeTab === 'projects'" class="space-y-4">
                        @include('admin.master-data.partials.projects-table')
                    </div>

                    <!-- Categories Tab Content -->
                    <div x-show="activeTab === 'categories'" class="space-y-4">
                        @include('admin.master-data.partials.categories-table')
                    </div>

                    <!-- Materials Tab Content -->
                    <div x-show="activeTab === 'materials'" class="space-y-4">
                        @include('admin.master-data.partials.materials-table')
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        @include('admin.master-data.partials.modal')
    </div>

    <script>
        // Update URL when tab changes
        document.addEventListener('alpine:init', () => {
            Alpine.data('masterData', () => ({
                init() {
                    this.$watch('activeTab', (value) => {
                        const url = new URL(window.location);
                        url.searchParams.set('tab', value);
                        window.history.pushState(null, '', url);
                    });
                }
            }));
        });
    </script>
</x-admin-layout>
