<!-- Tabs -->
<div class="flex bg-gray-100">
    <button 
        wire:click="switchTab('login')"
        class="flex-1 py-3 font-semibold transition {{ $activeTab === 'login' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-600 hover:text-purple-600' }}"
    >
        Sign In
    </button>
    <button 
        wire:click="switchTab('register')"
        class="flex-1 py-3 font-semibold transition {{ $activeTab === 'register' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-600 hover:text-purple-600' }}"
    >
        Sign Up
    </button>
</div>
