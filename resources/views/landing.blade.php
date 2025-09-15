<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LoveSong') }} - Custom Songs That Go Viral 🎵</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Meta Tags for Social Sharing -->
    <meta property="og:title" content="Get Your Custom Song in 24 Hours 🎵">
    <meta property="og:description" content="Get YOUR custom song crafted with professional quality. Perfect for TikTok, gifts, or going viral!">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
</head>
<body class="antialiased bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 min-h-screen">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/10 backdrop-blur-md z-50 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <span class="text-lg sm:text-2xl font-bold text-white flex items-center">
                        <span class="mr-1 sm:mr-2">🎵</span>
                        <span>LoveSong</span>
                    </span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4" x-data>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white hover:text-yellow-300 transition text-sm sm:text-base">Song Collection</a>
                    @else
                        <button 
                            @click="$dispatch('openLoginModal')"
                            class="text-white hover:text-yellow-300 transition text-sm sm:text-base"
                        >
                            Login
                        </button>
                        <button 
                            @click="$dispatch('openRegisterModal')"
                            class="bg-white text-purple-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full font-semibold hover:bg-yellow-300 transition transform hover:scale-105 text-sm sm:text-base"
                        >
                            Get Started
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @include('partials.landing-hero')
    @include('partials.landing-social-proof')
    @include('partials.landing-how-it-works')
    @include('partials.landing-use-cases')
    @include('partials.landing-faq')
    @include('partials.landing-final-cta')
    @include('partials.landing-footer')

    <!-- Auth Modal -->
    @livewire('auth-modal')

    <!-- Simple Countdown Script -->
    <script>
        // Countdown timer
        function updateCountdown() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            
            const diff = tomorrow - now;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Add some animation classes
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            });
            
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }
    </style>
</body>
</html>
