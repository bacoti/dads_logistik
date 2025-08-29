<!-- Add/Edit Modal -->
<div x-show="showAddModal || showEditModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="showAddModal = false; showEditModal = false"></div>

    <!-- Modal container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Vendor Form -->
            <div x-show="modalType === 'vendors'" class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="showEditModal ? 'Edit Vendor' : 'Tambah Vendor Baru'"></h3>

                <form :action="showEditModal ? `/admin/master-data/vendor/${editData.id}` : '{{ route('admin.master-data.vendor.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" x-bind:value="showEditModal ? 'PUT' : 'POST'">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor *</label>
                        <input type="text" name="name" x-model="editData.name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                        <input type="text" name="contact_person" x-model="editData.contact_person"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" x-model="editData.phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editData.email"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" x-model="editData.address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAddModal = false; showEditModal = false"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <span x-text="showEditModal ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Project Form -->
            <div x-show="modalType === 'projects'" class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="showEditModal ? 'Edit Proyek' : 'Tambah Proyek Baru'"></h3>

                <form :action="showEditModal ? `/admin/master-data/project/${editData.id}` : '{{ route('admin.master-data.project.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" x-bind:value="showEditModal ? 'PUT' : 'POST'">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Proyek *</label>
                        <input type="text" name="code" x-model="editData.code" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Proyek *</label>
                        <input type="text" name="name" x-model="editData.name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="editData.description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub-Proyek</label>
                        <div class="space-y-2">
                            <template x-for="(subProject, index) in subProjects" :key="index">
                                <div class="flex items-center space-x-2">
                                    <input type="text" :name="`sub_projects[${index}]`" x-model="subProjects[index]"
                                           placeholder="Nama sub-proyek"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" @click="subProjects.splice(index, 1)" x-show="subProjects.length > 1"
                                            class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="subProjects.push('')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Sub-Proyek
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAddModal = false; showEditModal = false"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <span x-text="showEditModal ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Category Form -->
            <div x-show="modalType === 'categories'" class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="showEditModal ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h3>

                <form :action="showEditModal ? `/admin/master-data/category/${editData.id}` : '{{ route('admin.master-data.category.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" x-bind:value="showEditModal ? 'PUT' : 'POST'">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                        <input type="text" name="name" x-model="editData.name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="editData.description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAddModal = false; showEditModal = false"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <span x-text="showEditModal ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Material Form -->
            <div x-show="modalType === 'materials'" class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="showEditModal ? 'Edit Material' : 'Tambah Material Baru'"></h3>

                <form :action="showEditModal ? `/admin/master-data/material/${editData.id}` : '{{ route('admin.master-data.material.store') }}'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" x-bind:value="showEditModal ? 'PUT' : 'POST'">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Material *</label>
                        <input type="text" name="name" x-model="editData.name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="category_id" x-model="editData.category_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                        <input type="text" name="unit" x-model="editData.unit" required
                               placeholder="Contoh: kg, liter, pcs, m2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="editData.description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAddModal = false; showEditModal = false"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <span x-text="showEditModal ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
