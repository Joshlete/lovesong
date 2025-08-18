<!-- Login Form -->
<form wire:submit.prevent="login" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input 
            type="email" 
            wire:model="loginEmail"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="your@email.com"
            required
        >
        @error('loginEmail')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
            type="password" 
            wire:model="loginPassword"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="••••••••"
            required
        >
        @error('loginPassword')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center">
            <input 
                type="checkbox" 
                wire:model="remember"
                class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
            >
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-700">
            Forgot password?
        </a>
    </div>

    <button 
        type="submit"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
        class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg"
    >
        <span wire:loading.remove wire:target="login">Sign In →</span>
        <span wire:loading wire:target="login">Signing in...</span>
    </button>
</form>
