<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('Tambah User Baru') }}
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
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap">
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
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="user@example.com">
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
                                <option value="">Pilih Role</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih role sesuai dengan tanggung jawab user dalam sistem.
                            </p>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2"></i>
                                Password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       required
                                       minlength="8"
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                       placeholder="Minimal 8 karakter">
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
                                <i class="fas fa-key mr-2"></i>
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       required
                                       minlength="8"
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Ulangi password">
                                <button type="button"
                                        onclick="togglePassword('password_confirmation', 'password-confirmation-icon')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i id="password-confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Role Description -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Deskripsi Role:
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li><strong>Administrator:</strong> Akses penuh ke sistem, termasuk manajemen user.</li>
                                <li><strong>User Lapangan:</strong> Dapat mengelola data lapangan dan transaksi.</li>
                                <li><strong>User PO:</strong> Dapat mengelola Purchase Order dan material.</li>
                            </ul>
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
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

        // Generate random password
        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%";
            let password = "";
            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }

            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;

            // Show generated password
            alert('Password yang dihasilkan: ' + password + '\n\nSilakan salin dan berikan kepada user.');
        }

        // Add generate password button
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password').parentNode;
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.className = 'mt-2 text-sm bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded-md';
            generateBtn.innerHTML = '<i class="fas fa-random mr-1"></i> Generate Password';
            generateBtn.onclick = generatePassword;
            passwordField.appendChild(generateBtn);
        });
    </script>
</x-app-layout>
