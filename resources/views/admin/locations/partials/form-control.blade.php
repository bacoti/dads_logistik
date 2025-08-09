<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lokasi (Gudang)</label>
    <input type="text" name="name" id="name" value="{{ old('name', $location->name ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>
<div class="mb-4">
    <label for="cluster_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Cluster</label>
    <input type="text" name="cluster_name" id="cluster_name" value="{{ old('cluster_name', $location->cluster_name ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>
<div class="mb-4">
    <label for="site_id_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site ID Cluster</label>
    <input type="text" name="site_id_cluster" id="site_id_cluster" value="{{ old('site_id_cluster', $location->site_id_cluster ?? '') }}" class="mt-1 block w-full rounded-md dark:bg-gray-700" required>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.locations.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Batal</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
        {{ isset($location) ? 'Update' : 'Simpan' }}
    </button>
</div>
