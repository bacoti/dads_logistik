<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard PO Material
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola purchase order material Anda dengan mudah</p>
            </div>
            <a href="{{ route('po.po-materials.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah PO Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quick Overview Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Total PO -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalPoMaterials }}</p>
                            <p class="text-xs text-gray-500">Total PO</p>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-semibold text-gray-900">{{ $pendingPoMaterials }}</p>
                            <p class="text-xs text-gray-500">Menunggu</p>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-semibold text-gray-900">{{ $approvedPoMaterials }}</p>
                            <p class="text-xs text-gray-500">Disetujui</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-semibold text-gray-900">{{ $rejectedPoMaterials }}</p>
                            <p class="text-xs text-gray-500">Ditolak</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent PO -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Utama</h3>
                    <div class="space-y-3">
                        <a href="{{ route('po.po-materials.index') }}"
                           class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="p-2 bg-gray-100 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Daftar PO Material</p>
                                <p class="text-xs text-gray-500">Lihat semua PO materials</p>
                            </div>
                        </a>

                        <a href="{{ route('po.po-materials.create') }}"
                           class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Buat PO Baru</p>
                                <p class="text-xs text-gray-500">Tambah PO material baru</p>
                            </div>
                        </a>

                        @if($pendingPoMaterials > 0)
                        <a href="{{ route('po.po-materials.index', ['status' => 'pending']) }}"
                           class="flex items-center p-3 text-gray-700 hover:bg-yellow-50 rounded-lg transition-colors">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">PO Menunggu</p>
                                <p class="text-xs text-gray-500">{{ $pendingPoMaterials }} PO menunggu persetujuan</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Recent PO Materials -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">PO Material Terbaru</h3>
                            @if($recentPoMaterials->count() > 0)
                            <a href="{{ route('po.po-materials.index') }}"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Lihat Semua
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($recentPoMaterials->count() > 0)
                    <div class="overflow-hidden">
                        <div class="space-y-0">
                            @foreach($recentPoMaterials as $poMaterial)
                            <div class="px-6 py-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div>
                                                <p class="font-medium text-gray-900 truncate">{{ $poMaterial->po_number }}</p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <p class="text-sm text-gray-500">{{ $poMaterial->supplier }}</p>
                                                    <span class="text-gray-300">•</span>
                                                    <p class="text-sm text-gray-500">{{ $poMaterial->project->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ $poMaterial->description }}</p>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            {!! $poMaterial->status_badge !!}
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('po.po-materials.show', $poMaterial) }}"
                                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                Lihat
                                            </a>
                                            @if($poMaterial->status === 'pending')
                                            <a href="{{ route('po.po-materials.edit', $poMaterial) }}"
                                               class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                                Edit
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-sm font-medium text-gray-900 mt-2">Belum ada PO Material</h3>
                        <p class="text-sm text-gray-500 mt-1">Mulai dengan membuat PO material pertama Anda</p>
                        <div class="mt-4">
                            <a href="{{ route('po.po-materials.create') }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat PO Material
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
