<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Detail Pengajuan MFO</h2>
                            <p class="text-gray-600 mt-1">ID: #{{ $mfoRequest->id }}</p>
                        </div>
                        <a href="{{ route('admin.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->user->name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email Pelapor</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->user->email }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Proyek</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->project->name ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Sub Proyek</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->subProject->name ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Lokasi Proyek</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->project_location }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cluster</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->cluster ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                                        <div class="mt-1 text-sm text-gray-900">{{ $mfoRequest->request_date->format('d F Y') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <div class="mt-1">{!! $mfoRequest->status_badge !!}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pengajuan -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4">Detail Pengajuan</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $mfoRequest->description }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dokumen Pendukung -->
                            @if($mfoRequest->document_path)
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4">Dokumen Pendukung</h3>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ basename($mfoRequest->document_path) }}
                                            </div>
                                            <div class="text-sm text-gray-500">Dokumen pendukung</div>
                                        </div>
                                        <a href="{{ route('admin.mfo-requests.download', $mfoRequest) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Review Admin -->
                            @if($mfoRequest->admin_notes)
                                <div class="bg-blue-50 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4 text-blue-900">Catatan Admin</h3>
                                    <div class="text-sm text-blue-800 whitespace-pre-wrap">{{ $mfoRequest->admin_notes }}</div>
                                    @if($mfoRequest->reviewed_at && $mfoRequest->reviewer)
                                        <div class="mt-3 text-xs text-blue-600">
                                            Ditinjau oleh {{ $mfoRequest->reviewer->name }} pada {{ $mfoRequest->reviewed_at->format('d F Y, H:i') }}
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
                                <form method="POST" action="{{ route('admin.mfo-requests.update-status', $mfoRequest) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="pending" {{ $mfoRequest->status === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                            <option value="reviewed" {{ $mfoRequest->status === 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                            <option value="approved" {{ $mfoRequest->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="rejected" {{ $mfoRequest->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                        <textarea name="admin_notes" 
                                                  id="admin_notes" 
                                                  rows="4" 
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                  placeholder="Berikan catatan atau komentar...">{{ $mfoRequest->admin_notes }}</textarea>
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
                                            <div class="text-sm font-medium text-gray-900">Pengajuan dibuat</div>
                                            <div class="text-xs text-gray-500">{{ $mfoRequest->created_at->format('d F Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    @if($mfoRequest->reviewed_at)
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Terakhir ditinjau</div>
                                                <div class="text-xs text-gray-500">{{ $mfoRequest->reviewed_at->format('d F Y, H:i') }}</div>
                                                @if($mfoRequest->reviewer)
                                                    <div class="text-xs text-gray-500">oleh {{ $mfoRequest->reviewer->name }}</div>
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
        </div>
    </div>
</x-admin-layout>
