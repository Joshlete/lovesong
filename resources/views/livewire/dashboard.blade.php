<div class="relative" wire:init="initLocalTime">
    <!-- Animated background pattern -->
    <div class="absolute inset-0 opacity-10 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-20 h-20 bg-white rounded-full animate-bounce"></div>
        <div class="absolute bottom-32 left-1/4 w-16 h-16 bg-white rounded-full animate-ping"></div>
        <div class="absolute bottom-20 right-1/3 w-24 h-24 bg-white rounded-full animate-pulse"></div>
    </div>

    <div class="relative py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Personal Greeting with Energy -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-2">
                    {{ $greeting }}
                </h1>
                <p class="text-xl text-white/90 font-medium">
                    {{ $motivationalMessage }}
                </p>
            </div>

            <!-- Live Activity Social Proof -->
            <div class="flex justify-center mb-8">
                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                    <span class="animate-pulse bg-green-400 rounded-full w-3 h-3 mr-3"></span>
                    <span class="text-white font-medium">
                        🔥 {{ $liveActivity['count'] }} people are {{ $liveActivity['activity'] }} right now
                    </span>
                </div>
            </div>

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

            <!-- Dashboard Stats with Psychological Design -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
                <x-psych-card variant="info" title="{{ $stats['total_requests'] }}" subtitle="Total Songs" emoji="🎵" :hoverable="false" />
                <x-psych-card variant="warning" title="{{ $stats['pending_requests'] }}" subtitle="In Queue" emoji="⏳" :pulsing="$stats['pending_requests'] > 0" />
                <x-psych-card variant="progress" title="{{ $stats['in_progress_requests'] }}" subtitle="Being Created" emoji="🎨" :pulsing="$stats['in_progress_requests'] > 0" />
                <x-psych-card variant="success" title="{{ $stats['completed_requests'] }}" subtitle="Ready to Enjoy" emoji="✨" />
            </div>

            <!-- Achievement System -->
            @if(count($achievements) > 0)
                <div class="mb-8">
                    <x-psych-card variant="achievement" title="🏆 Your Achievements" subtitle="Unlock more by creating songs!">
                        <div class="flex flex-wrap gap-3 mt-4">
                            @foreach($achievements as $achievement)
                                <x-achievement-badge 
                                    :achievement="$achievement['achievement']"
                                    :level="$achievement['level']"
                                    :emoji="$achievement['emoji']"
                                    :unlocked="$achievement['unlocked']"
                                    :description="$achievement['description']"
                                    :progress="$achievement['progress']"
                                    :total="$achievement['total']"
                                />
                            @endforeach
                        </div>
                    </x-psych-card>
                </div>
            @endif

            <!-- Next Milestone -->
            @if($nextMilestone)
                <div class="mb-8">
                    <x-psych-card 
                        variant="celebration" 
                        :title="$nextMilestone['emoji'] . ' Next Goal'"
                        :subtitle="$nextMilestone['message']"
                        :pulsing="$nextMilestone['progress'] > 75"
                    >
                        <div class="mt-4">
                            <div class="flex justify-between text-sm font-medium mb-2">
                                <span>{{ $nextMilestone['current'] }} / {{ $nextMilestone['target'] }} songs</span>
                                <span>{{ number_format($nextMilestone['progress'], 1) }}%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-3 overflow-hidden">
                                <div 
                                    class="h-full bg-gradient-to-r from-yellow-300 to-yellow-400 transition-all duration-1000 ease-out"
                                    style="width: {{ $nextMilestone['progress'] }}%"
                                ></div>
                            </div>
                            @if($nextMilestone['remaining'] === 1)
                                <p class="text-sm mt-2 font-bold animate-bounce">
                                    🎯 Just {{ $nextMilestone['remaining'] }} more song to unlock this milestone!
                                </p>
                            @elseif($nextMilestone['remaining'] <= 3)
                                <p class="text-sm mt-2 font-medium">
                                    🔥 Only {{ $nextMilestone['remaining'] }} more songs to go!
                                </p>
                            @else
                                <p class="text-sm mt-2">
                                    💪 {{ $nextMilestone['remaining'] }} more songs until your next achievement!
                                </p>
                            @endif
                        </div>
                    </x-psych-card>
                </div>
            @endif

            <!-- Call-to-Action Section -->
            <div class="mb-8">
                <x-psych-card variant="social" title="🎤 Ready to Create Magic?" subtitle="Your next hit song is just one click away!">
                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <a href="{{ route('song-requests.create') }}" 
                           class="flex-1 bg-yellow-400 text-purple-900 px-6 py-4 rounded-xl font-bold text-lg hover:bg-yellow-300 transform hover:scale-105 transition shadow-xl text-center animate-pulse">
                            🎵 Create New Song
                        </a>
                        <a href="{{ route('song-requests.index') }}" 
                           class="flex-1 bg-white/20 backdrop-blur-sm text-white px-6 py-4 rounded-xl font-semibold border border-white/30 hover:bg-white/30 transform hover:scale-105 transition text-center">
                            📚 View My Collection
                        </a>
                        @if($stats['total_requests'] > 0)
                            <button wire:click="refreshRecentRequests"
                                    class="bg-white/10 backdrop-blur-sm text-white px-4 py-4 rounded-xl font-medium border border-white/20 hover:bg-white/20 transition">
                                <svg wire:loading wire:target="refreshRecentRequests" class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="refreshRecentRequests">🔄</span>
                            </button>
                        @endif
                    </div>
                </x-psych-card>
            </div>

            <!-- Recent Song Requests -->
            @if($recentRequests->count() > 0)
                <div class="mb-8">
                    <x-psych-card variant="default" title="🎼 Your Recent Creations" subtitle="Track your musical journey">
                        <div class="space-y-4 mt-6">
                            @foreach($recentRequests as $request)
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 hover:from-purple-50 hover:to-pink-50 transition-all duration-300 transform hover:scale-[1.02]">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <div class="text-2xl">
                                                    @if($request->status === 'completed') 🎉
                                                    @elseif($request->status === 'in_progress') 🎨
                                                    @elseif($request->status === 'pending') ⏳
                                                    @else 🎵
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-900">
                                                        {{ $request->recipient_name }}
                                                    </h4>
                                                    <x-status-badge :status="$request->status" />
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                                @if($request->style)
                                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">{{ ucfirst($request->style) }}</span>
                                                @endif
                                                @if($request->mood)
                                                    <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-medium">{{ ucfirst($request->mood) }}</span>
                                                @endif
                                                <span class="font-bold text-green-600">${{ number_format($request->price_usd, 2) }}</span>
                                                <span>{{ $request->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('song-requests.show', $request) }}" 
                                               class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-lg font-medium hover:from-purple-600 hover:to-pink-600 transform hover:scale-105 transition">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($recentRequests->count() >= 5)
                            <div class="text-center mt-6">
                                <a href="{{ route('song-requests.index') }}" 
                                   class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium">
                                    See all your amazing songs →
                                </a>
                            </div>
                        @endif
                    </x-psych-card>
                </div>
            @else
                <!-- First Time User Experience -->
                <div class="mb-8">
                    <x-psych-card variant="celebration" title="🌟 Welcome to Your Musical Journey!" subtitle="Let's create something amazing together!" :pulsing="true">
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4 animate-bounce">🎵</div>
                            <h3 class="text-xl font-bold mb-2">Ready to Create Your First Masterpiece?</h3>
                            <p class="text-white/90 mb-6">Join thousands of creators who've made their musical dreams come true!</p>
                            <a href="{{ route('song-requests.create') }}" 
                               class="bg-yellow-400 text-purple-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-yellow-300 transform hover:scale-105 transition shadow-xl animate-pulse">
                                🎤 Create My First Song
                            </a>
                        </div>
                    </x-psych-card>
                </div>
            @endif

            <!-- Celebration Modal -->
            @if($showCelebration)
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @hide-celebration.window="setTimeout(() => show = false, 3000)">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-8 rounded-2xl text-white text-center max-w-md mx-4 transform animate-bounce">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-2xl font-bold mb-2">Awesome!</h3>
                        <p class="text-lg">You're making great progress on your musical journey!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for Dashboard local time -->
<script>
window.addEventListener('livewire:initialized', () => {
    const updateDashboardTime = () => {
        const localHour = new Date().getHours();
        @this.call('updateLocalHour', localHour);
    };
    
    // Update immediately
    updateDashboardTime();
    
    // Listen for dashboard time request
    Livewire.on('dashboard-get-local-time', updateDashboardTime);
});
</script>
