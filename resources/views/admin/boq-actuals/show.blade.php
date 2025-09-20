<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail BOQ Actual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-white">Detail BOQ Actual</h3>
                            <p class="text-green-100 text-sm">Informasi lengkap data pemakaian material aktual</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.boq-actuals.edit', $boqActual) }}"
                               class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-sm font-medium text-white hover:bg-opacity-30 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('admin.boq-actuals.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-sm font-medium text-white hover:bg-opacity-30 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Main Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Project Information -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Informasi Proyek</h4>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Proyek</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->project->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Sub Proyek</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->subProject->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Cluster</label>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $boqActual->cluster }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nomor DN</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->dn_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Material Information -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Informasi Material</h4>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nama Material</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->material->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $boqActual->material->category->name ?? 'No Category' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">BOQ Actual (Target)</label>
                                    <p class="mt-1">
                                        <span class="text-2xl font-bold text-blue-600">{{ number_format($boqActual->actual_quantity, 2) }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $boqActual->material->unit }}</span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Actual Usage (Real)</label>
                                    <p class="mt-1">
                                        <span class="text-2xl font-bold text-green-600">{{ number_format($boqActual->actual_usage, 2) }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $boqActual->material->unit }}</span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tanggal Pemakaian</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $boqActual->usage_date->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Update Actual Usage Section -->
                        <div class="space-y-6">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Update Actual Usage</h4>

                            <form id="updateUsageForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="actual_usage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Actual Usage <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number"
                                               name="actual_usage"
                                               id="actual_usage"
                                               value="{{ old('actual_usage', $boqActual->actual_usage) }}"
                                               step="0.01"
                                               min="0"
                                               required
                                               placeholder="0.00"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm">{{ $boqActual->material->unit }}</span>
                                        </div>
                                    </div>
                                    @error('actual_usage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="usage_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan Pemakaian
                                    </label>
                                    <textarea name="usage_notes"
                                              id="usage_notes"
                                              rows="3"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                              placeholder="Tambahkan catatan tentang pemakaian material...">{{ old('usage_notes', $boqActual->notes) }}</textarea>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <button type="submit"
                                            id="updateUsageBtn"
                                            class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span id="btnText">Update Usage</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($boqActual->notes)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Catatan</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $boqActual->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- System Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Sistem</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Admin Input</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Input</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->created_at->format('d F Y H:i') }}</p>
                            </div>

                            @if($boqActual->updated_at != $boqActual->created_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Terakhir Diperbarui</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $boqActual->updated_at->format('d F Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.boq-actuals.edit', $boqActual) }}"
                               class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Data
                            </a>
                        </div>

                        <form action="{{ route('admin.boq-actuals.destroy', $boqActual) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus data BOQ Actual ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
document.getElementById('updateUsageForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const btn = document.getElementById('updateUsageBtn');
    const btnText = document.getElementById('btnText');

    if (!btn || !btnText) {
        console.error('Button elements not found');
        return;
    }

    const originalText = btnText.textContent;

    // === OPSI 1: Enhanced Loading State dengan Progress ===
    enhancedLoadingState(btn, btnText, originalText, formData);
});

// === OPSI 1: Enhanced Loading State dengan Progress ===
function enhancedLoadingState(btn, btnText, originalText, formData) {
    // Disable button dan tampilkan loading
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    btnText.innerHTML = `
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Updating...</span>
            <div class="ml-2 w-16 bg-white bg-opacity-30 rounded-full h-1">
                <div class="bg-white h-1 rounded-full animate-pulse" style="width: 30%"></div>
            </div>
        </div>
    `;

    // Langsung reload page setelah request dikirim
    fetch('{{ route("admin.boq-actuals.update-usage", $boqActual) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Langsung reload tanpa menunggu animasi
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        // Bahkan jika error, tetap reload
        window.location.reload();
    });
}

// === OPSI 2: Real-time Update tanpa Reload ===
function realTimeUpdate(btn, btnText, originalText, formData) {
    // Disable button
    btn.disabled = true;
    btnText.innerHTML = `
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Updating...</span>
        </div>
    `;

    fetch('{{ route("admin.boq-actuals.update-usage", $boqActual) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Update nilai secara real-time tanpa reload
            const newUsageValue = document.getElementById('actual_usage').value;
            const usageDisplay = document.querySelector('.text-green-600');

            if (usageDisplay) {
                // Animate the value change
                usageDisplay.style.transition = 'all 0.5s ease';
                usageDisplay.style.transform = 'scale(1.1)';
                usageDisplay.style.color = '#10B981';

                setTimeout(() => {
                    usageDisplay.textContent = parseFloat(newUsageValue).toFixed(2);
                    usageDisplay.style.transform = 'scale(1)';
                }, 250);
            }

            // Success feedback
            btnText.innerHTML = `
                <div class="flex items-center text-green-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Updated!</span>
                </div>
            `;

            // Show toast notification
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });

            // Reset button
            setTimeout(() => {
                btn.disabled = false;
                btnText.textContent = originalText;
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btnText.innerHTML = `
            <div class="flex items-center text-red-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Failed</span>
            </div>
        `;

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat memperbarui data',
            timer: 3000,
            showConfirmButton: false
        });

        setTimeout(() => {
            btn.disabled = false;
            btnText.textContent = originalText;
        }, 3000);
    });
}

// === OPSI 3: Animated Success dengan Confetti ===
function animatedSuccess(btn, btnText, originalText, formData) {
    fetch('{{ route("admin.boq-actuals.update-usage", $boqActual) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Trigger confetti animation
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });

            // Success animation
            btnText.innerHTML = `
                <div class="flex items-center text-yellow-300 animate-bounce">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span>🎉 Success!</span>
                </div>
            `;

            // Show success message with animation
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '🎉 Berhasil!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false,
                    animation: true,
                    customClass: {
                        popup: 'animate__animated animate__bounceIn'
                    }
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat memperbarui data',
            timer: 3000,
            showConfirmButton: false
        });
    });
}
</script>

<!-- Confetti Library (uncomment jika menggunakan OPSI 3) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script> -->
