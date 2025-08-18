<div {{ $attributes->merge(['class' => $getCardClasses()]) }}>
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-20 h-20 bg-white rounded-full transform -translate-x-10 -translate-y-10 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full transform translate-x-16 translate-y-16"></div>
        <div class="absolute top-1/2 left-1/2 w-16 h-16 bg-white rounded-full transform -translate-x-8 -translate-y-8 opacity-50"></div>
    </div>
    
    <!-- Card content -->
    <div class="relative p-6">
        <!-- Header with emoji and title -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
                @if($emoji)
                    <span class="text-3xl {{ $pulsing ? 'animate-bounce' : '' }}">{{ $emoji }}</span>
                @endif
                <div>
                    <h3 class="text-xl font-bold {{ $getTextClasses() }}">
                        {{ $title }}
                    </h3>
                    @if($subtitle)
                        <p class="text-sm {{ $getSubtitleClasses() }} mt-1">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Optional action indicator -->
            @if($action)
                <div class="flex items-center {{ $getTextClasses() }} opacity-70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        <!-- Card content slot -->
        <div class="{{ $getTextClasses() }}">
            {{ $slot }}
        </div>
    </div>
    
    <!-- Hover glow effect -->
    @if($hoverable)
        <div class="absolute inset-0 bg-white opacity-0 hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
    @endif
</div>