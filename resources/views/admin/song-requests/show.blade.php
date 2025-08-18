<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Song Request #{{ $songRequest->id }}</title>

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
                
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                        <!-- Animated background elements -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-4 right-6 w-16 h-16 bg-white rounded-full animate-pulse"></div>
                            <div class="absolute bottom-4 left-8 w-12 h-12 bg-white rounded-full animate-bounce"></div>
                            <div class="absolute top-1/2 left-1/2 w-8 h-8 bg-white rounded-full animate-ping"></div>
                        </div>

                        <div class="relative z-10">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold mb-2">🎵 Song Request #{{ $songRequest->id }}</h1>
                                    <p class="text-white/90 text-lg">{{ $songRequest->recipient_name }}</p>
                                </div>
                                <nav class="text-sm breadcrumbs">
                                    <ol class="flex space-x-2 text-white/70">
                                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Admin Studio</a></li>
                                        <li class="text-white/50">›</li>
                                        <li><a href="{{ route('admin.song-requests.index') }}" class="hover:text-white transition">Song Requests</a></li>
                                        <li class="text-white/50">›</li>
                                        <li class="text-white">#{{ $songRequest->id }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-100/95 backdrop-blur-sm border border-green-400 text-green-700 px-6 py-4 rounded-xl relative shadow-lg" 
                         role="alert"
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition.opacity.duration.300ms>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-green-700 hover:text-green-900">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100/95 backdrop-blur-sm border border-red-400 text-red-700 px-6 py-4 rounded-xl relative shadow-lg" 
                         role="alert"
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition.opacity.duration.300ms>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-red-700 hover:text-red-900">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Admin Song Request Show Component -->
                @livewire('admin.admin-song-request-show', ['songRequest' => $songRequest])
                
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>