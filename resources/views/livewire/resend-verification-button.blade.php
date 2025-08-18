<div>
    @if($emailSent && $this->showSuccessMessage())
        <!-- Success State -->
        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium">✅ Verification Email Sent!</p>
                    <p class="text-sm">Check your inbox (and spam folder) for the verification link.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Resend Button -->
    <button 
        wire:click="sendVerificationEmail"
        wire:loading.attr="disabled"
        @disabled($cooldownSeconds > 0)
        class="{{ $this->getButtonClass() }} {{ $cooldownSeconds > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
    >
        <span wire:loading.remove wire:target="sendVerificationEmail">
            @if($cooldownSeconds > 0)
                @if($emailSent)
                    📨 Resend in {{ $cooldownSeconds }}s
                @else
                    Wait {{ $cooldownSeconds }}s
                @endif
            @else
                @if($emailSent)
                    📨 Resend Verification Email
                @else
                    📧 Send Verification Email
                @endif
            @endif
        </span>
        <span wire:loading wire:target="sendVerificationEmail" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sending...
        </span>
    </button>

    @if($cooldownSeconds > 0 && $this->showProgressBar())
        <!-- Cooldown Progress Bar -->
        <div class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-purple-500 h-1 rounded-full transition-all duration-1000" 
                     style="width: {{ (($cooldownSeconds / 60) * 100) }}%"></div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-3 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @script
    <script>
        let resendInterval = null;
        const RESEND_COOLDOWN_KEY = 'lovesong_resend_verification_cooldown';
        
        function clearResendCountdown() {
            if (resendInterval) {
                clearInterval(resendInterval);
                resendInterval = null;
            }
        }
        
        function getResendCooldownFromStorage() {
            const stored = localStorage.getItem(RESEND_COOLDOWN_KEY);
            if (!stored) return 0;
            
            const data = JSON.parse(stored);
            const now = Math.floor(Date.now() / 1000);
            const remaining = Math.max(0, data.endsAt - now);
            
            if (remaining <= 0) {
                localStorage.removeItem(RESEND_COOLDOWN_KEY);
                return 0;
            }
            
            return remaining;
        }
        
        function setResendCooldownInStorage(seconds) {
            const endsAt = Math.floor(Date.now() / 1000) + seconds;
            localStorage.setItem(RESEND_COOLDOWN_KEY, JSON.stringify({ endsAt }));
        }
        
        function startResendClientCountdown() {
            clearResendCountdown();
            
            resendInterval = setInterval(() => {
                const remaining = getResendCooldownFromStorage();
                
                if (remaining <= 0) {
                    clearResendCountdown();
                    $wire.set('cooldownSeconds', 0);
                } else {
                    $wire.set('cooldownSeconds', remaining);
                }
            }, 1000);
        }
        
        // Initialize on page load
        function initializeComponent() {
            const storedCooldown = getResendCooldownFromStorage();
            if (storedCooldown > 0) {
                $wire.call('initializeCooldown', storedCooldown);
                startResendClientCountdown();
            }
        }
        
        // Initialize when component is ready
        document.addEventListener('DOMContentLoaded', initializeComponent);
        
        // Also initialize immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeComponent);
        } else {
            initializeComponent();
        }
        
        // Handle cooldown timer
        $wire.on('startResendCooldown', (event) => {
            setResendCooldownInStorage(event.seconds);
            startResendClientCountdown();
        });
        
        // Initialize when Livewire component is hydrated
        Livewire.hook('component.initialized', (component) => {
            if (component.fingerprint.name === 'resend-verification-button') {
                // Small delay to ensure component is fully ready
                setTimeout(initializeComponent, 100);
            }
        });
    </script>
    @endscript
</div>