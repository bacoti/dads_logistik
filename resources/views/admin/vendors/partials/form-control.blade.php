<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Vendor</label>
    <input type="text" name="name" id="name" value="{{ old('name', $vendor->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
</div>
<div class="mb-4">
    <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kontak Person</label>
    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $vendor->contact_person ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
</div>
<div class="mb-4">
    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
    <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
</div>
<div class="mb-4">
    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
    <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('address', $vendor->address ?? '') }}</textarea>
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.vendors.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Batal</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
        {{ isset($vendor) ? 'Update' : 'Simpan' }}
    </button>
</div>
