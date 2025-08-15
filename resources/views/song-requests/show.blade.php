<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Song Request for {{ $songRequest->recipient_name }}
            </h2>
            <div class="space-x-2">
                @if($songRequest->status === 'pending')
                    <a href="{{ route('song-requests.edit', $songRequest) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('song-requests.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Status Banner -->
                    <div class="mb-6 p-4 rounded-lg 
                        @if($songRequest->status === 'pending') bg-yellow-50 border border-yellow-200
                        @elseif($songRequest->status === 'in_progress') bg-blue-50 border border-blue-200
                        @elseif($songRequest->status === 'completed') bg-green-50 border border-green-200
                        @else bg-red-50 border border-red-200
                        @endif">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($songRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($songRequest->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($songRequest->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $songRequest->status)) }}
                            </span>
                            <span class="ml-3 text-sm 
                                @if($songRequest->status === 'pending') text-yellow-700
                                @elseif($songRequest->status === 'in_progress') text-blue-700
                                @elseif($songRequest->status === 'completed') text-green-700
                                @else text-red-700
                                @endif">
                                @if($songRequest->status === 'pending')
                                    Your song request is waiting to be reviewed.
                                @elseif($songRequest->status === 'in_progress')
                                    Your song is currently being created.
                                @elseif($songRequest->status === 'completed')
                                    Your song has been completed!
                                @else
                                    This request has been cancelled.
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    @if($songRequest->status === 'pending' && $songRequest->payment_status !== 'succeeded')
                        <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-orange-900">Payment Required</h3>
                                    <p class="text-sm text-orange-700">Complete payment to begin work on your custom song.</p>
                                    <p class="text-lg font-bold text-orange-900 mt-1">Total: ${{ number_format($songRequest->price_usd, 2) }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('payments.show', $songRequest) }}" 
                                       class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg">
                                        Complete Payment
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif($songRequest->payment_status === 'succeeded')
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-medium text-green-900">Payment Complete</h3>
                                    <p class="text-sm text-green-700">
                                        Payment received! Work on your custom song will begin shortly.
                                        @if($songRequest->payment_completed_at)
                                            <br><small>Paid: {{ $songRequest->payment_completed_at->format('M j, Y g:i A') }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Request Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Recipient Name
                                </label>
                                <p class="text-lg text-gray-900">{{ $songRequest->recipient_name }}</p>
                            </div>

                            @if($songRequest->style)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Musical Style
                                </label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($songRequest->style) }}
                                </span>
                            </div>
                            @endif

                            @if($songRequest->mood)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mood
                                </label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($songRequest->mood) }}
                                </span>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Price
                                </label>
                                <p class="text-2xl font-bold text-gray-900">
                                    ${{ number_format($songRequest->price_usd, 2) }}
                                    <span class="text-sm font-normal text-gray-500">{{ $songRequest->currency }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Request Created
                                </label>
                                <p class="text-gray-900">{{ $songRequest->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            @if($songRequest->delivered_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivered
                                </label>
                                <p class="text-gray-900">{{ $songRequest->delivered_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            @endif

                            @if($songRequest->payment_reference)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Payment Reference
                                </label>
                                <p class="text-gray-900 font-mono">{{ $songRequest->payment_reference }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($songRequest->lyrics_idea)
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lyrics Ideas
                        </label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songRequest->lyrics_idea }}</p>
                        </div>
                    </div>
                    @endif

                                        @if($songRequest->hasFile() && $songRequest->status === 'completed')
                     <div class="mt-8">
                         <label class="block text-sm font-medium text-gray-700 mb-2">
                             Your Song
                         </label>
                         <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                             <div class="flex items-center mb-4">
                                 <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                 </svg>
                                 <div class="ml-3">
                                     <p class="text-lg font-medium text-green-800">🎉 Your song is ready!</p>
                                     <p class="text-sm text-green-600">Your custom love song has been completed and is ready for download.</p>
                                 </div>
                             </div>
                             
                             @if($songRequest->hasS3File())
                                 <div class="bg-white rounded-md p-3 mb-4 border border-green-200">
                                     <div class="flex items-center justify-between">
                                         <div>
                                             <p class="text-sm font-medium text-gray-900">{{ $songRequest->getDisplayFilename() }}</p>
                                             <p class="text-xs text-gray-500">
                                                 {{ $songRequest->formatted_file_size }} • 
                                                 Delivered {{ $songRequest->delivered_at->format('M j, Y g:i A') }}
                                             </p>
                                         </div>
                                         <div class="flex items-center text-green-600">
                                             <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                             </svg>
                                             <span class="text-xs">Secure</span>
                                         </div>
                                     </div>
                                 </div>
                             @endif
                             
                             <div class="flex flex-col sm:flex-row gap-3">
                                 <a href="{{ route('song-requests.download', $songRequest) }}" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                     <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                     </svg>
                                     Download Song
                                 </a>
                                 
                                 @if($songRequest->hasS3File())
                                     <div class="flex items-center text-sm text-green-700 bg-green-100 px-3 py-2 rounded-md">
                                         <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                         </svg>
                                         Secure download link (expires in 1 hour)
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     @endif

                    @if($songRequest->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Need to make changes?</p>
                            </div>
                            <div class="space-x-3">
                                <a href="{{ route('song-requests.edit', $songRequest) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit Request
                                </a>
                                <form method="POST" 
                                      action="{{ route('song-requests.destroy', $songRequest) }}" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this song request? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>