<x-admin-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg rounded-b-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Detail PO Transport</h1>
                        <p class="text-blue-100 text-lg">Review dan kelola pengajuan PO Transport</p>
                        <div class="flex items-center mt-3 text-blue-100">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ now()->setTimezone('Asia/Jakarta')->format('l, d F Y - H:i') }} WIB</span>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <a href="{{ route('admin.po-transports.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition-all ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Information Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Informasi PO Transport</h3>
                                {!! $poTransport->status_badge !!}
                            </div>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <!-- PO Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor PO Transport</label>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xl font-semibold text-gray-900">{{ $poTransport->po_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- User Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diajukan oleh</label>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">
                                                {{ strtoupper(substr($poTransport->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">{{ $poTransport->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $poTransport->user->email }}</p>
                                        <p class="text-xs text-gray-400 capitalize">{{ $poTransport->user->role }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Excel</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-10 w-10 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $poTransport->document_name }}</p>
                                                <p class="text-sm text-gray-500">File Excel (.xlsx/.xls)</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.po-transports.download', $poTransport) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Download Excel
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
                                                @if($poTransport->reviewed_at)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
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

                    <!-- Status Update Form -->
                    @if($poTransport->status === 'pending' || in_array($poTransport->status, ['approved', 'rejected']))
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Update Status</h3>
                        </div>
                        <div class="px-6 py-6">
                            <form method="POST" action="{{ route('admin.po-transports.update-status', $poTransport) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" required
                                            class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Pilih Status</option>
                                        <option value="approved" {{ $poTransport->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ $poTransport->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="active" {{ $poTransport->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="completed" {{ $poTransport->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $poTransport->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan Admin
                                        <span class="text-gray-400 text-sm">(Opsional)</span>
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="4"
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="Tambahkan catatan untuk user...">{{ old('admin_notes', $poTransport->admin_notes) }}</textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions & Info Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white shadow-sm rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('admin.po-transports.download', $poTransport) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download Excel
                            </a>

                            @if($poTransport->status === 'pending')
                                <div class="grid grid-cols-2 gap-2">
                                    <form method="POST" action="{{ route('admin.po-transports.update-status', $poTransport) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menyetujui PO Transport ini?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Setujui
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.po-transports.update-status', $poTransport) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menolak PO Transport ini?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Information Summary -->
                    <div class="bg-white shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        {!! $poTransport->status_badge !!}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $poTransport->formatted_submitted_at }}
                                    </dd>
                                </div>
                                @if($poTransport->reviewed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Review</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $poTransport->formatted_reviewed_at }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $poTransport->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User Role</dt>
                                    <dd class="mt-1 text-sm text-gray-900 capitalize">
                                        {{ $poTransport->user->role }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
