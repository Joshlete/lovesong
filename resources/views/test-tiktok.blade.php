<x-guest-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h1 class="font-semibold text-2xl text-gray-800 leading-tight mb-2">
                    TikTok Pixel Testing
                </h1>
                <p class="text-gray-600">Test your TikTok pixel implementation and track events in real-time.</p>
            </div>
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Test Controls</h3>
                
                <div class="space-y-4">
                    <!-- Test Buttons -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button onclick="testInitiateCheckout()" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Test InitiateCheckout
                        </button>
                        
                        <button onclick="testAddToCart()" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Test AddToCart
                        </button>
                        
                        <button onclick="testPurchase()" 
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Test Purchase
                        </button>
                    </div>

                    <!-- Status Display -->
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">Pixel Status:</h4>
                        <div id="pixel-status" class="bg-gray-100 p-4 rounded">
                            <p>Loading...</p>
                        </div>
                    </div>

                    <!-- Console Log Display -->
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-2">Recent Events (check browser console for full details):</h4>
                        <div id="event-log" class="bg-gray-100 p-4 rounded max-h-64 overflow-y-auto">
                            <!-- Events will be logged here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test TikTok tracking events -->
    <x-tik-tok-pixel 
        event="ViewContent" 
        :data="[
            'content_type' => 'product',
            'content_name' => 'TikTok Test Page',
            'currency' => 'USD'
        ]" 
    />

    <script>
        // Test functions
        function testInitiateCheckout() {
            TikTokTracker.initiateCheckout({ 
                value: 50.00, 
                currency: 'USD' 
            });
            logEvent('InitiateCheckout triggered');
        }

        function testAddToCart() {
            TikTokTracker.addToCart({
                id: 999,
                price_usd: 75.00,
                currency: 'USD',
                recipient_name: 'Test User'
            });
            logEvent('AddToCart triggered');
        }

        function testPurchase() {
            TikTokTracker.purchase({
                id: 999,
                price_usd: 75.00,
                currency: 'USD',
                recipient_name: 'Test User'
            });
            logEvent('Purchase triggered');
        }

        function logEvent(message) {
            const eventLog = document.getElementById('event-log');
            const timestamp = new Date().toLocaleTimeString();
            eventLog.innerHTML += `<p><strong>${timestamp}:</strong> ${message}</p>`;
            eventLog.scrollTop = eventLog.scrollHeight;
        }

        // Check pixel status on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusDiv = document.getElementById('pixel-status');
            
            if (typeof ttq !== 'undefined') {
                statusDiv.innerHTML = `
                    <p class="text-green-600">✅ TikTok Pixel Loaded Successfully</p>
                    <p class="text-sm text-gray-600">Pixel ID: {{ config('services.tiktok.pixel_id') }}</p>
                `;
                logEvent('Page loaded - TikTok pixel ready');
            } else {
                statusDiv.innerHTML = `
                    <p class="text-red-600">❌ TikTok Pixel Not Detected</p>
                    <p class="text-sm text-gray-600">Check console for errors</p>
                `;
                logEvent('Page loaded - TikTok pixel not found');
            }
        });
    </script>
</x-guest-layout>
