<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - {{ $songRequest->recipient_name }}</title>

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

                <!-- Song Request Header -->
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
                                    <h1 class="text-3xl font-bold mb-2">🎵 {{ $songRequest->recipient_name }}</h1>
                                    <p class="text-white/90 text-lg">
                                        @if($songRequest->status === 'pending')
                                            ⏳ Your song request is waiting to be reviewed
                                        @elseif($songRequest->status === 'in_progress')
                                            🎵 Your song is currently being created
                                        @elseif($songRequest->status === 'completed')
                                            ✅ Your song has been completed!
                                        @else
                                            ❌ This request has been cancelled
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="flex space-x-3">
                                        @if($songRequest->status === 'pending')
                                            <a href="{{ route('song-requests.edit', $songRequest) }}" 
                                               class="bg-yellow-400 text-purple-900 px-4 py-2 rounded-full font-bold hover:bg-yellow-300 transform hover:scale-105 transition">
                                                ✏️ Edit
                                            </a>
                                        @endif
                                        <a href="{{ route('song-requests.index') }}" 
                                           class="bg-white/20 text-white px-4 py-2 rounded-full font-medium hover:bg-white/30 transition">
                                            ← Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Banner -->
                <div class="mb-8">
                    <div class="
                        @if($songRequest->status === 'pending') bg-yellow-50/95 border-yellow-200
                        @elseif($songRequest->status === 'in_progress') bg-blue-50/95 border-blue-200
                        @elseif($songRequest->status === 'completed') bg-green-50/95 border-green-200
                        @else bg-red-50/95 border-red-200
                        @endif
                        backdrop-blur-sm rounded-2xl p-6 border shadow-lg">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                                @if($songRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($songRequest->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($songRequest->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($songRequest->status === 'pending') ⏳
                                @elseif($songRequest->status === 'in_progress') 🎵
                                @elseif($songRequest->status === 'completed') ✅
                                @else ❌
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $songRequest->status)) }}
                            </span>
                            <span class="ml-4 text-sm 
                                @if($songRequest->status === 'pending') text-yellow-700
                                @elseif($songRequest->status === 'in_progress') text-blue-700
                                @elseif($songRequest->status === 'completed') text-green-700
                                @else text-red-700
                                @endif">
                                Request created {{ $songRequest->created_at->format('F j, Y \a\t g:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($songRequest->status === 'pending' && $songRequest->payment_status !== 'succeeded')
                    <div class="mb-8">
                        <div class="bg-orange-50/95 backdrop-blur-sm border border-orange-200 rounded-2xl p-6 shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-orange-900 mb-2">💰 Payment Required</h3>
                                    <p class="text-sm text-orange-700 mb-3">Complete payment to begin work on your custom song.</p>
                                    <p class="text-2xl font-bold text-orange-900">${{ number_format($songRequest->price_usd, 2) }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('payments.show', $songRequest) }}" 
                                       class="block w-full sm:w-auto bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-3 px-6 rounded-xl transform hover:scale-105 transition shadow-lg text-center">
                                        Complete Payment
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($songRequest->payment_status === 'succeeded')
                    <div class="mb-8">
                        <div class="bg-green-50/95 backdrop-blur-sm border border-green-200 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-400 mr-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-xl font-bold text-green-900">✅ Payment Complete</h3>
                                    <p class="text-sm text-green-700">
                                        Payment received!
                                        @if($songRequest->payment_completed_at)
                                            <br><small>Paid: {{ $songRequest->payment_completed_at->format('M j, Y g:i A') }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Song Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column: Song Information -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20 space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">🎼 Song Details</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Song Title</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $songRequest->recipient_name }}</p>
                        </div>

                        @if($songRequest->song_description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900">{{ $songRequest->song_description }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($songRequest->style)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Style</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    🎼 {{ ucfirst($songRequest->style) }}
                                </span>
                            </div>
                            @endif

                            @if($songRequest->mood)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mood</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    😊 {{ ucfirst($songRequest->mood) }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                            <p class="text-2xl font-bold text-gray-900">
                                ${{ number_format($songRequest->price_usd, 2) }}
                                <span class="text-sm font-normal text-gray-500">{{ $songRequest->currency }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Column: Timeline & Status -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20 space-y-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">📅 Timeline</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 text-sm">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Request Created</p>
                                    <p class="text-sm text-gray-500">{{ $songRequest->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>

                            @if($songRequest->payment_completed_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 text-sm">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Payment Received</p>
                                    <p class="text-sm text-gray-500">{{ $songRequest->payment_completed_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($songRequest->delivered_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 text-sm">✓</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Song Delivered</p>
                                    <p class="text-sm text-gray-500">{{ $songRequest->delivered_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($songRequest->payment_reference)
                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference</label>
                                <p class="text-sm font-mono text-gray-900 bg-gray-50 p-2 rounded">{{ $songRequest->payment_reference }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Lyrics Ideas -->
                @if($songRequest->lyrics_idea)
                <div class="mb-8">
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">✍️ Lyrics Ideas & Themes</h3>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songRequest->lyrics_idea }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Download Section -->
                @if($songRequest->hasFile() && $songRequest->status === 'completed')
                <div class="mb-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 shadow-xl">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mr-4">
                                <span class="text-3xl">🎉</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-green-800">Your song is ready!</h3>
                                <p class="text-green-600">Your custom love song has been completed and is ready for download.</p>
                            </div>
                        </div>
                        
                        @if($songRequest->hasS3File())
                            <div class="bg-white rounded-xl p-4 mb-6 border border-green-200 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $songRequest->getDisplayFilename() }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $songRequest->formatted_file_size }} • 
                                            @if($songRequest->delivered_at)
                                                Delivered {{ $songRequest->delivered_at->format('M j, Y g:i A') }}
                                            @else
                                                Available for download
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center text-green-600">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span class="text-sm">Secure</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('song-requests.download', $songRequest) }}" 
                               download
                               class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-xl text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition shadow-lg">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Song
                            </a>
                            
                            @if($songRequest->hasS3File())
                                <div class="flex items-center text-sm text-green-700 bg-green-100 px-4 py-2 rounded-xl">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Secure download
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions for Pending Requests -->
                @if($songRequest->status === 'pending')
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">Need to make changes?</h3>
                            <p class="text-sm text-gray-600">You can edit your request while it's still pending.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                            <a href="{{ route('song-requests.edit', $songRequest) }}" 
                               class="w-full sm:w-auto bg-blue-100 text-blue-700 hover:bg-blue-200 px-6 py-3 rounded-xl font-medium transition transform hover:scale-105 text-center">
                                ✏️ Edit Request
                            </a>
                            <form method="POST" 
                                  action="{{ route('song-requests.destroy', $songRequest) }}" 
                                  class="w-full sm:w-auto"
                                  onsubmit="return confirm('Are you sure you want to delete this song request? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full sm:w-auto bg-red-100 text-red-700 hover:bg-red-200 px-6 py-3 rounded-xl font-medium transition transform hover:scale-105">
                                    🗑️ Delete Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>