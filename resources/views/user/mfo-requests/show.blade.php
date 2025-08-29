<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Detail Pengajuan MFO</h2>
                            <p class="text-gray-600 mt-1">ID: #{{ $mfoRequest->id }}</p>
                        </div>
                        <div class="flex space-x-3">
                            @if($mfoRequest->status === 'pending')
                                <a href="{{ route('user.mfo-requests.edit', $mfoRequest) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Edit
                                </a>
                            @endif
                            <a href="{{ route('user.mfo-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ← Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Informasi Umum -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Informasi Umum</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <!-- Detail Pengajuan -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Detail Pengajuan</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap bg-white p-3 rounded-md border">{{ $mfoRequest->description }}</div>
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
                                    <a href="{{ route('user.mfo-requests.download', $mfoRequest) }}" 
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
                                <div class="text-sm text-blue-800 whitespace-pre-wrap bg-white p-3 rounded-md border border-blue-200">{{ $mfoRequest->admin_notes }}</div>
                                @if($mfoRequest->reviewed_at && $mfoRequest->reviewer)
                                    <div class="mt-3 text-xs text-blue-600">
                                        Ditinjau oleh {{ $mfoRequest->reviewer->name }} pada {{ $mfoRequest->reviewed_at->format('d F Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Timeline -->
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

                        <!-- Actions -->
                        @if($mfoRequest->status === 'pending')
                            <div class="bg-yellow-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-4 text-yellow-900">Aksi</h3>
                                <div class="flex space-x-3">
                                    <a href="{{ route('user.mfo-requests.edit', $mfoRequest) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Pengajuan
                                    </a>
                                    
                                    <form method="POST" action="{{ route('user.mfo-requests.destroy', $mfoRequest) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Pengajuan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
