<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Detail PO Transport</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Informasi lengkap pengajuan PO Transport
                            </p>
                        </div>
                        <a href="{{ route('user.po-transports.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi PO Transport</h3>
                        </div>
                        <div class="px-6 py-4 space-y-6">
                            <!-- PO Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor PO Transport</label>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-lg font-semibold text-gray-900">{{ $poTransport->po_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Excel</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $poTransport->document_name }}</p>
                                                <p class="text-sm text-gray-500">File Excel (.xlsx/.xls)</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('user.po-transports.download', $poTransport) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($poTransport->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-900">{{ $poTransport->description }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Timeline -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">Timeline Status</label>
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        <!-- Submitted -->
                                        <li>
                                            <div class="relative pb-8">
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                Diajukan oleh <span class="font-medium text-gray-900">{{ $poTransport->user->name }}</span>
                                                            </p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="{{ $poTransport->submitted_at->format('Y-m-d H:i:s') }}">
                                                                {{ $poTransport->formatted_submitted_at }}
                                                            </time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <!-- Reviewed (if applicable) -->
                                        @if($poTransport->reviewed_at)
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full 
                                                            @if($poTransport->status === 'approved') bg-green-500
                                                            @elseif($poTransport->status === 'rejected') bg-red-500  
                                                            @else bg-gray-500 @endif
                                                            flex items-center justify-center ring-8 ring-white">
                                                            @if($poTransport->status === 'approved')
                                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            @elseif($poTransport->status === 'rejected')
                                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                @if($poTransport->status === 'approved')
                                                                    Disetujui oleh 
                                                                @elseif($poTransport->status === 'rejected')
                                                                    Ditolak oleh 
                                                                @else
                                                                    Direview oleh 
                                                                @endif
                                                                <span class="font-medium text-gray-900">
                                                                    {{ $poTransport->reviewer->name ?? 'Admin' }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="{{ $poTransport->reviewed_at->format('Y-m-d H:i:s') }}">
                                                                {{ $poTransport->formatted_reviewed_at }}
                                                            </time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Admin Notes (if available) -->
                            @if($poTransport->admin_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">{{ $poTransport->admin_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Status & Aksi</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <!-- Status Badge -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini</label>
                                <div class="flex justify-center">
                                    {!! $poTransport->status_badge !!}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-3">
                                @if($poTransport->status === 'pending')
                                    <a href="{{ route('user.po-transports.edit', $poTransport) }}" 
                                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                @endif

                                <a href="{{ route('user.po-transports.download', $poTransport) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Dokumen
                                </a>

                                @if($poTransport->status === 'pending')
                                    <form method="POST" action="{{ route('user.po-transports.destroy', $poTransport) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO Transport ini? Tindakan ini tidak dapat dibatalkan.')"
                                          class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Information -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Dibuat:</span>
                                        <span class="text-gray-900">{{ $poTransport->formatted_submitted_at }}</span>
                                    </div>
                                    @if($poTransport->reviewed_at)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Direview:</span>
                                            <span class="text-gray-900">{{ $poTransport->formatted_reviewed_at }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Diupdate:</span>
                                        <span class="text-gray-900">{{ $poTransport->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
