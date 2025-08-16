<!-- Hero Section with Urgency -->
<section class="pt-32 pb-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <!-- Social Proof Badge -->
        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
            <span class="animate-pulse bg-green-400 rounded-full w-2 h-2 mr-2"></span>
            <span class="text-white text-sm font-medium">🔥 12 people are creating songs right now</span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black text-white mb-6 animate-fade-in">
            Your Song.<br>
            <span class="text-yellow-300">24 Hours.</span><br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-pink-300">
                Viral Ready.
            </span>
        </h1>
        
        <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl mx-auto">
            Professional musicians create YOUR custom song. Perfect for TikTok videos, surprise gifts, or just because! 
        </p>

        <!-- Price with Scarcity -->
        <div class="mb-8">
            <div class="inline-flex items-center bg-white rounded-full px-6 py-3 shadow-2xl">
                <span class="text-gray-500 line-through text-lg mr-2">$25</span>
                <span class="text-4xl font-bold text-purple-600">${{ number_format(\App\Models\Setting::getSongPrice(), 2) }}</span>
                <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">LIMITED TIME</span>
            </div>
            <p class="text-white/80 text-sm mt-2">⏰ Price goes up in 24 hours!</p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('register') }}" class="bg-yellow-400 text-purple-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 transform hover:scale-105 transition shadow-xl animate-bounce">
                🎤 Create My Song Now
            </a>
            <button onclick="document.getElementById('how-it-works').scrollIntoView({behavior: 'smooth'})" class="text-white underline hover:text-yellow-300 transition">
                See how it works →
            </button>
        </div>

        <!-- Trust Indicators -->
        <div class="mt-8 flex justify-center space-x-8 text-white/80">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H4v10a2 2 0 002 2h8a2 2 0 002-2V5h-2a1 1 0 100-2 2 2 0 012 2v10a4 4 0 01-4 4H6a4 4 0 01-4-4V5z" clip-rule="evenodd"></path>
                </svg>
                <span>No Hidden Fees</span>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>100% Satisfaction</span>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                </svg>
                <span>2.3K Songs Created</span>
            </div>
        </div>
    </div>
</section>
