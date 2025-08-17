<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Email Verification Success Banner -->
        @if($showEmailVerifiedSuccess)
            <x-notification-banner 
                type="success"
                title="🎉 Email Verified Successfully!"
                message="Awesome! Your email is now verified. You can now complete payments and access all features."
                :action-url="route('song-requests.create')"
                action-text="🎵 Create Your First Song →"
            />
        @endif

        <!-- Email Verification Warning Banner -->
        @if(!$userEmailVerified)
            <x-notification-banner 
                type="warning"
                title="📧 Verify Your Email to Complete Orders"
                message="You can create song requests, but you'll need to verify your email before making any payments."
            >
                <div class="flex items-center space-x-3">
                    @livewire('resend-verification-button', ['variant' => 'banner'])
                    
                    <a href="{{ route('verification.notice') }}" class="text-white underline text-sm hover:text-white/80 flex items-center transition">
                        Learn More →
                    </a>
                </div>
            </x-notification-banner>
        @endif

        <!-- Dashboard Stats (if user has requests) -->
        @if($stats['total_requests'] > 0)
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Overview</h3>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_requests'] }}</div>
                                <div class="text-sm text-gray-600">Total Requests</div>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-yellow-800">{{ $stats['pending_requests'] }}</div>
                                <div class="text-sm text-yellow-600">Pending</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-800">{{ $stats['in_progress_requests'] }}</div>
                                <div class="text-sm text-blue-600">In Progress</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-800">{{ $stats['completed_requests'] }}</div>
                                <div class="text-sm text-green-600">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('song-requests.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Song Request
                        </a>
                        <a href="{{ route('song-requests.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Requests
                        </a>
                        @if($stats['total_requests'] > 0)
                            <button wire:click="refreshRecentRequests"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg wire:loading wire:target="refreshRecentRequests" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Refresh
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Song Requests -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Recent Song Requests</h3>
                    @if($recentRequests->count() > 0)
                        <a href="{{ route('song-requests.index') }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View all →
                        </a>
                    @endif
                </div>
                
                @if($recentRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRequests as $request)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $request->recipient_name }}
                                            </h4>
                                            <x-status-badge :status="$request->status" />
                                        </div>
                                        <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                                            @if($request->style)
                                                <span>{{ ucfirst($request->style) }}</span>
                                            @endif
                                            @if($request->mood)
                                                <span>{{ ucfirst($request->mood) }}</span>
                                            @endif
                                            <span>${{ number_format($request->price_usd, 2) }}</span>
                                            <span>{{ $request->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('song-requests.show', $request) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No song requests yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first song request.</p>
                        <div class="mt-6">
                            <a href="{{ route('song-requests.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Song Request
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
