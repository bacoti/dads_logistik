<x-app-layout>
    <x-page-header
        title="Detail Laporan Kehilangan"
        subtitle="Lihat informasi lengkap laporan kehilangan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('user.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Laporan Kehilangan', 'url' => route('user.loss-reports.index')],
            ['title' => 'Detail Laporan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z\'></path></svg>'"
        >
        <x-slot name="action">
            <div class="flex items-center space-x-3">
                @if($lossReport->status === 'pending')
                    <x-button
                        href="{{ route('user.loss-reports.edit', $lossReport) }}"
                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white">
                        Edit Laporan
                    </x-button>
                @endif
                <x-button
                    variant="secondary"
                    href="{{ route('user.loss-reports.index') }}"
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
                        <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white">{{ $lossReport->loss_date->format('d M Y') }}</h2>
                                        <p class="text-orange-100">Laporan Kehilangan</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $lossReport->status_badge ?? 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($lossReport->status) }}
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
                                            <div class="bg-orange-100 rounded-lg p-2 mr-3">
                                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">Tanggal Kehilangan</p>
                                                <p class="text-lg font-semibold text-gray-900">{{ $lossReport->loss_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center">
                                            <div class="bg-red-100 rounded-lg p-2 mr-3">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600">Jenis Material</p>
                                                <p class="text-lg font-semibold text-gray-900">{{ $lossReport->material_type }}</p>
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
                                            <div class="bg-blue-100 rounded-lg p-2 mr-4">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $lossReport->project->name ?? 'N/A' }}</h4>
                                                <p class="text-gray-600 mb-1">
                                                    <span class="font-medium">Sub Proyek:</span> {{ $lossReport->subProject->name ?? 'N/A' }}
                                                </p>
                                                <div class="flex items-start mt-3">
                                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Lokasi Proyek</p>
                                                        <p class="text-gray-600 mt-1">{{ $lossReport->project_location }}</p>
                                                    </div>
                                                </div>
                                                @if($lossReport->cluster)
                                                <div class="flex items-start mt-3">
                                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Cluster</p>
                                                        <p class="text-gray-600 mt-1">{{ $lossReport->cluster }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chronology Section -->
                            @if($lossReport->loss_chronology)
                                <div>
                                    <x-section-header
                                        title="Kronologi Kejadian"
                                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />

                                    <div class="mt-6 bg-red-50 rounded-xl p-6 border border-red-200">
                                        <p class="text-gray-700 leading-relaxed">{{ $lossReport->loss_chronology }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Additional Notes Section -->
                            @if($lossReport->additional_notes)
                                <div>
                                    <x-section-header
                                        title="Catatan Tambahan"
                                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'" />

                                    <div class="mt-6 bg-gray-50 rounded-xl p-6">
                                        <p class="text-gray-700 leading-relaxed">{{ $lossReport->additional_notes }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Supporting Document -->
                            @if($lossReport->supporting_document_path)
                                <div>
                                    <x-section-header
                                        title="Dokumen Pendukung"
                                        :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'" />

                                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="bg-blue-100 rounded-lg p-2 mr-4">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-blue-800">Dokumen Tersedia</p>
                                                    <p class="text-sm text-blue-600">{{ basename($lossReport->supporting_document_path) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('user.loss-reports.download', $lossReport) }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
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
                                        $lossReport->status === 'pending' ? 'bg-yellow-400' :
                                        ($lossReport->status === 'reviewed' ? 'bg-blue-400' :
                                        ($lossReport->status === 'approved' ? 'bg-green-400' : 'bg-red-400'))
                                    }} mr-3"></div>
                                    <span class="font-medium text-gray-700">{{ ucfirst($lossReport->status) }}</span>
                                </div>
                                <span class="text-sm text-gray-500">Current</span>
                            </div>

                            @if($lossReport->reviewed_at)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Direview pada:</p>
                                    <p class="text-sm text-gray-900">{{ $lossReport->reviewed_at->format('d M Y H:i') }}</p>

                                    @if($lossReport->reviewer)
                                        <p class="text-sm text-gray-600 mt-1">oleh {{ $lossReport->reviewer->name }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($lossReport->admin_notes)
                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Catatan Admin:</p>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <p class="text-sm text-blue-800">{{ $lossReport->admin_notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                        <div class="space-y-3">
                            @if($lossReport->status === 'pending')
                                <x-button
                                    href="{{ route('user.loss-reports.edit', $lossReport) }}"
                                    :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z\'></path></svg>'"
                                    class="w-full justify-center bg-indigo-500 hover:bg-indigo-600 text-white">
                                    Edit Laporan
                                </x-button>
                            @endif

                            @if($lossReport->supporting_document_path)
                                <x-button
                                    href="{{ route('user.loss-reports.download', $lossReport) }}"
                                    :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>'"
                                    class="w-full justify-center bg-blue-500 hover:bg-blue-600 text-white">
                                    Download Dokumen
                                </x-button>
                            @endif

                            <x-button
                                variant="secondary"
                                href="{{ route('user.loss-reports.create') }}"
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
                                <div class="bg-orange-100 rounded-full p-2 mr-4">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Laporan Dibuat</p>
                                    <p class="text-xs text-gray-500">{{ $lossReport->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($lossReport->reviewed_at)
                                <div class="flex items-start">
                                    <div class="bg-blue-100 rounded-full p-2 mr-4">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Direview Admin</p>
                                        <p class="text-xs text-gray-500">{{ $lossReport->reviewed_at->format('d M Y H:i') }}</p>
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
