<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Terms of Service - {{ config('app.name', 'LoveSong') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 min-h-screen">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/10 backdrop-blur-md z-50 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-lg sm:text-2xl font-bold text-white flex items-center">
                        <span class="mr-1 sm:mr-2">🎵</span>
                        <span>LoveSong</span>
                    </a>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white hover:text-yellow-300 transition text-sm sm:text-base">Dashboard</a>
                    @else
                        <a href="{{ url('/') }}" class="text-white hover:text-yellow-300 transition text-sm sm:text-base">Home</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="pt-20 pb-12 px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Terms of Service</h1>
                <p class="text-xl text-white/90">The rules and guidelines for using LoveSong</p>
                <p class="text-white/70 mt-2">Last updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-12">
                <div class="prose prose-lg max-w-none">
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🎵 Our Service</h2>
                    <p class="mb-6">LoveSong provides custom song creation services where we create personalized songs with professional quality based on your requirements. By using our service, you agree to these terms.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📝 Song Requests</h2>
                    <p class="mb-6">When you submit a song request:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>You provide creative direction, lyrics ideas, and preferences</li>
                        <li>We create an original song based on your requirements</li>
                        <li>Typical delivery time is 24-48 hours after payment confirmation</li>
                        <li>You'll receive a high-quality digital audio file</li>
                        <li>One revision is included if needed within 7 days of delivery</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">💳 Payment Terms</h2>
                    <p class="mb-6">Payment terms and conditions:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Payment is due at the time of song request submission</li>
                        <li>We accept major credit cards and digital payments via Stripe</li>
                        <li>All prices are in USD and include applicable taxes</li>
                        <li>Refunds are processed according to our refund policy below</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🔄 Refund Policy</h2>
                    <p class="mb-6">We stand behind our work with a satisfaction guarantee:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li><strong>Before Creation:</strong> Full refund if you cancel before we start working</li>
                        <li><strong>After Delivery:</strong> One free revision if the song doesn't match your requirements</li>
                        <li><strong>Quality Issues:</strong> Full refund if we can't deliver a song that meets basic quality standards</li>
                        <li><strong>Late Delivery:</strong> 50% refund if delivery exceeds 72 hours without notice</li>
                        <li>Refund requests must be submitted within 7 days of delivery</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🎨 Intellectual Property</h2>
                    <p class="mb-6">Rights and ownership:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li><strong>Your Song:</strong> You own the final song and can use it for personal purposes</li>
                        <li><strong>Commercial Use:</strong> Commercial licensing available for additional fee</li>
                        <li><strong>Originality:</strong> All songs are original compositions created for you</li>
                        <li><strong>Portfolio Use:</strong> We may use anonymized samples for marketing with your permission</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🚫 Prohibited Content</h2>
                    <p class="mb-6">We reserve the right to refuse song requests that contain:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Hate speech, discrimination, or offensive content</li>
                        <li>Copyrighted material without proper licensing</li>
                        <li>Illegal activities or content</li>
                        <li>Personal attacks or harassment</li>
                        <li>Content that violates our community guidelines</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">👤 Account Responsibilities</h2>
                    <p class="mb-6">As a user, you agree to:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Provide accurate and up-to-date account information</li>
                        <li>Keep your login credentials secure</li>
                        <li>Use the service only for lawful purposes</li>
                        <li>Respect other users and our team members</li>
                        <li>Notify us immediately of any security breaches</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">⚖️ Limitation of Liability</h2>
                    <p class="mb-6">Our liability is limited to the amount you paid for the service. We're not responsible for:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Indirect, incidental, or consequential damages</li>
                        <li>Loss of profits, data, or business opportunities</li>
                        <li>Use of the song in ways that violate third-party rights</li>
                        <li>Technical issues beyond our reasonable control</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📞 Contact & Disputes</h2>
                    <p class="mb-6">For questions or disputes:</p>
                    <div class="text-center mb-6">
                        <a href="{{ route('contact') }}" 
                           class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transform hover:scale-105 transition shadow-lg">
                            💌 Contact Our Team
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🔄 Changes to Terms</h2>
                    <p class="mb-6">We may update these terms from time to time. Significant changes will be communicated via service notification. Continued use of our service constitutes acceptance of updated terms.</p>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-8">
                        <p class="text-yellow-800">
                            <strong>Questions?</strong> If you have any questions about these terms, please <a href="{{ route('contact') }}" class="text-yellow-600 hover:text-yellow-800 underline">contact us</a>. We're here to help!
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </main>

    @include('partials.landing-footer')

</body>
</html>
