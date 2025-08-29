<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl px-8 py-6 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        Download Center
                    </h1>
                    <p class="text-blue-100 text-lg">Akses dokumen, template, dan form yang diperlukan untuk pekerjaan lapangan</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-20 rounded-xl px-4 py-3">
                        <p class="text-sm text-blue-100">Total Dokumen Tersedia</p>
                        <p class="text-2xl font-bold">{{ $documents->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-2xl shadow-lg mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-white hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Category Filter -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8" x-data="{
                selectedCategory: '{{ request('category', 'all') }}',
                searchTerm: '{{ request('search', '') }}'
            }">
                <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchTerm"
                               placeholder="Cari dokumen..."
                               class="pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full transition-colors duration-200">
                    </div>

                    <!-- Category Filter -->
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">Filter Kategori:</label>
                        <select x-model="selectedCategory"
                                class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            <option value="all">Semua Kategori</option>
                            <option value="template">Template</option>
                            <option value="manual">Manual</option>
                            <option value="form">Form</option>
                            <option value="document">Dokumen</option>
                            <option value="other">Lainnya</option>
                        </select>
                        <button @click="window.location.href = '{{ route('user.documents.index') }}?category=' + selectedCategory + '&search=' + encodeURIComponent(searchTerm)"
                                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
            </div>

            @if($documents->count() > 0)
                <!-- Documents Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($documents as $document)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            <!-- Category Badge -->
                            <div class="px-6 pt-6 pb-2">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $document->category == 'template' ? 'bg-purple-100 text-purple-800' :
                                           ($document->category == 'manual' ? 'bg-blue-100 text-blue-800' :
                                           ($document->category == 'form' ? 'bg-green-100 text-green-800' :
                                           ($document->category == 'document' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ ucfirst($document->category_label) }}
                                    </span>
                                    @if($document->created_at >= now()->subDays(7))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Baru
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- File Icon & Title -->
                            <div class="px-6 pb-4">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-16 h-16 bg-gradient-to-br
                                        {{ $document->category == 'template' ? 'from-purple-400 to-purple-600' :
                                           ($document->category == 'manual' ? 'from-blue-400 to-blue-600' :
                                           ($document->category == 'form' ? 'from-green-400 to-green-600' :
                                           ($document->category == 'document' ? 'from-yellow-400 to-yellow-600' : 'from-gray-400 to-gray-600'))) }}
                                        rounded-xl flex items-center justify-center">

                                        @php
                                            $extension = pathinfo($document->original_name, PATHINFO_EXTENSION);
                                            $iconClass = 'w-8 h-8 text-white';
                                        @endphp

                                        @if(in_array($extension, ['pdf']))
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @elseif(in_array($extension, ['doc', 'docx']))
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                            </svg>
                                        @elseif(in_array($extension, ['ppt', 'pptx']))
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V3a1 1 0 011 1v7.586l2 2V21a1 1 0 01-1 1H5a1 1 0 01-1-1v-8.414l2-2V4a1 1 0 011-1z"/>
                                            </svg>
                                        @elseif(in_array($extension, ['zip', 'rar']))
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                            </svg>
                                        @else
                                            <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">{{ $document->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ $document->original_name }}</p>
                                    </div>
                                </div>

                                @if($document->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $document->description }}</p>
                                @endif

                                <!-- File Info -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span>{{ $document->file_size_formatted }}</span>
                                    <span>{{ $document->created_at->diffForHumans() }}</span>
                                </div>

                                <!-- Download Stats -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>{{ $document->download_count }} download</span>
                                    </div>
                                    <span>Diupload {{ $document->created_at->format('d/m/Y') }}</span>
                                </div>

                                <!-- Download Button -->
                                <a href="{{ route('user.documents.download', $document) }}"
                                   class="w-full bg-gradient-to-r
                                        {{ $document->category == 'template' ? 'from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800' :
                                           ($document->category == 'manual' ? 'from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800' :
                                           ($document->category == 'form' ? 'from-green-600 to-green-700 hover:from-green-700 hover:to-green-800' :
                                           ($document->category == 'document' ? 'from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800' : 'from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800'))) }}
                                   text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 transform hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Download Sekarang</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($documents->hasPages())
                    <div class="mt-12">
                        {{ $documents->withQueryString()->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Dokumen</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('category') && request('category') != 'all' || request('search'))
                            Tidak ada dokumen yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada dokumen yang tersedia untuk didownload.
                        @endif
                    </p>

                    @if(request('category') && request('category') != 'all' || request('search'))
                        <a href="{{ route('user.documents.index') }}"
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Lihat Semua Dokumen
                        </a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
