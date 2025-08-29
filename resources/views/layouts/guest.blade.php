<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>PT DADS - Sistem Logistik</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @if(file_exists(public_path('images/logos/logo-small.png')))
            <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logos/logo-small.png') }}">
            <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logos/logo-small.png') }}">
            <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logos/logo-small.png') }}">
        @else
            <!-- Fallback favicon jika logo tidak ada -->
            <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚛</text></svg>">
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }

            /* Minimalist gradient background */
            .auth-bg {
                background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            }

            /* Subtle floating shapes */
            .floating-shapes {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                overflow: hidden;
                pointer-events: none;
            }

            .shape {
                position: absolute;
                background: rgba(255, 255, 255, 0.08);
                border-radius: 50%;
                animation: float 8s ease-in-out infinite;
            }

            .shape-1 {
                width: 100px;
                height: 100px;
                top: 15%;
                left: 15%;
                animation-delay: 0s;
            }

            .shape-2 {
                width: 80px;
                height: 80px;
                top: 60%;
                right: 20%;
                animation-delay: 3s;
            }

            .shape-3 {
                width: 60px;
                height: 60px;
                top: 35%;
                right: 35%;
                animation-delay: 6s;
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                    opacity: 0.6;
                }
                50% {
                    transform: translateY(-15px);
                    opacity: 1;
                }
            }

            /* Clean card design */
            .auth-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.98);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            /* Watermark animation */
            @keyframes watermark-pulse {
                0%, 100% {
                    opacity: 0.08;
                    transform: scale(1);
                }
                50% {
                    opacity: 0.12;
                    transform: scale(1.02);
                }
            }

            .watermark-logo {
                animation: watermark-pulse 8s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 auth-bg">

            <!-- Logo Watermark Background -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                @if(file_exists(public_path('images/logos/logo-small.png')))
                    <img src="{{ asset('images/logos/logo-small.png') }}"
                         alt="PT DADS Watermark"
                         class="w-96 h-96 object-contain opacity-10 watermark-logo"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-96 h-96 flex items-center justify-center opacity-10 watermark-logo" style="display: none;">
                        <div class="text-white text-9xl font-black">
                            PT<br>DADS
                        </div>
                    </div>
                @else
                    <div class="w-96 h-96 flex items-center justify-center opacity-10 watermark-logo">
                        <div class="text-white text-9xl font-black text-center leading-tight">
                            PT<br>DADS
                        </div>
                    </div>
                @endif
            </div>

            <!-- Floating Background Shapes -->
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>

            <!-- Main Content Card -->
            <div class="w-full max-w-sm mx-auto px-6 py-8 auth-card shadow-2xl rounded-2xl relative z-10">
                {{ $slot }}
            </div>

            <!-- Simple Footer -->
            <div class="mt-8 text-center text-red-100 text-xs relative z-10">
                <p>&copy; {{ date('Y') }} PT DADS. Semua hak dilindungi.</p>
            </div>
        </div>
    </body>
</html>
