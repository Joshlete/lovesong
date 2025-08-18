<div class="max-w-2xl mx-auto">
    <!-- Order Summary -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20 space-y-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">🎼 Order Summary</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Song Details -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Song Title</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $songRequest->recipient_name }}</p>
                </div>
                
                @if($songRequest->style)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Style</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        🎼 {{ ucfirst($songRequest->style) }}
                    </span>
                </div>
                @endif
                
                @if($songRequest->mood)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mood</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        😊 {{ ucfirst($songRequest->mood) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Pricing -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                <div class="text-center">
                    <p class="text-sm text-green-700 mb-2">Total Amount</p>
                    <p class="text-3xl font-bold text-green-900">
                        ${{ number_format($songRequest->price_usd, 2) }}
                        <span class="text-sm font-normal text-green-600">{{ $songRequest->currency }}</span>
                    </p>
                    <p class="text-xs text-green-600 mt-2">Professional musicians • Full rights included</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Verification Warning -->
    @if(!$userEmailVerified)
        <div class="bg-orange-50/95 backdrop-blur-sm border border-orange-200 rounded-2xl p-6 mb-8 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-orange-900 mb-2">
                        📧 Email Verification Required
                    </h3>
                    <p class="text-orange-700 mb-4">You need to verify your email address before completing payment to ensure secure delivery of your custom song.</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        @livewire('resend-verification-button', ['variant' => 'small'])
                        <a href="{{ route('verification.notice') }}" class="text-orange-800 underline text-sm hover:text-orange-900 transition font-medium">
                            Learn More →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Status -->
    @if($songRequest->payment_status === 'pending' || $songRequest->payment_status === 'failed')
        @if(!$paymentSuccess)
            <!-- Payment Form -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20 space-y-6 mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">💳 Secure Payment</h3>
                
                <!-- Stripe Checkout Button -->
                <div class="space-y-4">
                    <button type="button" 
                            wire:click="processPayment"
                            wire:loading.attr="disabled"
                            wire:target="processPayment"
                            @if(!$userEmailVerified) disabled @endif
                            class="w-full @if($userEmailVerified) bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 @else bg-gray-400 cursor-not-allowed @endif text-white font-bold py-4 px-6 rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg">
                        <span wire:loading.remove wire:target="processPayment" class="flex items-center justify-center">
                            @if($userEmailVerified)
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Pay ${{ number_format($songRequest->price_usd, 2) }} with Stripe
                            @else
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Verify Email to Continue
                            @endif
                        </span>
                        <span wire:loading wire:target="processPayment" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Redirecting to secure checkout...
                        </span>
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600 flex items-center justify-center">
                            <svg class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Secure payment powered by Stripe
                        </p>
                    </div>
                </div>
                
                @if(app()->isLocal())
                    <!-- Development Test Buttons (only in local environment) -->
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">🧪 Development Testing</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button type="button" 
                                    wire:click="processTestPayment('success')"
                                    wire:loading.attr="disabled"
                                    wire:target="processTestPayment"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="processTestPayment">✅ Test Success</span>
                                <span wire:loading wire:target="processTestPayment">Processing...</span>
                            </button>
                            
                            <button type="button" 
                                    wire:click="processTestPayment('fail')"
                                    wire:loading.attr="disabled"
                                    wire:target="processTestPayment"
                                    class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="processTestPayment">❌ Test Failure</span>
                                <span wire:loading wire:target="processTestPayment">Processing...</span>
                            </button>
                        </div>
                        
                        <!-- Stripe Test Cards Info -->
                        <div class="bg-blue-50/95 backdrop-blur-sm border border-blue-200 rounded-xl p-4 mt-4">
                            <p class="text-sm font-medium text-blue-900 mb-3">💳 Test Card Numbers:</p>
                            <div class="text-sm text-blue-700 space-y-2">
                                <div class="flex justify-between">
                                    <span>✅ Success:</span>
                                    <code class="bg-blue-100 px-2 py-1 rounded">4242 4242 4242 4242</code>
                                </div>
                                <div class="flex justify-between">
                                    <span>❌ Declined:</span>
                                    <code class="bg-blue-100 px-2 py-1 rounded">4000 0000 0000 0002</code>
                                </div>
                                <div class="flex justify-between">
                                    <span>⚠️ Insufficient:</span>
                                    <code class="bg-blue-100 px-2 py-1 rounded">4000 0000 0000 9995</code>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Error Message -->
            @if($paymentError)
                <div class="bg-red-50/95 backdrop-blur-sm border border-red-200 rounded-2xl p-6 mb-8 shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-900 mb-2">Payment Error</h3>
                            <p class="text-red-700">{{ $paymentError }}</p>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <!-- Success State -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-8 text-center mb-8 shadow-xl border border-green-200">
                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-green-900 mb-4">🎉 Payment Successful!</h3>
                <p class="text-green-700 text-lg">Your custom song is now in production. We'll keep you updated every step of the way!</p>
            </div>

            <!-- Next Steps -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20 space-y-6 mb-8">
                <h4 class="text-xl font-bold text-gray-900 mb-6">🎵 What happens next?</h4>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-indigo-500 text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                        <div>
                            <p class="font-semibold text-gray-900">Review & Planning</p>
                            <p class="text-sm text-gray-600">Our team reviews your request within 24 hours</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                        <div>
                            <p class="font-semibold text-gray-900">Professional Creation</p>
                            <p class="text-sm text-gray-600">Musicians compose & record your unique song</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                        <div>
                            <p class="font-semibold text-gray-900">Delivery & Download</p>
                            <p class="text-sm text-gray-600">Receive your completed song in 7-14 days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Another Song -->
            <div class="text-center">
                <a href="{{ route('song-requests.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold rounded-xl transition transform hover:scale-105 shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Another Song
                </a>
            </div>
        @endif
    @else
        <!-- Already Paid -->
        <div class="bg-green-50/95 backdrop-blur-sm border border-green-200 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-green-900">✅ Payment Complete</h3>
                    <p class="text-green-700">Your song is in production. Check your song request page for updates!</p>
                </div>
            </div>
        </div>
    @endif
</div>

