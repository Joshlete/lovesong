@extends('layouts.auth')

@section('title', 'Verify Email')
@section('icon', '📧')
@section('heading', 'Check Your Email!')
@section('subheading', 'We\'ve sent you a verification link to get started')

@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium">Email Sent!</p>
                    <p class="text-sm">We've sent a fresh verification link to your email.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6 text-gray-600 text-sm leading-relaxed">
        Before you can start creating your viral songs, we need to verify your email address. Check your inbox and click the verification link we just sent you! 🎵
    </div>

    <!-- Steps -->
    <div class="mb-6 space-y-3">
        <div class="flex items-center text-sm">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold mr-3">1</div>
            <span class="text-gray-700">Check your email inbox</span>
        </div>
        <div class="flex items-center text-sm">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold mr-3">2</div>
            <span class="text-gray-700">Click the verification link</span>
        </div>
        <div class="flex items-center text-sm">
            <div class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-bold mr-3">3</div>
            <span class="text-gray-700">Start creating amazing songs!</span>
        </div>
    </div>

    <!-- Actions -->
    <div class="space-y-4">
        <!-- Resend Email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg"
            >
                Resend Verification Email 📨
            </button>
        </form>

        <!-- Secondary Actions -->
        <div class="flex space-x-3">
            <a
                href="{{ route('profile.show') }}"
                class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-200 transition"
            >
                Edit Profile
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-200 transition"
                >
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-6 p-4 bg-yellow-50 rounded-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="text-sm text-yellow-700 font-medium">Can't find the email?</p>
                <p class="text-xs text-yellow-600 mt-1">Check your spam folder or try resending. The email comes from no-reply@lovesong.com</p>
            </div>
        </div>
    </div>
@endsection