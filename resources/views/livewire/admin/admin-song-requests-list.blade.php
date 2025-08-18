<div class="space-y-6">
    <!-- Filter Statistics -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Request Overview</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                <div class="text-xl font-bold text-gray-700">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">Total</div>
            </div>
            <div class="text-center p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                <div class="text-xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
                <div class="text-xs text-yellow-600">Pending</div>
            </div>
            <div class="text-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <div class="text-xl font-bold text-blue-700">{{ $stats['in_progress'] }}</div>
                <div class="text-xs text-blue-600">In Progress</div>
            </div>
            <div class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                <div class="text-xl font-bold text-green-700">{{ $stats['completed'] }}</div>
                <div class="text-xs text-green-600">Completed</div>
            </div>
            <div class="text-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl border border-red-200">
                <div class="text-xl font-bold text-red-700">{{ $stats['cancelled'] }}</div>
                <div class="text-xs text-red-600">Cancelled</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Search & Filter</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Requests</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search by song title, lyrics, or description..." 
                       class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" 
                        class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <!-- User Filter -->
            <div>
                <label for="userFilter" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="userFilter"
                       placeholder="Search by user name or email..." 
                       class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
            </div>
        </div>
        
        <!-- Quick Filters and Actions -->
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <button wire:click="clearFilters" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">
                Clear Filters
            </button>
            <button wire:click="$set('statusFilter', 'pending')" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition font-medium">
                Pending Only
            </button>
            <button wire:click="$set('statusFilter', 'in_progress')" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition font-medium">
                In Progress Only
            </button>
            
            <!-- Per Page -->
            <div class="ml-auto flex items-center space-x-2">
                <label class="text-sm text-gray-600">Show:</label>
                <select wire:model.live="perPage" 
                        class="rounded border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Song Requests List -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">🎵 Song Requests</h3>
                @if($songRequests->total() > 0)
                    <div class="text-sm text-gray-600">
                        Showing {{ $songRequests->firstItem() }} to {{ $songRequests->lastItem() }} of {{ $songRequests->total() }} results
                    </div>
                @endif
            </div>

            @if($songRequests->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('recipient_name')" 
                                            class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 transition">
                                        <span>Song Details</span>
                                        @if($sortBy === 'recipient_name')
                                            <span class="text-purple-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('user_id')" 
                                            class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 transition">
                                        <span>User</span>
                                        @if($sortBy === 'user_id')
                                            <span class="text-purple-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Style & Mood</th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('price_usd')" 
                                            class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 transition">
                                        <span>Price</span>
                                        @if($sortBy === 'price_usd')
                                            <span class="text-purple-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('status')" 
                                            class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 transition">
                                        <span>Status</span>
                                        @if($sortBy === 'status')
                                            <span class="text-purple-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('created_at')" 
                                            class="flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 transition">
                                        <span>Created</span>
                                        @if($sortBy === 'created_at')
                                            <span class="text-purple-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($songRequests as $request)
                                <tr class="cursor-pointer hover:bg-purple-50 hover:shadow-md transition-all duration-200 group"
                                    onclick="window.location.href='{{ route('admin.song-requests.show', $request) }}'">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 group-hover:text-purple-700 transition">{{ $request->recipient_name }}</div>
                                        @if($request->lyrics_idea)
                                            <div class="text-sm text-gray-500 group-hover:text-purple-600 transition">{{ Str::limit($request->lyrics_idea, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">{{ $request->user->name }}</div>
                                        <div class="text-sm text-gray-500 group-hover:text-purple-600 transition">{{ $request->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @if($request->style)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($request->style) }}
                                                </span>
                                            @endif
                                            @if($request->mood)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ ucfirst($request->mood) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">
                                        ${{ number_format($request->price_usd, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($request->status === 'pending') ⏳
                                            @elseif($request->status === 'in_progress') 🎨
                                            @elseif($request->status === 'completed') ✅
                                            @else ❌
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 group-hover:text-purple-600 transition">
                                        {{ $request->created_at->format('M j, Y') }}
                                        <div class="text-xs text-gray-400 group-hover:text-purple-500 transition">{{ $request->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile/Tablet Cards -->
                <div class="lg:hidden space-y-4">
                    @foreach($songRequests as $request)
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 cursor-pointer hover:from-purple-50 hover:to-pink-50 hover:shadow-lg hover:border-purple-200 transition-all duration-200 border border-gray-200 group"
                             onclick="window.location.href='{{ route('admin.song-requests.show', $request) }}'">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-gray-900 group-hover:text-purple-700 transition">{{ $request->recipient_name }}</h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600 group-hover:text-purple-600 transition">
                                <div>
                                    <strong>User:</strong> {{ $request->user->name }}
                                </div>
                                <div>
                                    <strong>Price:</strong> ${{ number_format($request->price_usd, 2) }}
                                </div>
                                <div>
                                    <strong>Email:</strong> {{ $request->user->email }}
                                </div>
                                <div>
                                    <strong>Created:</strong> {{ $request->created_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            @if($request->style || $request->mood)
                                <div class="mt-3 flex flex-wrap gap-1">
                                    @if($request->style)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($request->style) }}
                                        </span>
                                    @endif
                                    @if($request->mood)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ ucfirst($request->mood) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            @if($request->lyrics_idea)
                                <div class="mt-3 text-sm text-gray-600 group-hover:text-purple-600 transition">
                                    <strong>Lyrics:</strong> {{ Str::limit($request->lyrics_idea, 80) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $songRequests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🎵</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No song requests found</h3>
                    <p class="text-gray-500">
                        @if($search || $statusFilter || $userFilter)
                            Try adjusting your filters to see more results.
                        @else
                            Song requests will appear here once users start creating them.
                        @endif
                    </p>
                    @if($search || $statusFilter || $userFilter)
                        <button wire:click="clearFilters" 
                                class="mt-4 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition font-medium">
                            Clear All Filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>