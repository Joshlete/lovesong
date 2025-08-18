<div class="max-w-md mx-auto">
    <!-- Order Summary -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
        
        <div class="space-y-3 mb-4">
            <div class="flex justify-between">
                <span class="text-gray-600">Custom Song for</span>
                <span class="font-medium">{{ $songRequest->recipient_name }}</span>
            </div>
            
            @if($songRequest->style)
            <div class="flex justify-between">
                <span class="text-gray-600">Style</span>
                <span class="font-medium">{{ ucfirst($songRequest->style) }}</span>
            </div>
            @endif
            
            @if($songRequest->mood)
            <div class="flex justify-between">
                <span class="text-gray-600">Mood</span>
                <span class="font-medium">{{ ucfirst($songRequest->mood) }}</span>
            </div>
            @endif
            
            <div class="border-t pt-3 mt-4">
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total</span>
                    <span>${{ number_format($songRequest->price_usd, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Verification Warning -->
    @if(!$userEmailVerified)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">
                        📧 Email Verification Required
                    </h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <p>You need to verify your email address before completing payment.</p>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center space-x-3">
                            @livewire('resend-verification-button', ['variant' => 'small'])
                            
                            <a href="{{ route('verification.notice') }}" class="text-orange-800 underline text-sm hover:text-orange-900 transition">
                                Learn More →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Status -->
    @if($songRequest->payment_status === 'pending' || $songRequest->payment_status === 'failed')
        @if(!$paymentSuccess)
            <!-- Payment Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                
                <!-- Stripe Checkout Button -->
                <button type="button" 
                        wire:click="processPayment"
                        wire:loading.attr="disabled"
                        wire:target="processPayment"
                        @if(!$userEmailVerified) disabled @endif
                        class="w-full @if($userEmailVerified) bg-green-600 hover:bg-green-700 @else bg-gray-400 cursor-not-allowed @endif text-white font-medium py-3 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed mb-4">
                    <span wire:loading.remove wire:target="processPayment">
                        @if($userEmailVerified)
                            Pay ${{ number_format($songRequest->price_usd, 2) }} with Stripe
                        @else
                            Verify Email to Continue
                        @endif
                    </span>
                    <span wire:loading wire:target="processPayment">
                        Redirecting to checkout...
                    </span>
                </button>
                
                @if(app()->isLocal())
                    <!-- Development Test Buttons (only in local environment) -->
                    <div class="space-y-2">
                        <button type="button" 
                                wire:click="processTestPayment('success')"
                                wire:loading.attr="disabled"
                                wire:target="processTestPayment"
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="processTestPayment">
                                ✅ Test Successful Payment
                            </span>
                            <span wire:loading wire:target="processTestPayment">
                                Processing...
                            </span>
                        </button>
                        
                        <button type="button" 
                                wire:click="processTestPayment('fail')"
                                wire:loading.attr="disabled"
                                wire:target="processTestPayment"
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed mb-4">
                            <span wire:loading.remove wire:target="processTestPayment">
                                ❌ Test Failed Payment
                            </span>
                            <span wire:loading wire:target="processTestPayment">
                                Processing...
                            </span>
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center">
                            Development Mode: These buttons simulate payment outcomes
                        </p>
                    </div>
                    
                    <!-- Stripe Test Cards Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                        <p class="text-xs text-blue-700 mb-2">
                            <span class="font-medium">For real Stripe testing, use these cards:</span>
                        </p>
                        <div class="text-xs text-blue-600 space-y-1">
                            <div>✅ Success: <code>4242 4242 4242 4242</code></div>
                            <div>❌ Declined: <code>4000 0000 0000 0002</code></div>
                            <div>⚠️ Insufficient funds: <code>4000 0000 0000 9995</code></div>
                            <div>🔒 Authentication required: <code>4000 0025 0000 3155</code></div>
                        </div>
                    </div>
                @endif
                
                <p class="text-sm text-gray-500 text-center">
                    🔒 Secure payment powered by Stripe
                </p>
            </div>

            <!-- Error Message -->
            @if($paymentError)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <p class="text-red-700 text-sm">{{ $paymentError }}</p>
                </div>
            @endif

        @else
            <!-- Success State -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center mb-6">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-lg font-semibold text-green-900 mb-2">Payment Successful!</h3>
                <p class="text-green-700">Your custom song is now in production.</p>
            </div>

            <!-- Next Steps -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-medium text-gray-900 mb-3">What's next?</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">1</span>
                        <span>Review within 24 hours</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">2</span>
                        <span>Composition & recording</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-3">3</span>
                        <span>Delivery in 7-14 days</span>
                    </div>
                </div>
            </div>

            <!-- Create Another Song -->
            <div class="text-center">
                <a href="{{ route('song-requests.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Create Another Song
                </a>
            </div>
        @endif
    @else
        <!-- Already Paid -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="font-medium text-green-900">Payment Complete</h3>
                    <p class="text-sm text-green-700">Your song is in production.</p>
                </div>
            </div>
        </div>
    @endif
</div>

