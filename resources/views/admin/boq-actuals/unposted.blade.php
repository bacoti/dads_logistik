<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('BOQ Belum Diposting') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-medium">Daftar BOQ dengan sisa > 0</h3>

                    <form method="GET" action="{{ route('admin.boq-actuals.unposted') }}" class="mt-4 flex items-end space-x-3">
                        <div>
                            <label class="block text-sm text-gray-600">Proyek</label>
                            <select id="filterProject" name="project_id" class="mt-1 block w-64 border-gray-300 rounded-md">
                                <option value="">-- Semua Proyek --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ request()->get('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Sub Proyek</label>
                            <select id="filterSubProject" name="sub_project_id" class="mt-1 block w-64 border-gray-300 rounded-md">
                                <option value="">-- Semua Sub Proyek --</option>
                                @foreach($subProjects as $sp)
                                    <option value="{{ $sp->id }}" data-project="{{ $sp->project_id }}" {{ request()->get('sub_project_id') == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
                            <a href="{{ route('admin.boq-actuals.unposted') }}" class="px-4 py-2 bg-gray-200 rounded">Reset</a>
                            <a href="{{ route('boq-actuals.unposted.export', request()->all()) }}" class="px-4 py-2 bg-green-600 text-white rounded">Export CSV</a>
                            <a href="{{ route('boq-actuals.unposted.export.xlsx', request()->all()) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Export XLSX</a>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2">ID</th>
                                <th class="p-2">Proyek</th>
                                <th class="p-2">Sub Proyek</th>
                                <th class="p-2">Cluster</th>
                                <th class="p-2">Material</th>
                                <th class="p-2">BOQ Qty</th>
                                <th class="p-2">Posted</th>
                                <th class="p-2">Sisa</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boqs as $boq)
                                <tr class="border-b">
                                    <td class="p-2">{{ $boq->id }}</td>
                                    <td class="p-2">{{ $boq->project->name }}</td>
                                    <td class="p-2">{{ $boq->subProject->name }}</td>
                                    <td class="p-2">{{ $boq->cluster }}</td>
                                    <td class="p-2">{{ $boq->material->name }}</td>
                                    <td class="p-2">{{ number_format($boq->actual_quantity,4) }} {{ $boq->material->unit }}</td>
                                    <td class="p-2">{{ number_format($boq->posted_quantity ?? 0,4) }}</td>
                                    <td class="p-2 font-bold text-green-600">{{ number_format($boq->remaining_qty,4) }}</td>
                                    <td class="p-2">
                                        <a href="{{ route('admin.boq-actuals.show', $boq) }}" class="text-blue-600">Detail / Buat Pemakaian</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="p-4" colspan="9">Tidak ada BOQ dengan sisa untuk diposting.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $boqs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
    // Filter sub-project options based on selected project
    document.addEventListener('DOMContentLoaded', function() {
        var projectSelect = document.getElementById('filterProject');
        var subSelect = document.getElementById('filterSubProject');
        if (!projectSelect || !subSelect) return;

        function filterSubs() {
            var selected = projectSelect.value;
            Array.from(subSelect.options).forEach(function(opt) {
                if (opt.value === '') { opt.style.display = ''; return; }
                var proj = opt.getAttribute('data-project');
                if (selected === '' || selected === proj) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        projectSelect.addEventListener('change', filterSubs);
        // initial filter run
        filterSubs();
    });
</script>
