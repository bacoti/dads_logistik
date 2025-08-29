<x-app-layout>
    <x-page-header 
        title="Detail Laporan Bulanan" 
        subtitle="Lihat informasi lengkap laporan bulanan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Bulanan', 'url' => route('user.monthly-reports.index')],
            ['title' => 'Detail Laporan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'></path></svg>'"
        >
        <x-slot name="action">
            <div class="flex items-center space-x-3">
                @if($report->status === 'pending')
                    <x-button 
                        href="{{ route('user.monthly-reports.edit', $report) }}"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white">
                        Edit Laporan
                    </x-button>
                @endif
                <x-button 
                    variant="secondary"
                    href="{{ route('user.monthly-reports.index') }}"
                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                    Kembali
                </x-button>
            </div>
        </x-slot>
    </x-page-header>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Report Header Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white">{{ $report->formatted_period }}</h2>
                                        <p class="text-red-100">Laporan Bulanan</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $report->status_badge }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8 space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <x-section-header 
                                    title="Informasi Dasar"
                                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>'" />
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">Tanggal Laporan</p>
                                                <p class="text-lg font-semibold text-gray-900">{{ $report->report_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center">
                                            <div class="bg-green-100 rounded-lg p-2 mr-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">Periode</p>
                                                <p class="text-lg font-semibold text-gray-900">{{ $report->report_period }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Information -->
                            <div>
                                <x-section-header 
                                    title="Informasi Proyek"
                                    :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\'></path></svg>'" />
                                
                                <div class="mt-6 space-y-4">
                                    <div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-r from-white to-gray-50">
                                        <div class="flex items-start">
                                            <div class="bg-red-100 rounded-lg p-2 mr-4">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $report->project->name }}</h4>
                                                <p class="text-gray-600 mb-1">
                                                    <span class="font-medium">Sub Proyek:</span> {{ $report->subProject->name }}
                                                </p>
                                                <div class="flex items-start mt-3">
                                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Lokasi Proyek</p>
                                                        <p class="text-gray-600 mt-1">{{ $report->project_location }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            @if($report->notes)
                                <div>
                                    <x-section-header 
                                        title="Catatan Laporan"
                                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />
                                    
                                    <div class="mt-6 bg-gray-50 rounded-xl p-6">
                                        <p class="text-gray-700 leading-relaxed">{{ $report->notes }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- File Information -->
                            @if($report->excel_file_path)
                                <div>
                                    <x-section-header 
                                        title="File Excel"
                                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />
                                    
                                    <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="bg-green-100 rounded-lg p-2 mr-4">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-green-800">File Excel Tersedia</p>
                                                    <p class="text-sm text-green-600">{{ basename($report->excel_file_path) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('user.monthly-reports.download', $report) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Laporan</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full {{ 
                                        $report->status === 'pending' ? 'bg-yellow-400' : 
                                        ($report->status === 'reviewed' ? 'bg-blue-400' :
                                        ($report->status === 'approved' ? 'bg-green-400' : 'bg-red-400'))
                                    }} mr-3"></div>
                                    <span class="font-medium text-gray-700">{{ ucfirst($report->status) }}</span>
                                </div>
                                <span class="text-sm text-gray-500">Current</span>
                            </div>

                            @if($report->reviewed_at)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Direview pada:</p>
                                    <p class="text-sm text-gray-900">{{ $report->reviewed_at->format('d M Y H:i') }}</p>
                                    
                                    @if($report->reviewer)
                                        <p class="text-sm text-gray-600 mt-1">oleh {{ $report->reviewer->name }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($report->admin_notes)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Catatan Admin:</p>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <p class="text-sm text-blue-800">{{ $report->admin_notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        
                        <div class="space-y-3">
                            @if($report->status === 'pending')
                                <x-button 
                                    href="{{ route('user.monthly-reports.edit', $report) }}"
                                    :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
                                    class="w-full justify-center bg-indigo-500 hover:bg-indigo-600 text-white">
                                    Edit Laporan
                                </x-button>
                            @endif

                            @if($report->excel_file_path)
                                <x-button 
                                    href="{{ route('user.monthly-reports.download', $report) }}"
                                    :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'"
                                    class="w-full justify-center bg-green-500 hover:bg-green-600 text-white">
                                    Download Excel
                                </x-button>
                            @endif

                            <x-button 
                                variant="secondary"
                                href="{{ route('user.monthly-reports.create') }}"
                                :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
                                class="w-full justify-center">
                                Buat Laporan Baru
                            </x-button>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="bg-green-100 rounded-full p-2 mr-4">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Laporan Dibuat</p>
                                    <p class="text-xs text-gray-500">{{ $report->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($report->reviewed_at)
                                <div class="flex items-start">
                                    <div class="bg-blue-100 rounded-full p-2 mr-4">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Direview Admin</p>
                                        <p class="text-xs text-gray-500">{{ $report->reviewed_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
