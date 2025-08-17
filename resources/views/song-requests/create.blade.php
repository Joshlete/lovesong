<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Song Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form method="POST" action="{{ route('song-requests.store') }}" class="space-y-6">
                        @csrf

                        <!-- Recipient Name -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700">
                                Recipient Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="recipient_name" 
                                   id="recipient_name" 
                                   value="{{ old('recipient_name') }}"
                                   required 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('recipient_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Style -->
                        <div>
                            <label for="style" class="block text-sm font-medium text-gray-700">
                                Musical Style
                            </label>
                            <select name="style" 
                                    id="style" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a style...</option>
                                <option value="rock" {{ old('style') == 'rock' ? 'selected' : '' }}>Rock</option>
                                <option value="pop" {{ old('style') == 'pop' ? 'selected' : '' }}>Pop</option>
                                <option value="country" {{ old('style') == 'country' ? 'selected' : '' }}>Country</option>
                                <option value="jazz" {{ old('style') == 'jazz' ? 'selected' : '' }}>Jazz</option>
                                <option value="blues" {{ old('style') == 'blues' ? 'selected' : '' }}>Blues</option>
                                <option value="classical" {{ old('style') == 'classical' ? 'selected' : '' }}>Classical</option>
                                <option value="hip-hop" {{ old('style') == 'hip-hop' ? 'selected' : '' }}>Hip-Hop</option>
                                <option value="folk" {{ old('style') == 'folk' ? 'selected' : '' }}>Folk</option>
                            </select>
                            @error('style')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mood -->
                        <div>
                            <label for="mood" class="block text-sm font-medium text-gray-700">
                                Mood
                            </label>
                            <select name="mood" 
                                    id="mood" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a mood...</option>
                                <option value="happy" {{ old('mood') == 'happy' ? 'selected' : '' }}>Happy</option>
                                <option value="romantic" {{ old('mood') == 'romantic' ? 'selected' : '' }}>Romantic</option>
                                <option value="sad" {{ old('mood') == 'sad' ? 'selected' : '' }}>Sad</option>
                                <option value="energetic" {{ old('mood') == 'energetic' ? 'selected' : '' }}>Energetic</option>
                                <option value="calm" {{ old('mood') == 'calm' ? 'selected' : '' }}>Calm</option>
                                <option value="nostalgic" {{ old('mood') == 'nostalgic' ? 'selected' : '' }}>Nostalgic</option>
                                <option value="uplifting" {{ old('mood') == 'uplifting' ? 'selected' : '' }}>Uplifting</option>
                            </select>
                            @error('mood')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lyrics Ideas -->
                        <div>
                            <label for="lyrics_idea" class="block text-sm font-medium text-gray-700">
                                Lyrics Ideas
                            </label>
                            <textarea name="lyrics_idea" 
                                      id="lyrics_idea" 
                                      rows="4" 
                                      placeholder="Share any lyrics ideas, themes, or messages you'd like included in the song..."
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('lyrics_idea') }}</textarea>
                            @error('lyrics_idea')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Song Price
                            </label>
                            <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format(\App\Models\Setting::getSongPrice(), 2) }}</p>
                                    </div>
                                    <div class="text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Song can be purchased after the request is created.
                            </p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6">
                            <a href="{{ route('song-requests.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Song Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>