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

                    <form method="POST" action="{{ route('admin.song-requests.update', $songRequest) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Request Details (Read-only) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <div>
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

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pending" {{ old('status', $songRequest->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $songRequest->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $songRequest->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $songRequest->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Reference -->
                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700">
                                Payment Reference
                            </label>
                            <input type="text" 
                                   name="payment_reference" 
                                   id="payment_reference" 
                                   value="{{ old('payment_reference', $songRequest->payment_reference) }}"
                                   placeholder="Enter payment reference or transaction ID"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Song File Upload -->
                        <div class="mb-6">
                            <label for="song_file" class="block text-sm font-medium text-gray-700 mb-2">
                                🎵 Upload Song File
                            </label>
                            
                            @if($songRequest->hasS3File())
                                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-green-800">
                                                ✅ Current File: {{ $songRequest->getDisplayFilename() }}
                                            </p>
                                            <p class="text-sm text-green-600">
                                                Size: {{ $songRequest->formatted_file_size }} • 
                                                Uploaded: {{ $songRequest->updated_at->format('M j, Y g:i A') }}
                                            </p>
                                        </div>
                                        <a href="{{ route('admin.song-requests.download', $songRequest) }}" 
                                           class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" 
                                   name="song_file" 
                                   id="song_file"
                                   accept=".mp3,.wav,.m4a,.aac,.ogg,.flac"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            
                            @error('song_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div class="mt-2 text-sm text-gray-500">
                                <p><strong>✨ Pro tip:</strong> Uploading a file will automatically mark the request as "completed"!</p>
                                <p>Accepted formats: MP3, WAV, M4A, AAC, OGG, FLAC</p>
                                @php
                                    $uploadMax = ini_get('upload_max_filesize');
                                    $postMax = ini_get('post_max_size');
                                    $serverLimit = $uploadMax < $postMax ? $uploadMax : $postMax;
                                @endphp
                                <p><strong>Current upload limit:</strong> {{ $serverLimit }}</p>

                                @if($songRequest->hasS3File())
                                    <p class="text-amber-600">⚠️ Uploading a new file will replace the current one.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Delivered At -->
                        <div>
                            <label for="delivered_at" class="block text-sm font-medium text-gray-700">
                                Delivery Date & Time
                            </label>
                            <input type="datetime-local" 
                                   name="delivered_at" 
                                   id="delivered_at" 
                                   value="{{ old('delivered_at', $songRequest->delivered_at?->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('delivered_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Leave empty to automatically set when status changes to "Completed".
                            </p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6">
                            <a href="{{ route('admin.song-requests.show', $songRequest) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Song Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill delivered_at when status changes to completed
        document.getElementById('status').addEventListener('change', function() {
            const deliveredAtField = document.getElementById('delivered_at');
            if (this.value === 'completed' && !deliveredAtField.value) {
                const now = new Date();
                const localTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000);
                deliveredAtField.value = localTime.toISOString().slice(0, 16);
            } else if (this.value !== 'completed') {
                // Optionally clear the field if status is not completed
                // deliveredAtField.value = '';
            }
        });
    </script>
</x-app-layout>