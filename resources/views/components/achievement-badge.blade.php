<div class="relative group" x-data="{ showTooltip: false }">
    <!-- Badge -->
    <div 
        {{ $attributes->merge(['class' => $getBadgeClasses()]) }}
        @mouseenter="showTooltip = true" 
        @mouseleave="showTooltip = false"
        @if($isAlmostComplete()) 
            x-init="setTimeout(() => $el.classList.add('animate-pulse'), Math.random() * 3000)"
        @endif
    >
        <span class="text-lg mr-2">{{ $emoji }}</span>
        <span>{{ $getTitle() }}</span>
        
        @if(!$unlocked && $progress > 0)
            <span class="ml-2 text-xs opacity-75">
                {{ $progress }}/{{ $total }}
            </span>
        @endif
    </div>
    
    <!-- Progress bar for incomplete achievements -->
    @if(!$unlocked && $progress > 0)
        <div class="mt-1 bg-gray-200 rounded-full h-1 overflow-hidden">
            <div 
                class="h-full bg-gradient-to-r transition-all duration-1000 ease-out
                    @if($isAlmostComplete()) 
                        from-yellow-400 to-orange-400 animate-pulse
                    @else 
                        from-blue-400 to-purple-500
                    @endif"
                style="width: {{ $getProgressPercentage() }}%"
            ></div>
        </div>
    @endif
    
    <!-- Tooltip -->
    <div 
        x-show="showTooltip && '{{ $description }}'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg z-10 whitespace-nowrap"
        style="display: none;"
    >
        {{ $description }}
        <!-- Tooltip arrow -->
        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
    </div>
    
    <!-- Celebration sparkles for unlocked achievements -->
    @if($unlocked && in_array($level, ['gold', 'platinum', 'diamond']))
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-1 h-1 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
            <div class="absolute top-0 right-0 w-1 h-1 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-0 left-0 w-1 h-1 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 1.5s;"></div>
            <div class="absolute bottom-0 right-0 w-1 h-1 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 2s;"></div>
        </div>
    @endif
</div>