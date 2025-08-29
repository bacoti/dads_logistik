<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail PO Material') }}
            </h2>
            <a href="{{ route('admin.po-materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Status Action Card (only show if pending) -->
            @if($poMaterial->status === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                PO Material ini menunggu keputusan Anda.
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-2">

                        <!-- APPROVE FORM - Simple HTML Form -->
                        <form method="POST" action="{{ route('admin.po-materials.update-status', $poMaterial) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui PO Material ini?')">
                                <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Setujui
                            </button>
                        </form>

                        <!-- REJECT FORM - Simple HTML Form -->
                        <form method="POST" action="{{ route('admin.po-materials.update-status', $poMaterial) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                    onclick="return confirm('Apakah Anda yakin ingin menolak PO Material ini?')">
                                <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Tolak
                            </button>
                        </form>

                        <!-- JavaScript Buttons (as backup/alternative) -->
                        <!-- Uncomment these if you want to use JavaScript version -->
                        <!--
                        <button onclick="changeStatus('{{ $poMaterial->id }}', 'approved')"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Setujui (JS)
                        </button>
                        <button onclick="changeStatus('{{ $poMaterial->id }}', 'rejected')"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Tolak (JS)
                        </button>
                        -->
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Information Card -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Informasi PO Material</h3>
                        {!! $poMaterial->status_badge !!}
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- No. PO -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. PO</label>
                            <p class="text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->po_number }}
                            </p>
                        </div>

                        <!-- PO User -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PO User</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->user->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->supplier }}
                            </p>
                        </div>

                        <!-- Tanggal Rilis -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rilis</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->release_date ? \Carbon\Carbon::parse($poMaterial->release_date)->format('d F Y') : 'N/A' }}
                            </p>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->location }}
                            </p>
                        </div>

                        <!-- Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->project->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Sub Project -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sub Project</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->subProject->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->formatted_quantity }}
                            </p>
                        </div>

                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Nama Material)</label>
                        <div class="bg-gray-50 px-4 py-3 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $poMaterial->description }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($poMaterial->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <div class="bg-gray-50 px-4 py-3 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $poMaterial->notes }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Timestamps Card -->
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Waktu</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat Pada</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->created_at->format('d F Y, H:i:s') }}
                            </p>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diperbarui</label>
                            <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                {{ $poMaterial->updated_at->format('d F Y, H:i:s') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('PO Material detail page loaded');
            console.log('changeStatus function available:', typeof changeStatus === 'function');
        });

        function changeStatus(poMaterialId, status) {
            try {
                console.log('=== PO APPROVAL DEBUG START ===');
                console.log('Change status called with:', { poMaterialId, status });

                // Validate parameters
                if (!poMaterialId || !status) {
                    console.error('Missing required parameters:', { poMaterialId, status });
                    alert('Error: Parameter tidak lengkap');
                    return false;
                }

                const statusText = status === 'approved' ? 'menyetujui' : 'menolak';
                console.log('Status text:', statusText);

                if (confirm(`Apakah Anda yakin ingin ${statusText} PO Material ini?`)) {
                    console.log('User confirmed action');

                    // Show loading state - find buttons more precisely
                    const approveBtn = document.querySelector('button[onclick*="approved"]');
                    const rejectBtn = document.querySelector('button[onclick*="rejected"]');

                    if (approveBtn) {
                        approveBtn.disabled = true;
                        approveBtn.innerHTML = approveBtn.innerHTML.replace('Setujui', 'Processing...');
                        console.log('Approve button disabled');
                    }
                    if (rejectBtn) {
                        rejectBtn.disabled = true;
                        rejectBtn.innerHTML = rejectBtn.innerHTML.replace('Tolak', 'Processing...');
                        console.log('Reject button disabled');
                    }

                    // Build route URL more carefully
                    const routeTemplate = `{{ route('admin.po-materials.update-status', ':id') }}`;
                    const routeUrl = routeTemplate.replace(':id', poMaterialId);
                    console.log('Route template:', routeTemplate);
                    console.log('Final route URL:', routeUrl);

                    // Create form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = routeUrl;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    console.log('CSRF token:', csrfToken.value);

                    // Add method override for PATCH
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PATCH';

                    // Add status
                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = status;

                    // Append inputs to form
                    form.appendChild(csrfToken);
                    form.appendChild(methodInput);
                    form.appendChild(statusInput);

                    // Add optional notes
                    const notes = prompt(`Tambahkan catatan untuk ${statusText} PO Material ini (opsional):`);
                    if (notes && notes.trim() !== '') {
                        const notesInput = document.createElement('input');
                        notesInput.type = 'hidden';
                        notesInput.name = 'notes';
                        notesInput.value = notes.trim();
                        form.appendChild(notesInput);
                        console.log('Added notes:', notes.trim());
                    }

                    console.log('Form elements:');
                    console.log('- Action:', form.action);
                    console.log('- Method:', form.method);
                    console.log('- Children count:', form.children.length);

                    // Log all form data
                    const formData = new FormData(form);
                    for (let [key, value] of formData.entries()) {
                        console.log(`- ${key}: ${value}`);
                    }

                    // Append form to body and submit
                    document.body.appendChild(form);
                    console.log('Form appended to body');

                    setTimeout(() => {
                        console.log('Submitting form...');
                        form.submit();
                    }, 100);

                } else {
                    console.log('User cancelled action');
                }

                console.log('=== PO APPROVAL DEBUG END ===');

            } catch (error) {
                console.error('Error in changeStatus function:', error);
                alert('Terjadi kesalahan: ' + error.message);

                // Re-enable buttons on error
                const buttons = document.querySelectorAll('button[onclick*="changeStatus"]');
                buttons.forEach(btn => {
                    btn.disabled = false;
                    btn.innerHTML = btn.innerHTML.replace('Processing...', 'Setujui').replace('Processing...', 'Tolak');
                });
            }

            return false;
        }
    </script>
    @endpush
</x-app-layout>
