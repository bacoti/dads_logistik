<x-app-layout>
    <x-page-header 
        title="Catat Penggunaan Material" 
        subtitle="Catat material yang digunakan untuk proyek"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('po.dashboard'), 'icon' => '<svg class=\'w-5 h-5 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
            ['title' => 'Material Usage', 'url' => route('po.material-usage.index')],
            ['title' => 'Catat Penggunaan']
        ]"
        :icon="'<svg class=\'w-8 h-8 mr-3\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'></path></svg>'"
        >
        <x-slot name="action">
            <x-button 
                variant="secondary"
                href="{{ route('po.material-usage.index') }}"
                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M10 19l-7-7m0 0l7-7m-7 7h18\'></path></svg>'"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                Kembali
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Available Stock Summary -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Stock Material yang Tersedia</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $materialStocks->count() }} material tersedia untuk digunakan</p>
                </div>
                
                @if($materialStocks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Tersedia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($materialStocks as $stock)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Material #{{ $stock->id }}</div>
                                                <div class="text-sm text-gray-500">{{ $stock->unit }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->current_stock, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($stock->current_stock <= $stock->minimum_stock)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Stock Rendah
                                            </span>
                                        @elseif($stock->current_stock <= ($stock->minimum_stock * 2))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Perhatian
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Stock Material</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada material yang diterima dan tersedia untuk digunakan.</p>
                </div>
                @endif
            </div>

            <!-- Usage Form -->
            @if($materialStocks->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Form Penggunaan Material</h3>
                    <p class="mt-1 text-sm text-gray-600">Pilih material yang akan digunakan dan masukkan jumlah penggunaan</p>
                </div>
                
                <form action="{{ route('po.material-usage.store') }}" method="POST" x-data="usageForm()">
                    @csrf
                    <div class="p-6">
                        <!-- Usage Date -->
                        <div class="mb-6">
                            <label for="usage_date" class="block text-sm font-medium text-gray-700">Tanggal Penggunaan</label>
                            <input type="date" 
                                   name="usage_date" 
                                   id="usage_date"
                                   value="{{ old('usage_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('usage_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Selection -->
                        <div class="mb-6">
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                            <select name="project_id" id="project_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Pilih Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Activity Name -->
                        <div class="mb-6">
                            <label for="activity_name" class="block text-sm font-medium text-gray-700">Nama Aktivitas</label>
                            <input type="text" 
                                   name="activity_name" 
                                   id="activity_name"
                                   value="{{ old('activity_name') }}"
                                   placeholder="Contoh: Pemasangan Tiang, Pengecoran, dll"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('activity_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PIC Name -->
                        <div class="mb-6">
                            <label for="pic_name" class="block text-sm font-medium text-gray-700">PIC (Person in Charge)</label>
                            <input type="text" 
                                   name="pic_name" 
                                   id="pic_name"
                                   value="{{ old('pic_name') }}"
                                   placeholder="Nama penanggung jawab aktivitas ini"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('pic_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700">Lokasi (Opsional)</label>
                            <input type="text" 
                                   name="location" 
                                   id="location"
                                   value="{{ old('location') }}"
                                   placeholder="Lokasi penggunaan material"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Material Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Material yang Digunakan</label>
                            @error('materials')
                                <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div x-data="{ usageItems: [{ material_stock_id: '', quantity_used: '', notes: '' }] }">
                                <template x-for="(item, index) in usageItems" :key="index">
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Material Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Material</label>
                                                <select :name="'materials[' + index + '][material_stock_id]'" 
                                                        x-model="item.material_stock_id"
                                                        required
                                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    <option value="">Pilih Material</option>
                                                    @foreach($materialStocks as $stock)
                                                        <option value="{{ $stock->id }}" 
                                                                data-available="{{ $stock->current_stock }}"
                                                                data-unit="{{ $stock->unit }}">
                                                            {{ $stock->material_name ?: 'Material #' . $stock->id }} 
                                                            ({{ number_format($stock->current_stock, 0) }} {{ $stock->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("materials.*.material_stock_id")
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Quantity -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jumlah Digunakan</label>
                                                <input type="number" 
                                                       :name="'materials[' + index + '][quantity_used]'" 
                                                       x-model="item.quantity_used"
                                                       min="0" 
                                                       step="0.01"
                                                       placeholder="0"
                                                       required
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                @error("materials.*.quantity_used")
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="flex items-end">
                                                <button type="button" 
                                                        x-show="usageItems.length > 1"
                                                        @click="usageItems.splice(index, 1)"
                                                        class="w-full px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700">Tujuan/Catatan Penggunaan</label>
                                            <textarea :name="'materials[' + index + '][notes]'"
                                                      x-model="item.notes"
                                                      rows="2"
                                                      placeholder="Untuk proyek apa, lokasi penggunaan, dll..."
                                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                        </div>
                                    </div>
                                </template>

                                <!-- Add More Button -->
                                <button type="button" 
                                        @click="usageItems.push({ material_stock_id: '', quantity_used: '', notes: '' })"
                                        class="mb-6 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Material Lain
                                </button>
                            </div>
                        </div>

                        <!-- General Notes -->
                        <div class="mb-6">
                            <label for="general_notes" class="block text-sm font-medium text-gray-700">Catatan Umum</label>
                            <textarea name="general_notes" 
                                      id="general_notes"
                                      rows="3"
                                      placeholder="Catatan umum mengenai penggunaan material ini..."
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('general_notes') }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <x-button 
                                variant="secondary"
                                href="{{ route('po.material-usage.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700">
                                Batal
                            </x-button>
                            <x-button 
                                type="submit"
                                variant="primary"
                                :icon="'<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'"
                                class="bg-blue-600 hover:bg-blue-700 text-white">
                                Simpan Penggunaan
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <script>
        function usageForm() {
            return {
                init() {
                    // Form initialization logic if needed
                }
            }
        }
    </script>
</x-app-layout>
