<!-- Register Form -->
<form wire:submit.prevent="register" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input 
            type="text" 
            wire:model="name"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="Your Name"
            required
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input 
            type="email" 
            wire:model="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="your@email.com"
            required
        >
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
            type="password" 
            wire:model="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="••••••••"
            required
        >
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input 
            type="password" 
            wire:model="password_confirmation"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="••••••••"
            required
        >
    </div>

    <!-- Special Offer Badge -->
    <div class="bg-gradient-to-r from-yellow-100 to-orange-100 border border-yellow-300 rounded-xl p-3">
        <p class="text-sm font-semibold text-gray-800">
            🎁 Special Offer: Get your first song for only ${{ number_format(\App\Models\Setting::getSongPrice(), 2) }}!
        </p>
    </div>

    <button 
        type="submit"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
        class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg"
    >
        <span wire:loading.remove wire:target="register">Create Account →</span>
        <span wire:loading wire:target="register">Creating account...</span>
    </button>

    <p class="text-xs text-gray-500 text-center">
        By signing up, you agree to our Terms of Service and Privacy Policy
    </p>
</form>
