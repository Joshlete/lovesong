@extends('layouts.auth')

@section('title', 'Reset Password')
@section('icon', '🔐')
@section('heading', 'Create New Password')
@section('subheading', 'You\'re almost back! Just create a secure new password')

@section('content')
    <div class="mb-6 text-gray-600 text-sm leading-relaxed">
        Make it strong and memorable! You'll use this to access your song requests and track your orders. 🎵
    </div>

    <x-validation-errors class="mb-6" />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}"
                required 
                autofocus 
                autocomplete="username"
                readonly
                class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600"
            >
            <p class="text-xs text-gray-500 mt-1">This email is associated with your account</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                placeholder="Enter a strong password"
            >
            <p class="text-xs text-gray-500 mt-1">At least 8 characters with a mix of letters and numbers</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                placeholder="Confirm your password"
            >
        </div>

        <button 
            type="submit"
            class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg"
        >
            Update Password & Sign In 🎉
        </button>
    </form>

    <!-- Security Note -->
    <div class="mt-6 p-4 bg-blue-50 rounded-xl">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="text-sm text-blue-700 font-medium">Security Tip</p>
                <p class="text-xs text-blue-600 mt-1">After updating your password, you'll be automatically signed in and can start creating songs!</p>
            </div>
        </div>
    </div>
@endsection