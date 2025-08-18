<div class="space-y-8">
    <!-- Admin Header -->
    <div class="mb-8">
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
                        <h1 class="text-3xl font-bold mb-2">⚡ Admin Command Center</h1>
                        <p class="text-white/90 text-lg">Manage your musical empire</p>
                    </div>
                    <div class="text-right">
                        <button wire:click="refreshData" 
                                class="bg-white/20 text-white px-4 py-2 rounded-full font-medium hover:bg-white/30 transition">
                            🔄 Refresh
                        </button>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $stats['total_requests'] }}</div>
                            <div class="text-sm text-white/80">Total Songs</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-300">{{ $stats['pending_requests'] }}</div>
                            <div class="text-sm text-white/80">Pending</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-300">{{ $stats['in_progress_requests'] }}</div>
                            <div class="text-sm text-white/80">In Progress</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-300">{{ $stats['completed_requests'] }}</div>
                            <div class="text-sm text-white/80">Completed</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-300">{{ $stats['total_users'] }}</div>
                            <div class="text-sm text-white/80">Users</div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-emerald-300">${{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="text-sm text-white/80">Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Analytics -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <h3 class="text-xl font-bold text-gray-900 mb-4">💰 Revenue Analytics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                <div class="text-2xl font-bold text-green-700">${{ number_format($revenueAnalytics['today'], 2) }}</div>
                <div class="text-sm text-green-600">Today</div>
            </div>
            <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                <div class="text-2xl font-bold text-blue-700">${{ number_format($revenueAnalytics['this_week'], 2) }}</div>
                <div class="text-sm text-blue-600">This Week</div>
            </div>
            <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                <div class="text-2xl font-bold text-purple-700">${{ number_format($revenueAnalytics['this_month'], 2) }}</div>
                <div class="text-sm text-purple-600">This Month</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <h3 class="text-xl font-bold text-gray-900 mb-4">🚀 Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.song-requests.index') }}" 
               class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-4 rounded-xl font-bold text-center transition transform hover:scale-105 shadow-lg">
                📋 All Requests
            </a>
            <a href="{{ route('admin.song-requests.index', ['status' => 'pending']) }}" 
               class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white px-6 py-4 rounded-xl font-bold text-center transition transform hover:scale-105 shadow-lg">
                ⏳ Pending
            </a>
            <a href="{{ route('admin.song-requests.index', ['status' => 'in_progress']) }}" 
               class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-6 py-4 rounded-xl font-bold text-center transition transform hover:scale-105 shadow-lg">
                🎨 In Progress
            </a>
            <a href="{{ route('admin.settings') }}" 
               class="bg-gradient-to-r from-gray-500 to-slate-600 hover:from-gray-600 hover:to-slate-700 text-white px-6 py-4 rounded-xl font-bold text-center transition transform hover:scale-105 shadow-lg">
                ⚙️ Settings
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Requests Needing Attention -->
        @if($pendingRequests->count() > 0)
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">⚠️ Needs Attention</h3>
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $pendingRequests->count() }} Paid & Pending
                </span>
            </div>
            <div class="space-y-3">
                @foreach($pendingRequests as $request)
                    <div class="group cursor-pointer bg-gradient-to-r from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 rounded-xl p-4 transition-all duration-200 border border-orange-200 hover:border-orange-300"
                         onclick="window.location.href='{{ route('admin.song-requests.show', $request) }}'">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-orange-700 transition">
                                    {{ $request->recipient_name }}
                                </h4>
                                <p class="text-sm text-gray-600 group-hover:text-orange-600 transition">
                                    By: {{ $request->user->name }} • ${{ number_format($request->price_usd, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 group-hover:text-orange-500 transition">
                                    {{ $request->time_ago }}
                                </p>
                            </div>
                            <div class="text-orange-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                →
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Popular Styles -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-gray-900 mb-4">🎼 Popular Styles</h3>
            @if($popularStyles->count() > 0)
                <div class="space-y-3">
                    @foreach($popularStyles as $style)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                            <span class="font-medium text-purple-800">{{ ucfirst($style->style) }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-sm font-medium">
                                    {{ $style->total }} songs
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No style data available yet</p>
            @endif
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">🎵 Recent Song Requests</h3>
            <a href="{{ route('admin.song-requests.index') }}" 
               class="text-purple-600 hover:text-purple-800 font-medium transition">
                View All →
            </a>
        </div>

        @if($recentRequests->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 rounded-lg">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Song</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentRequests as $request)
                            <tr class="cursor-pointer hover:bg-purple-50 hover:shadow-md transition-all duration-200 group"
                                onclick="window.location.href='{{ route('admin.song-requests.show', $request) }}'">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 group-hover:text-purple-700 transition">{{ $request->recipient_name }}</div>
                                    @if($request->style)
                                        <div class="text-sm text-gray-500 group-hover:text-purple-600 transition">{{ ucfirst($request->style) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">{{ $request->user->name }}</div>
                                    <div class="text-sm text-gray-500 group-hover:text-purple-600 transition">{{ $request->user->email }}</div>
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
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 group-hover:text-purple-700 transition">
                                    ${{ number_format($request->price_usd, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 group-hover:text-purple-600 transition">
                                    {{ $request->formatted_date }}
                                    <div class="text-xs text-gray-400 group-hover:text-purple-500 transition">{{ $request->time_ago }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @foreach($recentRequests as $request)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 cursor-pointer hover:from-purple-50 hover:to-pink-50 hover:shadow-lg hover:border-purple-200 transition-all duration-200 border border-gray-200 group"
                         onclick="window.location.href='{{ route('admin.song-requests.show', $request) }}'">
                        <div class="flex items-center justify-between mb-2">
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
                        <div class="text-sm text-gray-600 group-hover:text-purple-600 space-y-1 transition">
                            <div>User: {{ $request->user->name }}</div>
                            <div>Price: ${{ number_format($request->price_usd, 2) }}</div>
                            <div>Created: {{ $request->time_ago }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🎵</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No song requests yet</h3>
                <p class="text-gray-500">Song requests will appear here once users start creating them.</p>
            </div>
        @endif
    </div>
</div>