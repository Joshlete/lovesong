<!-- Gradient Header -->
<div class="bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 p-6 relative">
    <!-- Close Button -->
    <button 
        @click="$wire.closeModal()"
        class="absolute top-4 right-4 text-white/80 hover:text-white transition"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Mobile Drag Handle -->
    <div class="w-12 h-1 bg-white/30 rounded-full mx-auto mb-4 sm:hidden"></div>

    <!-- Title -->
    <h2 class="text-2xl font-bold text-white text-center">
        @if($activeTab === 'login')
            Welcome Back! 👋
        @else
            Join the Party! 🎉
        @endif
    </h2>
    <p class="text-white/90 text-center mt-2">
        @if($activeTab === 'login')
            Sign in to create your next viral song
        @else
            Get your custom song in 24 hours
        @endif
    </p>
</div>
