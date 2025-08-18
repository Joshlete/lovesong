@extends('layouts.auth')

@section('title', 'Reset Password')
@section('icon', '🔑')
@section('heading', 'Forgot Your Password?')
@section('subheading', 'No worries! We\'ll help you get back to creating amazing songs')

@section('content')
    @session('status')
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ $value }}
            </div>
        </div>
    @endsession

    <div class="mb-6 text-gray-600 text-sm leading-relaxed">
        Just enter your email address and we'll send you a reset link. You'll be back to making viral songs in no time! 🎵
    </div>

    <x-validation-errors class="mb-6" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}"
                required 
                autofocus 
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                placeholder="your@email.com"
            >
        </div>

        <button 
            type="submit"
            class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg"
        >
            Send Reset Link 📧
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Remember your password? 
            <button 
                onclick="window.location.href = '{{ url('/') }}'"
                class="text-purple-600 hover:text-purple-700 font-semibold"
            >
                Sign In
            </button>
        </p>
    </div>
@endsection