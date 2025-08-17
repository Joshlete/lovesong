<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Email Verification Banner -->
            @if (!auth()->user()->hasVerifiedEmail())
                <div class="mb-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg shadow-lg p-6 relative overflow-hidden">
                    <!-- Animated background pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-0 w-20 h-20 bg-white rounded-full transform -translate-x-10 -translate-y-10"></div>
                        <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full transform translate-x-16 translate-y-16"></div>
                    </div>
                    
                    <div class="relative flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-full">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.56 2.123L8 7.383V5a1 1 0 112 0v2.383l1.5 1.152a1 1 0 01.5.865V12a1 1 0 11-2 0V9.4L8 8.35 6 9.4V12a1 1 0 11-2 0V9.4a1 1 0 01.5-.865z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-white">📧 Verify Your Email to Complete Orders</h3>
                            <p class="text-white/90 text-sm mt-1">You can create song requests, but you'll need to verify your email before making any payments.</p>
                            
                            <div class="mt-3 flex items-center space-x-3">
                                @livewire('resend-verification-button', ['variant' => 'banner'])
                                
                                <a href="{{ route('verification.notice') }}" class="text-white underline text-sm hover:text-white/80 flex items-center transition">
                                    Learn More →
                                </a>
                            </div>
                        </div>
                        
                        <div class="ml-4">
                            <span class="text-2xl">🎵</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('song-requests.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Song Request
                            </a>
                            <a href="{{ route('song-requests.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View All Requests
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Song Requests -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Recent Song Requests</h3>
                        <a href="{{ route('song-requests.index') }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View all →
                        </a>
                    </div>
                    
                    @php
                        $recentRequests = auth()->user()->songRequests()->latest()->take(5)->get();
                    @endphp

                    @if($recentRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentRequests as $request)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $request->recipient_name }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                @if($request->style)
                                                    <span>{{ ucfirst($request->style) }}</span>
                                                @endif
                                                @if($request->mood)
                                                    <span>{{ ucfirst($request->mood) }}</span>
                                                @endif
                                                <span>${{ number_format($request->price_usd, 2) }}</span>
                                                <span>{{ $request->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('song-requests.show', $request) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No song requests yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first song request.</p>
                            <div class="mt-6">
                                <a href="{{ route('song-requests.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create Song Request
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
