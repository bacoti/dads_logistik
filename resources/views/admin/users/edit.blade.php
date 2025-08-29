<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-user-edit mr-2"></i>
                {{ __('Edit User: ') }} {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- User Info Card -->
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                            <h4 class="text-sm font-medium text-gray-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informasi User
                            </h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>ID:</strong> {{ $user->id }}</p>
                                <p><strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                                <p><strong>Terakhir Diupdate:</strong> {{ $user->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2"></i>
                                Email
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag mr-2"></i>
                                Role
                            </label>
                            <select name="role"
                                    id="role"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Fields (Optional) -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-lock mr-2"></i>
                                Ubah Password (Opsional)
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">Kosongkan jika tidak ingin mengubah password.</p>

                            <!-- New Password Field -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           minlength="8"
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                           placeholder="Kosongkan jika tidak diubah">
                                    <button type="button"
                                            onclick="togglePassword('password', 'password-icon')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           minlength="8"
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Konfirmasi password baru">
                                    <button type="button"
                                            onclick="togglePassword('password_confirmation', 'password-confirmation-icon')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i id="password-confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('admin.users.index') }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions Card -->
            @if($user->id !== auth()->id())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-bolt mr-2"></i>
                            Aksi Cepat
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-700 text-white font-bold py-2 px-4 rounded-lg flex items-center"
                                        onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} mr-2"></i>
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User
                                </button>
                            </form>

                            <!-- Reset Password -->
                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg flex items-center"
                                        onclick="return confirm('Yakin ingin reset password user ini? Password baru akan ditampilkan.')">
                                    <i class="fas fa-key mr-2"></i>
                                    Reset Password
                                </button>
                            </form>

                            <!-- Delete User -->
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg flex items-center"
                                        onclick="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-app-layout>
