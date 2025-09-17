<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Pengajuan MFO</h2>
                            <p class="text-gray-600 mt-1">Kelola pengajuan Material Field Order Anda</p>
                        </div>
                        <a href="{{ route('user.mfo-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Buat Pengajuan Baru
                        </a>
                    </div>

                    <!-- Status Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- MFO Requests Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Proyek
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi & Cluster
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Pengajuan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($mfoRequests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $request->project->name ?? 'N/A' }}
                                            </div>
                                            @if($request->subProject)
                                                <div class="text-sm text-gray-500">{{ $request->subProject->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->project_location }}</div>
                                            @if($request->cluster)
                                                <div class="text-sm text-gray-500">Cluster: {{ $request->cluster }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->request_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {!! $request->status_badge !!}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('user.mfo-requests.show', $request) }}"
                                                   class="text-indigo-600 hover:text-indigo-900">Detail</a>

                                                @if($request->status === 'pending')
                                                    <a href="{{ route('user.mfo-requests.edit', $request) }}"
                                                       class="text-yellow-600 hover:text-yellow-900">Edit</a>

                                                    <form method="POST" action="{{ route('user.mfo-requests.destroy', $request) }}"
                                                          class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($request->status === 'rejected')
                                                    <button onclick="openResubmitModal({{ $request->id }})"
                                                            class="text-orange-600 hover:text-orange-900">
                                                        Upload Ulang
                                                    </button>
                                                @endif

                                                @if($request->document_path)
                                                    <a href="{{ route('user.mfo-requests.download', $request) }}"
                                                       class="text-green-600 hover:text-green-900">Download</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Belum ada pengajuan MFO.
                                            <a href="{{ route('user.mfo-requests.create') }}" class="text-indigo-600 hover:text-indigo-900">
                                                Buat pengajuan pertama Anda
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($mfoRequests->hasPages())
                        <div class="mt-6">
                            {{ $mfoRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Resubmit Modal -->
    <div id="resubmitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Upload Ulang Dokumen</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeResubmitModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="resubmitForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-3">
                            Pengajuan MFO Anda ditolak. Silakan upload dokumen yang sudah diperbaiki untuk diajukan kembali.
                        </p>
                        <label for="resubmit_document" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               id="resubmit_document"
                               name="document"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 10MB.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeResubmitModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Upload & Ajukan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResubmitModal(mfoRequestId) {
            const modal = document.getElementById('resubmitModal');
            const form = document.getElementById('resubmitForm');
            form.action = `/user/mfo-requests/${mfoRequestId}/resubmit`;
            modal.style.display = 'block';
        }

        function closeResubmitModal() {
            const modal = document.getElementById('resubmitModal');
            modal.style.display = 'none';
            // Reset form
            document.getElementById('resubmitForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('resubmitModal');
            if (event.target === modal) {
                closeResubmitModal();
            }
        }
    </script>
</x-app-layout>
