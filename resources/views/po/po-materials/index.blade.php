<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-red-100 p-2 rounded-lg">
                <i class="fas fa-clipboard-list text-red-600 text-lg"></i>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    PO Materials
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola Purchase Order Material Anda</p>
            </div>
        </div>
    </x-slot>

    <!-- Content Container with Dynamic Width -->
    <div class="transition-all duration-300 ease-in-out">
        <div class="mx-auto px-4 sm:px-6 lg:px-8"
             :class="{
                 'max-w-7xl': true,
                 'pl-2': !$parent.sidebarOpen,
                 'pl-6': $parent.sidebarOpen
             }">

            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('po.po-materials.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah PO Material
                    </a>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fas fa-list-alt text-red-600"></i>
                    <span>Total: <strong class="text-gray-900">{{ $poMaterials->total() }}</strong> PO Material</span>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 backdrop-blur-sm shadow-lg rounded-2xl mb-6 border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-filter text-red-600 mr-2"></i>
                            Filter & Pencarian
                        </h3>
                        @if(request()->anyFilled(['search', 'status', 'project_id']))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-filter mr-1"></i>
                                Filter Aktif
                            </span>
                        @endif
                    </div>
                    <form method="GET" action="{{ route('po.po-materials.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search text-gray-400 mr-1"></i>
                                Pencarian
                            </label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="No. PO, Supplier, Material..."
                                   class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag text-gray-400 mr-1"></i>
                                Status
                            </label>
                            <select name="status" id="status" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🚫 Dibatalkan</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🔄 Aktif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-project-diagram text-gray-400 mr-1"></i>
                                Project
                            </label>
                            <select name="project_id" id="project_id" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 text-sm">
                                <option value="">Semua Project</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                            <a href="{{ route('po.po-materials.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium text-center shadow-sm hover:shadow-md transition-all duration-200">
                                <i class="fas fa-times mr-1"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PO Materials Table -->
            <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                @if($poMaterials->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-hashtag text-red-600"></i>
                                        <span>No. PO</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-building text-red-600"></i>
                                        <span>Supplier</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-red-600"></i>
                                        <span>Tanggal Rilis</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-project-diagram text-red-600"></i>
                                        <span>Project</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-box text-red-600"></i>
                                        <span>Material</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-balance-scale text-red-600"></i>
                                        <span>Qty</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-flag text-red-600"></i>
                                        <span>Status</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-cog text-red-600"></i>
                                        <span>Aksi</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($poMaterials as $poMaterial)
                            <tr class="hover:bg-red-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                                            <i class="fas fa-file-alt text-red-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $poMaterial->po_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-gray-400 mr-2"></i>
                                        {{ $poMaterial->supplier }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                        {{ $poMaterial->release_date ? \Carbon\Carbon::parse($poMaterial->release_date)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium flex items-center">
                                            <i class="fas fa-folder text-gray-400 mr-2"></i>
                                            {{ $poMaterial->project->name ?? 'N/A' }}
                                        </div>
                                        @if($poMaterial->subProject)
                                        <div class="text-gray-500 text-xs mt-1 ml-6">{{ $poMaterial->subProject->name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs">
                                        <div class="flex items-center mb-1">
                                            <i class="fas fa-box text-gray-400 mr-2 flex-shrink-0"></i>
                                            <span class="text-xs text-gray-500 font-medium">{{ $poMaterial->items->count() }} Material{{ $poMaterial->items->count() > 1 ? 's' : '' }}</span>
                                        </div>
                                        @if($poMaterial->items->count() > 0)
                                            @foreach($poMaterial->items->take(2) as $item)
                                            <div class="truncate text-xs text-gray-700 ml-6" title="{{ $item->description }}">
                                                • {{ $item->description }}
                                            </div>
                                            @endforeach
                                            @if($poMaterial->items->count() > 2)
                                            <div class="text-xs text-blue-600 ml-6 font-medium">
                                                +{{ $poMaterial->items->count() - 2 }} lainnya...
                                            </div>
                                            @endif
                                        @else
                                            <!-- Fallback untuk PO lama yang belum memiliki items -->
                                            <div class="truncate text-xs text-gray-700 ml-6" title="{{ $poMaterial->description }}">
                                                • {{ $poMaterial->description }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-balance-scale text-gray-400 mr-2"></i>
                                        @if($poMaterial->items->count() > 0)
                                            <div>
                                                @foreach($poMaterial->items->take(2) as $item)
                                                <div class="text-xs">{{ $item->formatted_quantity }}</div>
                                                @endforeach
                                                @if($poMaterial->items->count() > 2)
                                                <div class="text-xs text-blue-600">+{{ $poMaterial->items->count() - 2 }} lainnya</div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Fallback untuk PO lama -->
                                            <span class="font-medium">{{ $poMaterial->formatted_quantity }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $poMaterial->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('po.po-materials.show', $poMaterial) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($poMaterial->status === 'pending')
                                        <a href="{{ route('po.po-materials.edit', $poMaterial) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Approve Button -->
                                        <form action="{{ route('po.po-materials.update-status', $poMaterial) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('✅ Apakah Anda yakin ingin menyetujui PO Material ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-all duration-200" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <!-- Cancel Button -->
                                        <form action="{{ route('po.po-materials.update-status', $poMaterial) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('❌ Apakah Anda yakin ingin membatalkan PO Material ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 rounded-lg transition-all duration-200" title="Batalkan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('po.po-materials.destroy', $poMaterial) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('⚠️ Apakah Anda yakin ingin menghapus PO Material ini?\n\nData yang dihapus tidak dapat dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-all duration-200" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($poMaterials->hasPages())
                <div class="bg-gray-50/80 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($poMaterials->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $poMaterials->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Sebelumnya
                                </a>
                            @endif

                            @if ($poMaterials->hasMorePages())
                                <a href="{{ $poMaterials->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Selanjutnya
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 leading-5">
                                    Menampilkan
                                    <span class="font-medium">{{ $poMaterials->firstItem() }}</span>
                                    sampai
                                    <span class="font-medium">{{ $poMaterials->lastItem() }}</span>
                                    dari
                                    <span class="font-medium">{{ $poMaterials->total() }}</span>
                                    hasil
                                </p>
                            </div>
                            <div>
                                {{ $poMaterials->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-red-600 text-3xl"></i>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada PO Materials</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                        @if(request()->anyFilled(['search', 'status', 'project_id']))
                            Tidak ada data yang sesuai dengan filter yang dipilih. Coba ubah kriteria pencarian Anda.
                        @else
                            Mulai kelola PO Material dengan membuat yang pertama sekarang.
                        @endif
                    </p>
                    <div class="mt-8 flex justify-center space-x-3">
                        @if(request()->anyFilled(['search', 'status', 'project_id']))
                            <a href="{{ route('po.po-materials.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Reset Filter
                            </a>
                        @endif
                        <a href="{{ route('po.po-materials.create') }}" class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-[1.02] transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah PO Material
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
