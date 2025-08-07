<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Song Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form method="POST" action="{{ route('song-requests.update', $songRequest) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Recipient Name -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700">
                                Recipient Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="recipient_name" 
                                   id="recipient_name" 
                                   value="{{ old('recipient_name', $songRequest->recipient_name) }}"
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
                                <option value="rock" {{ old('style', $songRequest->style) == 'rock' ? 'selected' : '' }}>Rock</option>
                                <option value="pop" {{ old('style', $songRequest->style) == 'pop' ? 'selected' : '' }}>Pop</option>
                                <option value="country" {{ old('style', $songRequest->style) == 'country' ? 'selected' : '' }}>Country</option>
                                <option value="jazz" {{ old('style', $songRequest->style) == 'jazz' ? 'selected' : '' }}>Jazz</option>
                                <option value="blues" {{ old('style', $songRequest->style) == 'blues' ? 'selected' : '' }}>Blues</option>
                                <option value="classical" {{ old('style', $songRequest->style) == 'classical' ? 'selected' : '' }}>Classical</option>
                                <option value="hip-hop" {{ old('style', $songRequest->style) == 'hip-hop' ? 'selected' : '' }}>Hip-Hop</option>
                                <option value="folk" {{ old('style', $songRequest->style) == 'folk' ? 'selected' : '' }}>Folk</option>
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
                                <option value="happy" {{ old('mood', $songRequest->mood) == 'happy' ? 'selected' : '' }}>Happy</option>
                                <option value="romantic" {{ old('mood', $songRequest->mood) == 'romantic' ? 'selected' : '' }}>Romantic</option>
                                <option value="sad" {{ old('mood', $songRequest->mood) == 'sad' ? 'selected' : '' }}>Sad</option>
                                <option value="energetic" {{ old('mood', $songRequest->mood) == 'energetic' ? 'selected' : '' }}>Energetic</option>
                                <option value="calm" {{ old('mood', $songRequest->mood) == 'calm' ? 'selected' : '' }}>Calm</option>
                                <option value="nostalgic" {{ old('mood', $songRequest->mood) == 'nostalgic' ? 'selected' : '' }}>Nostalgic</option>
                                <option value="uplifting" {{ old('mood', $songRequest->mood) == 'uplifting' ? 'selected' : '' }}>Uplifting</option>
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
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('lyrics_idea', $songRequest->lyrics_idea) }}</textarea>
                            @error('lyrics_idea')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price_usd" class="block text-sm font-medium text-gray-700">
                                Price (USD) <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="price_usd" 
                                       id="price_usd" 
                                       value="{{ old('price_usd', $songRequest->price_usd) }}"
                                       step="0.01" 
                                       min="0" 
                                       required 
                                       class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            @error('price_usd')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between pt-6">
                            <a href="{{ route('song-requests.show', $songRequest) }}" 
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
</x-app-layout>