<div class="space-y-6">
    <!-- Status Banner -->
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                    @if($songRequest->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($songRequest->status === 'in_progress') bg-blue-100 text-blue-800
                    @elseif($songRequest->status === 'completed') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    @if($songRequest->status === 'pending') ⏳ Pending
                    @elseif($songRequest->status === 'in_progress') 🎨 In Progress
                    @elseif($songRequest->status === 'completed') ✅ Completed
                    @else ❌ Cancelled
                    @endif
                </span>
                <div class="text-sm text-gray-600">
                    <div class="font-medium">Request #{{ $songRequest->id }}</div>
                    <div>Created {{ $songRequest->created_at->diffForHumans() }}</div>
                </div>
            </div>
            
            <!-- Quick Status Actions -->
            @if(!$editMode)
            <div class="flex flex-wrap gap-2">
                @if($songRequest->status !== 'pending')
                    <button wire:click="quickStatusUpdate('pending')" 
                            wire:confirm="Change status to Pending?"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition">
                        ⏳ Set Pending
                    </button>
                @endif
                @if($songRequest->status !== 'in_progress')
                    <button wire:click="quickStatusUpdate('in_progress')" 
                            wire:confirm="Change status to In Progress?"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition">
                        🎨 Set In Progress
                    </button>
                @endif
                @if($songRequest->status !== 'completed')
                    <button wire:click="markAsDelivered" 
                            wire:confirm="Mark as completed and delivered?"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition">
                        ✅ Mark Delivered
                    </button>
                @endif
                @if($songRequest->status !== 'cancelled')
                    <button wire:click="quickStatusUpdate('cancelled')" 
                            wire:confirm="Cancel this song request?"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition">
                        ❌ Cancel
                    </button>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Edit Form Modal -->
    @if($editMode)
    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">✏️ Edit Song Request</h3>
            <button wire:click="toggleEditMode" 
                    class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form wire:submit="updateSongRequest" class="space-y-6">
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select wire:model.live="status" 
                        id="status" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending">⏳ Pending</option>
                    <option value="in_progress">🎨 In Progress</option>
                    <option value="completed">✅ Completed</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Reference -->
            <div>
                <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">
                    Payment Reference
                </label>
                <input type="text" 
                       wire:model="payment_reference"
                       id="payment_reference" 
                       placeholder="Enter payment reference or transaction ID"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('payment_reference')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Song File Upload -->
            <div>
                <label for="song_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Song File Upload
                </label>
                <input type="file" 
                       wire:model="song_file"
                       id="song_file"
                       accept=".mp3,.wav,.flac,.m4a,.aac,.ogg"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('song_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if($song_file)
                    <div class="mt-2 flex items-center text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        File selected: {{ $song_file->getClientOriginalName() }}
                    </div>
                @endif
                <p class="mt-1 text-xs text-gray-500">
                    Supported formats: MP3, WAV, FLAC, M4A, AAC, OGG (max 50MB)
                </p>
            </div>

            <!-- Delivered At -->
            <div>
                <label for="delivered_at" class="block text-sm font-medium text-gray-700 mb-2">
                    Delivery Date & Time
                </label>
                <input type="datetime-local" 
                       wire:model="delivered_at"
                       id="delivered_at" 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('delivered_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Leave empty to automatically set when status changes to "Completed".
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button"
                        wire:click="toggleEditMode"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="updateSongRequest"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="updateSongRequest">Update Song Request</span>
                    <span wire:loading wire:target="updateSongRequest">Updating...</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Song Information -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">🎵 Song Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Song Title</label>
                        <p class="text-lg font-semibold text-purple-700">{{ $songDetails['song_title'] }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                        <p class="text-2xl font-bold text-green-600">
                            ${{ number_format($songRequest->price_usd, 2) }}
                            <span class="text-sm font-normal text-gray-500">{{ $songRequest->currency }}</span>
                        </p>
                    </div>
                    
                    @if($songDetails['style'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Musical Style</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($songDetails['style']) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($songDetails['custom_style'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Style</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ $songDetails['custom_style'] }}
                        </span>
                    </div>
                    @endif
                    
                    @if($songDetails['mood'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mood</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ ucfirst($songDetails['mood']) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($songDetails['custom_mood'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Mood</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                            {{ $songDetails['custom_mood'] }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Song Content -->
            @if($songDetails['lyrics_idea'] || $songDetails['song_description'])
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">📝 Song Content</h3>
                <div class="space-y-6">
                    @if($songDetails['song_description'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Overall Song Description</label>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songDetails['song_description'] }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($songDetails['lyrics_idea'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lyrics Ideas</label>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songDetails['lyrics_idea'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Technical Details -->
            @if($songDetails['genre_details'] || $songDetails['tempo'] || $songDetails['vocals'] || $songDetails['instruments'])
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">🎛️ Technical Specifications</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($songDetails['genre_details'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Genre Details</label>
                        <p class="text-gray-900 bg-gray-50 rounded-lg p-3">{{ $songDetails['genre_details'] }}</p>
                    </div>
                    @endif
                    
                    @if($songDetails['tempo'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempo</label>
                        <p class="text-gray-900 bg-gray-50 rounded-lg p-3">{{ $songDetails['tempo'] }}</p>
                    </div>
                    @endif
                    
                    @if($songDetails['vocals'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vocals</label>
                        <p class="text-gray-900 bg-gray-50 rounded-lg p-3">{{ $songDetails['vocals'] }}</p>
                    </div>
                    @endif
                    
                    @if($songDetails['instruments'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instruments</label>
                        <p class="text-gray-900 bg-gray-50 rounded-lg p-3 whitespace-pre-line">{{ $songDetails['instruments'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Creative Details -->
            @if($songDetails['song_structure'] || $songDetails['inspiration'] || $songDetails['special_instructions'])
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">✨ Creative Direction</h3>
                <div class="space-y-6">
                    @if($songDetails['song_structure'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Song Structure</label>
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songDetails['song_structure'] }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($songDetails['inspiration'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Inspiration</label>
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songDetails['inspiration'] }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($songDetails['special_instructions'])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                            <p class="text-gray-900 whitespace-pre-line">{{ $songDetails['special_instructions'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- File Information -->
            @if($songRequest->file_path)
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">🎵 Song File</h3>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $songRequest->original_filename ?? 'Song file' }}</p>
                            <p class="text-sm text-gray-600">
                                Uploaded {{ $songRequest->updated_at->diffForHumans() }}
                                @if($songRequest->file_size)
                                    • {{ number_format($songRequest->file_size / 1024 / 1024, 1) }} MB
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.song-requests.download', $songRequest) }}" 
                           target="_blank"
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition">
                            📥 Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-gray-900 mb-6">⏰ Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div>
                            <span class="font-medium text-gray-900">Request Created</span>
                            <span class="text-gray-500 ml-2">{{ $songRequest->created_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                    
                    @if($songRequest->payment_completed_at)
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <div>
                            <span class="font-medium text-gray-900">Payment Completed</span>
                            <span class="text-gray-500 ml-2">{{ $songRequest->payment_completed_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                    
                    @if($songRequest->delivered_at)
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <div>
                            <span class="font-medium text-gray-900">Delivered</span>
                            <span class="text-gray-500 ml-2">{{ $songRequest->delivered_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Customer Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-gray-900 font-medium">{{ $songRequest->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <a href="mailto:{{ $songRequest->user->email }}" 
                           class="text-purple-600 hover:text-purple-800 transition">
                            {{ $songRequest->user->email }}
                        </a>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                        <p class="text-gray-900">{{ $songRequest->user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Customer Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Requests</span>
                        <span class="font-bold text-gray-900">{{ $userStats['total_requests'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Completed</span>
                        <span class="font-bold text-green-600">{{ $userStats['completed_requests'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Spent</span>
                        <span class="font-bold text-purple-600">${{ number_format($userStats['total_spent'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($songRequest->payment_reference || $songRequest->payment_status)
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">💳 Payment Details</h3>
                <div class="space-y-3">
                    @if($songRequest->payment_status)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($songRequest->payment_status === 'succeeded') bg-green-100 text-green-800
                            @elseif($songRequest->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($songRequest->payment_status) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($songRequest->payment_reference)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                        <p class="text-gray-900 font-mono text-xs bg-gray-50 p-2 rounded">{{ $songRequest->payment_reference }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-white/20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Actions</h3>
                <div class="space-y-3">
                    @if(!$editMode)
                    <button wire:click="toggleEditMode"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition text-center">
                        ✏️ Edit Request
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.song-requests.index') }}" 
                       class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition text-center block">
                        ⬅️ Back to List
                    </a>
                    
                    <button wire:click="deleteSongRequest" 
                            wire:confirm="Are you sure you want to delete this song request? This action cannot be undone."
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition">
                        🗑️ Delete Request
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>