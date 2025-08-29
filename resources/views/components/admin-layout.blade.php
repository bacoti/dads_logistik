<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="min-h-screen flex">
            <!-- Side Navigation -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out w-full"
                 :class="{
                     'lg:ml-64': sidebarOpen,
                     'lg:ml-16': !sidebarOpen,
                     'ml-0': true
                 }">
                <!-- Top Navigation Bar -->
                @include('layouts.topbar')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 shadow-sm">
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    {{ $header }}
                                </div>
                            </div>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 p-6 overflow-y-auto">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white/80 backdrop-blur-sm border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-copyright text-red-600"></i>
                            <span>{{ date('Y') }} PT DADS Logistik. All rights reserved.</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-user-circle text-red-600"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-id-badge text-red-600"></i>
                                <span class="capitalize">{{ Auth::user()->role }}</span>
                            </span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
