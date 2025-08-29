<!-- Simple Approval Form - Alternative Solution -->
<!-- Ganti bagian tombol di show.blade.php dengan form ini -->

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

            <!-- APPROVE FORM -->
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

            <!-- REJECT FORM -->
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

        </div>
    </div>
</div>
@endif
