<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Create Song Request</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-500 to-red-500">
        @livewire('dashboard-header')

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Create Song Request Header -->
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                        <!-- Animated background elements -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-4 right-6 w-16 h-16 bg-white rounded-full animate-pulse"></div>
                            <div class="absolute bottom-4 left-8 w-12 h-12 bg-white rounded-full animate-bounce"></div>
                            <div class="absolute top-1/2 left-1/2 w-8 h-8 bg-white rounded-full animate-ping"></div>
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-3xl font-bold mb-2">🎵 Create Song Request</h1>
                                    <p class="text-white/90 text-lg">Let's create something beautiful together</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white/80 text-sm">Ready to start?</p>
                                    <p class="text-xl font-semibold">Let's go! 🚀</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Song Request Form -->
                @livewire('create-song-request')
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>