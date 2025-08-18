<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Contact Us - {{ config('app.name', 'LoveSong') }}</title>

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
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
                <p class="text-xl text-white/90">We'd love to hear from you! Reach out with any questions.</p>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-8 bg-green-100/95 backdrop-blur-sm border border-green-400 text-green-700 px-6 py-4 rounded-xl relative shadow-lg" 
                     role="alert"
                     x-data="{ show: true }" 
                     x-show="show"
                     x-transition.opacity.duration.300ms>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-8 bg-red-100/95 backdrop-blur-sm border border-red-400 text-red-700 px-6 py-4 rounded-xl relative shadow-lg" 
                     role="alert"
                     x-data="{ show: true }" 
                     x-show="show"
                     x-transition.opacity.duration.300ms>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Contact Form -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">💌 Send us a Message</h2>
                    
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                                   placeholder="Enter your full name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                                   placeholder="your@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" 
                                    name="subject" 
                                    required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 @error('subject') border-red-500 @enderror">
                                <option value="">Select a topic...</option>
                                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Question</option>
                                <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="billing" {{ old('subject') == 'billing' ? 'selected' : '' }}>Billing & Payments</option>
                                <option value="song-request" {{ old('subject') == 'song-request' ? 'selected' : '' }}>Song Request Help</option>
                                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Inquiry</option>
                                <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback & Suggestions</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      required
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 @error('message') border-red-500 @enderror"
                                      placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transform hover:scale-105 transition shadow-lg">
                            🚀 Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    
                    <!-- Quick Contact -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">📞 Get in Touch</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600">📧</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Email Support</p>
                                    <a href="mailto:support@lovesong.com" class="text-purple-600 hover:text-purple-800">support@lovesong.com</a>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600">⚡</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Response Time</p>
                                    <p class="text-gray-600">Usually within 24 hours</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600">🕒</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Support Hours</p>
                                    <p class="text-gray-600">Monday - Friday, 9 AM - 6 PM EST</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Quick Links -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">❓ Common Questions</h2>
                        
                        <div class="space-y-3">
                            <a href="#" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <p class="font-medium text-gray-900">How long does song creation take?</p>
                                <p class="text-sm text-gray-600">Usually 24-48 hours after payment</p>
                            </a>
                            
                            <a href="#" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <p class="font-medium text-gray-900">Can I request revisions?</p>
                                <p class="text-sm text-gray-600">Yes, one revision is included</p>
                            </a>
                            
                            <a href="#" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <p class="font-medium text-gray-900">What file formats do you provide?</p>
                                <p class="text-sm text-gray-600">High-quality MP3 and WAV files</p>
                            </a>
                            
                            <a href="#" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <p class="font-medium text-gray-900">Do you offer refunds?</p>
                                <p class="text-sm text-gray-600">Yes, see our terms for details</p>
                            </a>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">🌟 Follow Us</h2>
                        
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center hover:bg-purple-200 transition">
                                <span class="text-purple-600">📘</span>
                            </a>
                            <a href="#" class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center hover:bg-purple-200 transition">
                                <span class="text-purple-600">🐦</span>
                            </a>
                            <a href="#" class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center hover:bg-purple-200 transition">
                                <span class="text-purple-600">📸</span>
                            </a>
                            <a href="#" class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center hover:bg-purple-200 transition">
                                <span class="text-purple-600">🎵</span>
                            </a>
                        </div>
                        
                        <p class="text-gray-600 text-sm mt-4">Stay updated with new features and song samples!</p>
                    </div>
                    
                </div>
            </div>

        </div>
    </main>

    @include('partials.landing-footer')

</body>
</html>
