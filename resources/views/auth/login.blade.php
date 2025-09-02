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
            Selamat Datang Kembali
        </h2>
        <p class="text-gray-600 text-sm">Masuk ke sistem manajemen logistik</p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6 relative">
        @csrf

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
                       autofocus
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
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 {{ $errors->get('password') ? 'border-red-500 bg-red-50' : 'bg-white' }}">
                <button type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
            @if($errors->get('password'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->get('password')[0] }}</p>
            @endif
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center">
                <input type="checkbox"
                       name="remember"
                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <span class="ml-2 text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-red-600 hover:text-red-800 font-medium">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit"
                id="loginButton"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
            <span id="loginText">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Masuk
            </span>
        </button>

        <!-- Help Text -->
        {{-- Registration disabled
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Belum memiliki akun?
                <a href="{{ route('register') }}"
                   class="text-red-600 hover:text-red-800 font-semibold">
                    Daftar sekarang
                </a>
            </p>
        </div>
        --}}
    </form>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

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
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('loginText');

            button.disabled = true;
            button.classList.add('opacity-75');
            buttonText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sedang masuk...';
        });
    </script>
</x-guest-layout>
