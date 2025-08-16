<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin: Edit Song Request #{{ $songRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Customer Info Header -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Name:</span>
                                <span class="text-gray-900">{{ $songRequest->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Email:</span>
                                <a href="mailto:{{ $songRequest->user->email }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $songRequest->user->email }}
                                </a>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Request Date:</span>
                                <span class="text-gray-900">{{ $songRequest->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Request Details (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Recipient Name
                            </label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $songRequest->recipient_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Price
                            </label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">${{ number_format($songRequest->price_usd, 2) }}</p>
                        </div>

                        @if($songRequest->style)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Musical Style
                            </label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ ucfirst($songRequest->style) }}</p>
                        </div>
                        @endif

                        @if($songRequest->mood)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mood
                            </label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ ucfirst($songRequest->mood) }}</p>
                        </div>
                        @endif
                    </div>

                    @if($songRequest->lyrics_idea)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lyrics Ideas
                        </label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songRequest->lyrics_idea }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Admin-editable fields -->
                    <hr class="my-8">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Controls</h3>

                    @livewire('admin.edit-song-request', ['songRequest' => $songRequest], key($songRequest->id.'-edit'))
                </div>
            </div>
        </div>
    </div>


</x-app-layout>