<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header dengan tombol tambah -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Proyek</h3>
                            <p class="text-gray-600">Kelola proyek utama dan sub-proyek yang tersedia dalam sistem</p>
                        </div>
                        <a href="{{ route('admin.projects.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Proyek
                        </a>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari nama proyek, kode, atau deskripsi..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <button type="submit"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.projects.index') }}"
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Tabel Proyek -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Proyek
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Proyek
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sub-Proyek
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Transaksi
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($projects as $project)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $projects->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-blue-600 font-mono">{{ $project->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                            @if($project->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($project->subProjects->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($project->subProjects->take(2) as $subProject)
                                                        <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                            {{ $subProject->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($project->subProjects->count() > 2)
                                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                                            +{{ $project->subProjects->count() - 2 }} lainnya
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $project->transactions->count() }} transaksi
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.projects.show', $project) }}"
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    Detail
                                                </a>
                                                <a href="{{ route('admin.projects.edit', $project) }}"
                                                   class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                                      class="inline-block"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini? Semua sub-proyek juga akan terhapus.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800 font-medium"
                                                            {{ $project->transactions->count() > 0 ? 'disabled title="Tidak dapat dihapus karena masih digunakan dalam transaksi"' : '' }}>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                @if(request('search'))
                                                    <p class="text-lg font-medium">Tidak ada proyek yang ditemukan</p>
                                                    <p class="text-sm">Coba gunakan kata kunci pencarian lain.</p>
                                                @else
                                                    <p class="text-lg font-medium">Belum ada proyek yang terdaftar</p>
                                                    <p class="text-sm">Tambah proyek pertama untuk memulai.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($projects->hasPages())
                        <div class="mt-6">
                            {{ $projects->appends(request()->query())->links() }}
                        </div>
                    @endif

                    <!-- Summary -->
                    <div class="mt-6 text-sm text-gray-600 flex justify-between items-center">
                        <div>
                            Menampilkan {{ $projects->count() }} dari {{ $projects->total() }} proyek
                        </div>
                        @if(request('search'))
                            <div>
                                Hasil pencarian untuk: "<strong>{{ request('search') }}</strong>"
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
