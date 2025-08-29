<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Proyek</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub-Proyek</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($projects as $project)
                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
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
                            {{ $project->transactions_count }} transaksi
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <button @click="showEditModal = true; modalType = 'projects'; editData = {
                                id: {{ $project->id }},
                                name: '{{ $project->name }}',
                                code: '{{ $project->code }}',
                                description: '{{ $project->description }}'
                            }; subProjects = {{ $project->subProjects->pluck('name') }}"
                            class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.master-data.project.delete', $project) }}"
                                  class="inline-block"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini? Semua sub-proyek juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 font-medium"
                                        {{ $project->transactions_count > 0 ? 'disabled title="Tidak dapat dihapus karena masih digunakan dalam transaksi"' : '' }}>
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
                            @if($search)
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
