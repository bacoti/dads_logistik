<x-admin-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Detail Laporan Kehilangan</h2>
                        <p class="text-gray-600 mt-1">ID: #{{ $lossReport->id }}</p>
                    </div>
                    <a href="{{ route('admin.loss-reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        ← Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informasi Umum -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Umum</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->user->name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Pelapor</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->user->email }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Proyek</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->project->name ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sub Proyek</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->subProject->name ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lokasi Proyek</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->project_location }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cluster</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->cluster ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Kehilangan</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->loss_date->format('d F Y') }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lossReport->status_badge }}">
                                            {{ ucfirst($lossReport->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kehilangan -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Kehilangan</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Material yang Hilang</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $lossReport->material_type }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kronologi Kehilangan</label>
                                    <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $lossReport->loss_chronology }}</div>
                                </div>
                                @if($lossReport->additional_notes)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                                        <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $lossReport->additional_notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dokumen Pendukung -->
                        @if($lossReport->supporting_document_path)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Dokumen Pendukung</h3>
                                <div class="flex items-center space-x-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ basename($lossReport->supporting_document_path) }}
                                        </div>
                                        <div class="text-sm text-gray-500">Dokumen pendukung</div>
                                    </div>
                                    <a href="{{ route('admin.loss-reports.download', $lossReport) }}"
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Review Admin -->
                        @if($lossReport->admin_notes)
                            <div class="bg-blue-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4 text-blue-900">Catatan Admin</h3>
                                <div class="text-sm text-blue-800 whitespace-pre-wrap">{{ $lossReport->admin_notes }}</div>
                                @if($lossReport->reviewed_at && $lossReport->reviewer)
                                    <div class="mt-3 text-xs text-blue-600">
                                        Ditinjau oleh {{ $lossReport->reviewer->name }} pada {{ $lossReport->reviewed_at->format('d F Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Update Status -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Update Status</h3>
                            <form method="POST" action="{{ route('admin.loss-reports.update-status', $lossReport) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="pending" {{ $lossReport->status === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                        <option value="reviewed" {{ $lossReport->status === 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                        <option value="completed" {{ $lossReport->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                    <textarea name="admin_notes"
                                              id="admin_notes"
                                              rows="4"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                              placeholder="Berikan catatan atau komentar...">{{ $lossReport->admin_notes }}</textarea>
                                </div>

                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <!-- Information Timeline -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Laporan dibuat</div>
                                        <div class="text-xs text-gray-500">{{ $lossReport->created_at->format('d F Y, H:i') }}</div>
                                    </div>
                                </div>
                                @if($lossReport->reviewed_at)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Terakhir ditinjau</div>
                                            <div class="text-xs text-gray-500">{{ $lossReport->reviewed_at->format('d F Y, H:i') }}</div>
                                            @if($lossReport->reviewer)
                                                <div class="text-xs text-gray-500">oleh {{ $lossReport->reviewer->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
</x-admin-layout>
