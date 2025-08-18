<div wire:init="initLocalTime" class="space-y-6">
    <!-- Welcome Header -->
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
                    <h1 class="text-3xl font-bold mb-2">{{ $personalizedGreeting }}</h1>
                    <p class="text-white/90 text-lg">Your musical profile & achievements</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">Member since</p>
                    <p class="text-xl font-semibold">{{ $profileStats['member_since'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-psych-card variant="gradient" gradient="blue" title="{{ $profileStats['total_songs'] }}" subtitle="Total Songs" emoji="🎵" />
        <x-psych-card variant="gradient" gradient="green" title="{{ $profileStats['completed_songs'] }}" subtitle="Completed" emoji="🎉" />
        <x-psych-card variant="gradient" gradient="orange" title="{{ $profileStats['creative_streak'] }}" subtitle="Day Streak" emoji="🔥" />
        <x-psych-card variant="gradient" gradient="purple" title="{{ $profileStats['completion_rate'] }}%" subtitle="Success Rate" emoji="⭐" />
    </div>

    <!-- Insights Section -->
    @if(count($insights) > 0)
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-xl border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <span class="mr-2">💡</span>
                Your Creative Insights
            </h2>
            <div class="space-y-3">
                @foreach($insights as $insight)
                    <div class="p-4 rounded-xl border-l-4 {{ 
                        $insight['color'] === 'orange' ? 'bg-orange-50 border-orange-400' :
                        ($insight['color'] === 'green' ? 'bg-green-50 border-green-400' :
                        ($insight['color'] === 'purple' ? 'bg-purple-50 border-purple-400' :
                        ($insight['color'] === 'blue' ? 'bg-blue-50 border-blue-400' : 'bg-gray-50 border-gray-400')))
                    }}">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $insight['emoji'] }}</span>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $insight['title'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $insight['message'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Achievements Section -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-xl border border-white/20">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="mr-2">🏆</span>
            Achievements
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($achievements as $achievement)
                <div class="relative p-4 rounded-xl border-2 transition-all duration-300 hover:shadow-lg {{ 
                    $achievement['unlocked'] 
                        ? 'bg-gradient-to-br from-yellow-50 to-amber-50 border-yellow-200 hover:from-yellow-100 hover:to-amber-100' 
                        : 'bg-gray-50 border-gray-200 opacity-60'
                }}">
                    <!-- Achievement Badge -->
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 rounded-full {{ 
                            $achievement['unlocked'] 
                                ? 'bg-gradient-to-br from-yellow-400 to-amber-500' 
                                : 'bg-gray-300'
                        }} flex items-center justify-center text-2xl mr-3">
                            {{ $achievement['emoji'] }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $achievement['title'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $achievement['date'] }}</p>
                        </div>
                        @if($achievement['unlocked'])
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-600">{{ $achievement['description'] }}</p>
                    
                    @if($achievement['unlocked'])
                        <!-- Achievement celebration effect -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute top-2 right-2 w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Profile Actions -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-xl border border-white/20">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="mr-2">🚀</span>
            Quick Actions
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('song-requests.create') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 transition shadow-lg group">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                    <span class="text-2xl">🎵</span>
                </div>
                <div>
                    <h3 class="font-semibold">Create New Song</h3>
                    <p class="text-white/80 text-sm">Start your next musical masterpiece</p>
                </div>
            </a>
            
            <a href="{{ route('song-requests.index') }}" 
               class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 transition shadow-lg group">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                    <span class="text-2xl">🎼</span>
                </div>
                <div>
                    <h3 class="font-semibold">My Songs</h3>
                    <p class="text-white/80 text-sm">View and manage your music</p>
                </div>
            </a>
        </div>
    </div>
</div>