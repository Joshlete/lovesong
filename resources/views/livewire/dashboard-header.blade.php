<nav x-data="{ open: false }" wire:init="initLocalTime" class="relative bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 border-b border-white/20 shadow-xl">
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-20 h-20 bg-white rounded-full animate-pulse"></div>
        <div class="absolute top-0 right-1/3 w-16 h-16 bg-white rounded-full animate-bounce"></div>
        <div class="absolute bottom-0 left-1/2 w-12 h-12 bg-white rounded-full animate-ping"></div>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo with Energy -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <span class="text-2xl font-black text-white flex items-center transform group-hover:scale-110 transition-all duration-300">
                            <span class="mr-2 text-3xl animate-bounce">🎵</span>
                            <span>LoveSong</span>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Center Stats (Hidden on small screens) -->
            <div class="hidden lg:flex items-center space-x-6">
                <!-- Time-based Message -->
                <div class="text-white/90 text-sm font-medium">
                    {{ $timeBasedMessage }}
                </div>
                
                @if($quickStats['total_songs'] > 0)
                    <div class="flex items-center space-x-3">
                        <!-- Total Songs -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">{{ $quickStats['total_songs'] }}</div>
                                <div class="text-xs text-white/80">Songs</div>
                            </div>
                        </div>

                        <!-- Completed Songs -->
                        @if($quickStats['completed_songs'] > 0)
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-yellow-300">{{ $quickStats['completed_songs'] }}</div>
                                    <div class="text-xs text-white/80">Ready</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Primary Action Button -->
                <a href="{{ route('song-requests.create') }}" 
                   class="bg-yellow-400 text-purple-900 px-4 py-2 rounded-full font-bold hover:bg-yellow-300 transform hover:scale-105 transition shadow-lg animate-pulse">
                    + Create Song
                </a>

                <!-- Navigation Links -->
                <div class="flex space-x-4">
                    <a href="{{ route('song-requests.index') }}" 
                       class="text-white hover:text-yellow-300 transition font-medium px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('song-requests.*') ? 'bg-white/20 text-yellow-300' : '' }}">
                        🎼 My Songs
                    </a>
                    @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-white hover:text-yellow-300 transition font-medium px-3 py-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.*') ? 'bg-white/20 text-yellow-300' : '' }}">
                            ⚡ Admin
                        </a>
                    @endif
                </div>

                <!-- Settings Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center text-white hover:text-yellow-300 transition">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        @endif
                        <span class="font-medium">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50"
                         style="display: none;">
                        
                        <div class="px-4 py-2 text-xs text-gray-400 border-b">
                            Manage Account
                        </div>
                        
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 transition">
                            Profile
                        </a>
                        
                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <a href="{{ route('api-tokens.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 transition">
                                API Tokens
                            </a>
                        @endif
                        
                        <div class="border-t border-gray-200 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" 
                        class="text-white hover:text-yellow-300 focus:outline-none transition p-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="sm:hidden bg-white/10 backdrop-blur-sm border-t border-white/20"
         style="display: none;">
        
        <div class="px-4 py-4 space-y-3">
            <!-- Mobile Stats -->
            @if($quickStats['total_songs'] > 0)
                <div class="flex justify-center space-x-4 mb-4">
                    <div class="bg-white/20 rounded-lg px-3 py-2 text-center">
                        <div class="text-white font-bold">{{ $quickStats['total_songs'] }}</div>
                        <div class="text-white/80 text-xs">Songs</div>
                    </div>
                    @if($quickStats['completed_songs'] > 0)
                        <div class="bg-white/20 rounded-lg px-3 py-2 text-center">
                            <div class="text-yellow-300 font-bold">{{ $quickStats['completed_songs'] }}</div>
                            <div class="text-white/80 text-xs">Ready</div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Mobile Create Button -->
            <a href="{{ route('song-requests.create') }}" 
               class="block bg-yellow-400 text-purple-900 px-4 py-3 rounded-lg font-bold text-center hover:bg-yellow-300 transition animate-pulse">
                + Create New Song
            </a>

            <!-- Mobile Links -->
            <a href="{{ route('song-requests.index') }}" 
               class="block text-white hover:text-yellow-300 transition font-medium py-2 px-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('song-requests.*') ? 'bg-white/20 text-yellow-300' : '' }}">
                🎼 My Songs
            </a>
            @if(auth()->user() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" 
                   class="block text-white hover:text-yellow-300 transition font-medium py-2 px-3 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.*') ? 'bg-white/20 text-yellow-300' : '' }}">
                    ⚡ Admin
                </a>
            @endif

            <!-- Mobile User Actions -->
            <div class="border-t border-white/20 pt-4 mt-4">
                <div class="text-white font-medium mb-2">{{ Auth::user()->name }}</div>
                <a href="{{ route('profile.show') }}" class="block text-white/80 hover:text-white py-1">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-white/80 hover:text-white py-1">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Refresh Button (Hidden) -->
    <button wire:click="refresh" class="hidden">Refresh</button>
</nav>

<!-- TimeManager handles local time updates centrally -->
