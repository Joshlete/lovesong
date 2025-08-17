<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LoveSong') }} - @yield('title', 'Authentication')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 min-h-screen">
    
    <!-- Back to Home Button -->
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ url('/') }}" class="inline-flex items-center text-white/80 hover:text-white transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Home
        </a>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 p-8 text-center">
                    <div class="text-3xl mb-4">@yield('icon')</div>
                    <h1 class="text-2xl font-bold text-white mb-2">@yield('heading')</h1>
                    <p class="text-white/90 text-sm">@yield('subheading')</p>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-white/60 text-sm">&copy; {{ date('Y') }} LoveSong. Made with ❤️ by professional musicians.</p>
            </div>
        </div>
    </div>
</body>
</html>
