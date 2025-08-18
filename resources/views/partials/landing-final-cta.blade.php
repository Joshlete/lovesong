<!-- Final CTA -->
<section class="py-20 px-4 bg-white/10 backdrop-blur-sm">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            Ready to Make Something Amazing? 🚀
        </h2>
        <p class="text-xl text-white/90 mb-8">
            Join 2,300+ happy customers who got their perfect song
        </p>
        
        <!-- Countdown Timer -->
        <div class="bg-white rounded-2xl p-6 inline-block mb-8 shadow-2xl">
            <p class="text-gray-600 mb-2">🔥 Special price ends in:</p>
            <div class="flex space-x-4 text-3xl font-bold text-purple-600">
                <div>
                    <span id="hours">23</span>
                    <p class="text-xs text-gray-500">HOURS</p>
                </div>
                <span>:</span>
                <div>
                    <span id="minutes">59</span>
                    <p class="text-xs text-gray-500">MINS</p>
                </div>
                <span>:</span>
                <div>
                    <span id="seconds">47</span>
                    <p class="text-xs text-gray-500">SECS</p>
                </div>
            </div>
        </div>
        
        <div x-data>
            @auth
                <a href="{{ route('song-requests.create') }}"
                   class="inline-block bg-yellow-400 text-purple-900 px-12 py-5 rounded-full font-bold text-xl hover:bg-yellow-300 transform hover:scale-105 transition shadow-2xl animate-pulse">
                    🎵 Get My Song for ${{ number_format(\App\Models\Setting::getSongPrice(), 2) }}
                </a>
            @else
                <button 
                    @click="$dispatch('openRegisterModal')"
                    class="inline-block bg-yellow-400 text-purple-900 px-12 py-5 rounded-full font-bold text-xl hover:bg-yellow-300 transform hover:scale-105 transition shadow-2xl animate-pulse"
                >
                    🎵 Get My Song for ${{ number_format(\App\Models\Setting::getSongPrice(), 2) }}
                </button>
            @endauth
            <p class="text-white/60 text-sm mt-4">No subscription. No hidden fees. Just your song.</p>
        </div>
    </div>
</section>
