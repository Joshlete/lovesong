@if($title || $message)
<div class="mb-6 bg-gradient-to-r {{ $getTypeClasses() }} rounded-lg shadow-lg p-6 relative overflow-hidden">
    <!-- Animated background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-20 h-20 bg-white rounded-full transform -translate-x-10 -translate-y-10"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full transform translate-x-16 translate-y-16"></div>
    </div>
    
    <div class="relative flex items-center">
        <!-- Icon -->
        <div class="flex-shrink-0">
            <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    {!! $getIcon() !!}
                </svg>
            </div>
        </div>
        
        <!-- Content -->
        <div class="ml-4 flex-1">
            @if($title)
                <h3 class="text-lg font-semibold text-white">
                    @if($emoji){{ $emoji }} @endif{{ $title }}
                </h3>
            @endif
            
            @if($message)
                <p class="text-white/90 text-sm mt-1">{{ $message }}</p>
            @endif
            
            <!-- Actions -->
            @if($actionUrl && $actionText)
                <div class="mt-3">
                    <a href="{{ $actionUrl }}" class="inline-flex items-center bg-white px-4 py-2 rounded-lg font-semibold text-sm transition
                        @if($type === 'success') text-green-600 hover:bg-green-50
                        @elseif($type === 'warning') text-orange-600 hover:bg-orange-50  
                        @elseif($type === 'error') text-red-600 hover:bg-red-50
                        @else text-blue-600 hover:bg-blue-50
                        @endif">
                        {{ $actionText }}
                    </a>
                </div>
            @endif
            
            <!-- Custom action slot -->
            @if($slot->isEmpty() === false)
                <div class="mt-3">
                    {{ $slot }}
                </div>
            @endif
        </div>
        
        <!-- End emoji -->
        @if($emoji && $type === 'success')
            <div class="ml-4">
                <span class="text-2xl">✅</span>
            </div>
        @elseif($emoji && $type === 'warning')
            <div class="ml-4">
                <span class="text-2xl">🎵</span>
            </div>
        @endif
    </div>
</div>
@endif