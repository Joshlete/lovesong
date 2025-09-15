<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Privacy Policy - {{ config('app.name', 'LoveSong') }}</title>

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
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Privacy Policy</h1>
                <p class="text-xl text-white/90">How we protect and handle your information</p>
                <p class="text-white/70 mt-2">Last updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 md:p-12">
                <div class="prose prose-lg max-w-none">
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📋 Information We Collect</h2>
                    <p class="mb-6">We collect information you provide directly to us, such as when you create an account, make a song request, or contact us:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li><strong>Account Information:</strong> Name, email address, and password</li>
                        <li><strong>Song Request Details:</strong> Song preferences, lyrics ideas, and creative requirements</li>
                        <li><strong>Payment Information:</strong> Processed securely through Stripe (we don't store card details)</li>
                        <li><strong>Communication:</strong> Messages you send us through our contact forms or support</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🛡️ How We Use Your Information</h2>
                    <p class="mb-6">We use the information we collect to:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Create and deliver your custom songs</li>
                        <li>Process payments and send receipts</li>
                        <li>Communicate with you about your song requests</li>
                        <li>Improve our services and user experience</li>
                        <li>Send important account and service updates</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🔐 Information Security</h2>
                    <p class="mb-6">We take your privacy seriously and implement appropriate security measures:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>All data is encrypted in transit and at rest</li>
                        <li>Payment processing is handled securely by Stripe</li>
                        <li>Access to personal information is restricted to authorized personnel only</li>
                        <li>Regular security audits and updates</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🤝 Information Sharing</h2>
                    <p class="mb-6">We do not sell, trade, or otherwise transfer your personal information to third parties, except:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li><strong>Service Providers:</strong> Trusted partners who help us operate our service (like payment processors)</li>
                        <li><strong>Song Creation:</strong> We use your song requirements to create your custom song</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🍪 Cookies and Tracking</h2>
                    <p class="mb-6">We use cookies and similar technologies to:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Remember your login status and preferences</li>
                        <li>Analyze website traffic and usage patterns</li>
                        <li>Improve site functionality and user experience</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">👤 Your Rights</h2>
                    <p class="mb-6">You have the right to:</p>
                    <ul class="list-disc pl-6 mb-6 text-gray-700">
                        <li>Access, update, or delete your personal information</li>
                        <li>Request a copy of your data</li>
                        <li>Opt out of marketing communications</li>
                        <li>Close your account at any time</li>
                    </ul>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📞 Contact Us</h2>
                    <p class="mb-6">If you have any questions about this Privacy Policy or your personal information, please contact us:</p>
                    <div class="text-center mb-6">
                        <a href="{{ route('contact') }}" 
                           class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transform hover:scale-105 transition shadow-lg">
                            💌 Contact Our Team
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">📝 Changes to This Policy</h2>
                    <p class="mb-6">We may update this Privacy Policy from time to time. We will notify you of any significant changes through our service. Your continued use of our service after such modifications constitutes your acceptance of the updated Privacy Policy.</p>

                </div>
            </div>

        </div>
    </main>

    @include('partials.landing-footer')

</body>
</html>
