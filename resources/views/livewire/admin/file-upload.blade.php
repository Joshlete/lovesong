<div>
    <label for="song_file" class="block text-sm font-medium text-gray-700 mb-2">
        🎵 Upload Song File
    </label>
    
    <!-- Current File Display -->
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

    <!-- Upload Area -->
    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors 
                {{ $uploading ? 'pointer-events-none opacity-50' : 'cursor-pointer' }}"
         x-data="fileUpload()" 
         x-on:drop.prevent="handleDrop($event)" 
         x-on:dragover.prevent="dragover = true" 
         x-on:dragleave.prevent="dragover = false"
         x-bind:class="dragover ? 'border-indigo-500 bg-indigo-50' : ''">
        
        <!-- Upload Progress -->
        @if($uploading)
            <div class="flex items-center justify-center mb-4">
                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-indigo-600 font-medium">Uploading file... {{ $uploadProgress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
            </div>
        @else
            <!-- Upload UI -->
            <div>
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-900 mb-2">Drop your file here or click to browse</p>
                    <p class="text-sm text-gray-500 mb-4">
                        <span class="font-medium">Accepted formats:</span> MP3, WAV, M4A, AAC, OGG, FLAC
                    </p>
                    <button type="button" 
                            x-on:click="$refs.fileInput.click()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Choose File
                    </button>
                </div>
            </div>

            <!-- Hidden file input -->
            <input type="file" 
                   x-ref="fileInput"
                   wire:model="file"
                   accept=".mp3,.wav,.m4a,.aac,.ogg,.flac"
                   class="hidden">
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading wire:target="file" class="mt-3">
        <div class="flex items-center text-indigo-600">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm">Processing file...</span>
        </div>
    </div>

    <!-- Messages -->
    @if($uploadSuccess)
        <div class="mt-3 border border-green-200 rounded-md p-3 bg-green-50 text-green-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium">✅ File uploaded successfully! Request marked as completed.</p>
                <button wire:click="clearMessages" class="text-green-400 hover:text-green-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if($uploadError)
        <div class="mt-3 border border-red-200 rounded-md p-3 bg-red-50 text-red-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium">❌ {{ $uploadError }}</p>
                <button wire:click="clearMessages" class="text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @error('file')
        <div class="mt-3 border border-red-200 rounded-md p-3 bg-red-50 text-red-600">
            <p class="text-sm font-medium">❌ {{ $message }}</p>
        </div>
    @enderror
    
    <div class="mt-2 text-sm text-gray-500">
        <p><strong>✨ Upload happens immediately!</strong> Files are uploaded as soon as you select them.</p>
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

<script>
    function fileUpload() {
        return {
            dragover: false,
            handleDrop(event) {
                this.dragover = false;
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    // Trigger Livewire file upload
                    const fileInput = this.$refs.fileInput;
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }
    }
</script>