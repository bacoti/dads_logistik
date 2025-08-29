<x-guest-layout>
    <!-- Logo Section -->
    <div class="flex justify-center mb-6">
        <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center">
            @if(file_exists(public_path('images/logos/logo-small.png')))
                <img src="{{ asset('images/logos/logo-small.png') }}"
                     alt="PT DADS Logo"
                     class="w-10 h-10 object-contain"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="text-red-600 text-xl font-bold" style="display: none;">
                    PT
                </div>
            @else
                <div class="text-red-600 text-xl font-bold">
                    PT
                </div>
            @endif
        </div>
    </div>

    <!-- Welcome Header -->
    <div class="text-center mb-8 relative">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Bergabung Dengan Kami
        </h2>
        <p class="text-gray-600 text-sm">Daftar untuk akses sistem manajemen logistik</p>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6 relative">
        @csrf

        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       autocomplete="name"
                       placeholder="Masukkan nama lengkap"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->get('name') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
            </div>
            @if($errors->get('name'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->get('name')[0] }}</p>
            @endif
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="username"
                       placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->get('email') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
            </div>
            @if($errors->get('email'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->get('email')[0] }}</p>
            @endif
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->get('password') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                <button type="button"
                        onclick="togglePassword('password', 'password-icon')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->get('password'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->get('password')[0] }}</p>
            @endif
        </div>

        <!-- Confirm Password Field -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Konfirmasi Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-gray-400"></i>
                </div>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi password"
                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->get('password_confirmation') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                <button type="button"
                        onclick="togglePassword('password_confirmation', 'password-confirmation-icon')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i id="password-confirmation-icon" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->get('password_confirmation'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->get('password_confirmation')[0] }}</p>
            @endif
        </div>

        <!-- Terms Agreement -->
        <div class="flex items-center">
            <input type="checkbox"
                   id="terms"
                   required
                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
            <label for="terms" class="ml-3 text-sm text-gray-600">
                Saya setuju dengan
                <a href="#" class="text-red-600 hover:text-red-800 font-medium">
                    syarat dan ketentuan
                </a>
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit"
                id="registerButton"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
            <span id="registerText">
                <i class="fas fa-user-plus mr-2"></i>
                Daftar
            </span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">atau</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                   class="text-red-600 hover:text-red-800 font-semibold">
                    Masuk sekarang
                </a>
            </p>
        </div>
    </form>

    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = document.getElementById('registerButton');
            const buttonText = document.getElementById('registerText');

            button.disabled = true;
            button.classList.add('opacity-75');
            buttonText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sedang mendaftar...';
        });
    </script>
</x-guest-layout>
