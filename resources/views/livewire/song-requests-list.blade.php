<div class="space-y-8">
    <!-- Stats Header -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-4 right-6 w-16 h-16 bg-white rounded-full animate-pulse"></div>
            <div class="absolute bottom-4 left-8 w-12 h-12 bg-white rounded-full animate-bounce"></div>
            <div class="absolute top-1/2 left-1/2 w-8 h-8 bg-white rounded-full animate-ping"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">🎵 My Song Requests</h1>
                    <p class="text-white/90 text-lg">Track your musical creations</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('song-requests.create') }}" 
                       class="bg-yellow-400 text-purple-900 px-6 py-3 rounded-full font-bold hover:bg-yellow-300 transform hover:scale-105 transition shadow-lg animate-pulse">
                        + Create New Song
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                        <div class="text-sm text-white/80">Total Songs</div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-300">{{ $stats['pending'] }}</div>
                        <div class="text-sm text-white/80">Pending</div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-300">{{ $stats['in_progress'] }}</div>
                        <div class="text-sm text-white/80">In Progress</div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-300">{{ $stats['completed'] }}</div>
                        <div class="text-sm text-white/80">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Search Songs
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           id="search"
                           placeholder="Search by title, style, mood..."
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">
                    Filter by Status
                </label>
                <select wire:model.live="statusFilter" 
                        id="statusFilter"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">
                    Songs per page
                </label>
                <select wire:model.live="perPage" 
                        id="perPage"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Song Requests List -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        @if($songRequests->count() > 0)
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-purple-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-purple-900">Your Song Collection</h3>
                    <div class="text-sm text-purple-600">
                        Showing {{ $songRequests->firstItem() }} to {{ $songRequests->lastItem() }} of {{ $songRequests->total() }} results
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button wire:click="sortBy('recipient_name')" 
                                        class="group flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Song Title
                                    @if($sortBy === 'recipient_name')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Style & Mood
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button wire:click="sortBy('price_usd')" 
                                        class="group flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Price
                                    @if($sortBy === 'price_usd')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button wire:click="sortBy('status')" 
                                        class="group flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Status
                                    @if($sortBy === 'status')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button wire:click="sortBy('created_at')" 
                                        class="group flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Created
                                    @if($sortBy === 'created_at')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Click to view</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($songRequests as $songRequest)
                            <tr class="hover:bg-purple-50 transition-all duration-200 cursor-pointer group"
                                onclick="window.location.href='{{ route('song-requests.show', $songRequest) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <span class="text-white font-bold text-lg">🎵</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">
                                                {{ $songRequest->recipient_name }}
                                            </div>
                                            @if($songRequest->song_description)
                                                <div class="text-sm text-gray-500 truncate max-w-xs group-hover:text-purple-600 transition">
                                                    {{ Str::limit($songRequest->song_description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($songRequest->style)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 group-hover:bg-blue-200 transition">
                                                🎼 {{ ucfirst($songRequest->style) }}
                                            </span>
                                        @endif
                                        @if($songRequest->mood)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 group-hover:bg-green-200 transition">
                                                😊 {{ ucfirst($songRequest->mood) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">
                                    ${{ number_format($songRequest->price_usd, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($songRequest->status === 'pending') bg-yellow-100 text-yellow-800 group-hover:bg-yellow-200
                                        @elseif($songRequest->status === 'in_progress') bg-blue-100 text-blue-800 group-hover:bg-blue-200
                                        @elseif($songRequest->status === 'completed') bg-green-100 text-green-800 group-hover:bg-green-200
                                        @else bg-red-100 text-red-800 group-hover:bg-red-200
                                        @endif transition">
                                        @if($songRequest->status === 'pending') ⏳
                                        @elseif($songRequest->status === 'in_progress') 🎵
                                        @elseif($songRequest->status === 'completed') ✅
                                        @else ❌
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $songRequest->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 group-hover:text-purple-600 transition">
                                    {{ $songRequest->created_at->format('M j, Y') }}
                                    <div class="text-xs text-gray-400 group-hover:text-purple-500 transition">
                                        {{ $songRequest->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end">
                                        <span class="text-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 font-medium text-sm">
                                            Click to view →
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($songRequests as $songRequest)
                    <div class="p-6 space-y-4 cursor-pointer hover:bg-purple-50 transition-all duration-200 group"
                         onclick="window.location.href='{{ route('song-requests.show', $songRequest) }}'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="text-white font-bold text-lg">🎵</span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">{{ $songRequest->recipient_name }}</h3>
                                    <p class="text-xs text-gray-500 group-hover:text-purple-600 transition">{{ $songRequest->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($songRequest->status === 'pending') bg-yellow-100 text-yellow-800 group-hover:bg-yellow-200
                                @elseif($songRequest->status === 'in_progress') bg-blue-100 text-blue-800 group-hover:bg-blue-200
                                @elseif($songRequest->status === 'completed') bg-green-100 text-green-800 group-hover:bg-green-200
                                @else bg-red-100 text-red-800 group-hover:bg-red-200
                                @endif transition">
                                @if($songRequest->status === 'pending') ⏳
                                @elseif($songRequest->status === 'in_progress') 🎵
                                @elseif($songRequest->status === 'completed') ✅
                                @else ❌
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $songRequest->status)) }}
                            </span>
                        </div>

                        @if($songRequest->song_description)
                            <p class="text-sm text-gray-600 group-hover:text-purple-700 transition">{{ Str::limit($songRequest->song_description, 100) }}</p>
                        @endif

                        <div class="flex flex-wrap gap-2">
                            @if($songRequest->style)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 group-hover:bg-blue-200 transition">
                                    🎼 {{ ucfirst($songRequest->style) }}
                                </span>
                            @endif
                            @if($songRequest->mood)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 group-hover:bg-green-200 transition">
                                    😊 {{ ucfirst($songRequest->mood) }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">
                                ${{ number_format($songRequest->price_usd, 2) }}
                            </div>
                            <div class="flex items-center">
                                <span class="text-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 font-medium text-sm">
                                    Tap to view →
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $songRequests->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl">🎵</span>
                </div>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No song requests yet</h3>
                <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                    Start your musical journey by creating your first custom song request. 
                    Let's make something beautiful together!
                </p>
                <div class="mt-6">
                    <a href="{{ route('song-requests.create') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform hover:scale-105 transition">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Your First Song
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>