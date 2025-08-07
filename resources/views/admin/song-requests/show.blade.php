<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin: Song Request #{{ $songRequest->id }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.song-requests.edit', $songRequest) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.song-requests.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8">
                            <!-- Status Banner -->
                            <div class="mb-6 p-4 rounded-lg 
                                @if($songRequest->status === 'pending') bg-yellow-50 border border-yellow-200
                                @elseif($songRequest->status === 'in_progress') bg-blue-50 border border-blue-200
                                @elseif($songRequest->status === 'completed') bg-green-50 border border-green-200
                                @else bg-red-50 border border-red-200
                                @endif">
                                <div class="flex items-center justify-between">
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
                                            Request ID: #{{ $songRequest->id }}
                                        </span>
                                    </div>
                                    <!-- Quick Status Update -->
                                    <div class="flex space-x-1">
                                        @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                                            @if($songRequest->status !== $status)
                                                <form method="POST" action="{{ route('admin.song-requests.update-status', $songRequest) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" 
                                                            class="px-2 py-1 text-xs rounded
                                                                @if($status === 'pending') bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                                                @elseif($status === 'in_progress') bg-blue-100 text-blue-800 hover:bg-blue-200
                                                                @elseif($status === 'completed') bg-green-100 text-green-800 hover:bg-green-200
                                                                @else bg-red-100 text-red-800 hover:bg-red-200
                                                                @endif"
                                                            onclick="return confirm('Change status to {{ ucfirst(str_replace('_', ' ', $status)) }}?')">
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

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
                                        <p class="text-gray-900 font-mono text-sm">{{ $songRequest->payment_reference }}</p>
                                    </div>
                                    @endif

                                    @if($songRequest->file_url)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            File URL
                                        </label>
                                        <a href="{{ $songRequest->file_url }}" 
                                           target="_blank"
                                           class="text-indigo-600 hover:text-indigo-900 text-sm break-all">
                                            {{ $songRequest->file_url }}
                                        </a>
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
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Information -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-sm text-gray-900">{{ $songRequest->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <a href="mailto:{{ $songRequest->user->email }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-900">
                                        {{ $songRequest->user->email }}
                                    </a>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Joined</label>
                                    <p class="text-sm text-gray-900">{{ $songRequest->user->created_at->format('F j, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Requests</label>
                                    <p class="text-sm text-gray-900">{{ $songRequest->user->songRequests()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.song-requests.edit', $songRequest) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Edit Request
                                </a>
                                
                                <form method="POST" 
                                      action="{{ route('admin.song-requests.destroy', $songRequest) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this song request? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>