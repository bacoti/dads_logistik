<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Material</label>
    <input type="text" name="name" id="name" value="{{ old('name', $material->name ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>
<div class="mb-4">
    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Material (misal: Kabel, Tiang)</label>
    <input type="text" name="type" id="type" value="{{ old('type', $material->type ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>
<div class="mb-4">
    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Awal</label>
    <input type="number" name="stock" id="stock" value="{{ old('stock', $material->stock ?? 0) }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>
<div class="mb-4">
    <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan (misal: Buah, Meter, Roll)</label>
    <input type="text" name="unit" id="unit" value="{{ old('unit', $material->unit ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.materials.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Batal</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
        {{ isset($material) ? 'Update' : 'Simpan' }}
    </button>
</div>
