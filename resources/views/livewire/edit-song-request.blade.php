<div>
    <!-- Edit Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <!-- Animated background elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-4 right-6 w-16 h-16 bg-white rounded-full animate-pulse"></div>
                <div class="absolute bottom-4 left-8 w-12 h-12 bg-white rounded-full animate-bounce"></div>
                <div class="absolute top-1/2 left-1/2 w-8 h-8 bg-white rounded-full animate-ping"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">✏️ Edit Song Request</h1>
                        <p class="text-white/90 text-lg">Update "{{ $songRequest->recipient_name }}" details</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('song-requests.show', $songRequest) }}" 
                           class="bg-white/20 text-white px-4 py-2 rounded-full font-medium hover:bg-white/30 transition">
                            ← Back to View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit="update" class="space-y-8">
        <!-- Basic Song Information -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
            <h3 class="text-xl font-bold text-purple-900 mb-4 flex items-center">
                🎵 Basic Song Information
                <span class="ml-2 text-sm font-normal text-purple-600">(Required)</span>
            </h3>
            
            <div class="space-y-6" x-data="{ style: @entangle('style'), mood: @entangle('mood') }">
                <!-- Song Title -->
                <div>
                    <label for="song_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Song Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="song_title"
                           id="song_title" 
                           placeholder="e.g., 'Forever My Love', 'Happy Birthday Sarah'"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('song_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Song Description -->
                <div>
                    <label for="song_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Overall Song Description <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="song_description" required
                              id="song_description" 
                              rows="3" 
                              placeholder="Describe the overall feeling, story, or purpose of this song. Who is it for? What occasion?"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                    @error('song_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Style and Mood Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Musical Style -->
                    <div class="space-y-4">
                        <div>
                            <label for="style" class="block text-sm font-medium text-gray-700 mb-2">
                                Musical Style <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="style" 
                                    id="style" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">Select a style...</option>
                                <option value="rock">Rock</option>
                                <option value="pop">Pop</option>
                                <option value="country">Country</option>
                                <option value="jazz">Jazz</option>
                                <option value="blues">Blues</option>
                                <option value="classical">Classical</option>
                                <option value="hip-hop">Hip-Hop</option>
                                <option value="folk">Folk</option>
                                <option value="r&b">R&B</option>
                                <option value="electronic">Electronic</option>
                                <option value="acoustic">Acoustic</option>
                                <option value="other">🎯 Other (specify below)</option>
                            </select>
                            @error('style')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Custom Style -->
                        <div x-show="style === 'other'" x-cloak wire:key="custom-style">
                            <label for="custom_style" class="block text-sm font-medium text-gray-700 mb-2">
                                Specify Your Style <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="custom_style"
                                   id="custom_style" 
                                   placeholder="e.g., Indie Folk, Synthwave, Latin Pop"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            @error('custom_style')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mood -->
                    <div class="space-y-4">
                        <div>
                            <label for="mood" class="block text-sm font-medium text-gray-700 mb-2">
                                Mood <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="mood" 
                                    id="mood" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">Select a mood...</option>
                                <option value="happy">Happy</option>
                                <option value="romantic">Romantic</option>
                                <option value="sad">Sad</option>
                                <option value="energetic">Energetic</option>
                                <option value="calm">Calm</option>
                                <option value="nostalgic">Nostalgic</option>
                                <option value="uplifting">Uplifting</option>
                                <option value="melancholic">Melancholic</option>
                                <option value="celebratory">Celebratory</option>
                                <option value="dramatic">Dramatic</option>
                                <option value="other">🎯 Other (specify below)</option>
                            </select>
                            @error('mood')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Custom Mood -->
                        <div x-show="mood === 'other'" x-cloak wire:key="custom-mood">
                            <label for="custom_mood" class="block text-sm font-medium text-gray-700 mb-2">
                                Specify Your Mood <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="custom_mood"
                                   id="custom_mood" 
                                   placeholder="e.g., Bittersweet, Triumphant, Mysterious"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            @error('custom_mood')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Song Content & Lyrics -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
                ✍️ Song Content & Lyrics
            </h3>
            
            <div class="space-y-6">
                <!-- Lyrics Ideas -->
                <div>
                    <label for="lyrics_idea" class="block text-sm font-medium text-gray-700 mb-2">
                        Lyrics Ideas & Themes
                    </label>
                    <textarea wire:model="lyrics_idea" 
                              id="lyrics_idea" 
                              rows="4" 
                              placeholder="Share any lyrics ideas, themes, specific messages, or lines you'd like included. What story should this song tell?"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                    @error('lyrics_idea')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">The more details you provide, the more personalized your song will be!</p>
                </div>

                <!-- Inspiration -->
                <div>
                    <label for="inspiration" class="block text-sm font-medium text-gray-700 mb-2">
                        Musical Inspiration
                    </label>
                    <textarea wire:model="inspiration" 
                              id="inspiration" 
                              rows="2" 
                              placeholder="Any songs, artists, or musical references that inspire the sound you want? e.g., 'Like Ed Sheeran but more upbeat'"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                    @error('inspiration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Musical Details -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
            <h3 class="text-xl font-bold text-green-900 mb-4 flex items-center">
                🎼 Musical Details
                <span class="ml-2 text-sm font-normal text-green-600">(Optional - helps with customization)</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Genre Details -->
                <div>
                    <label for="genre_details" class="block text-sm font-medium text-gray-700 mb-2">
                        Genre Specifics
                    </label>
                    <input type="text" 
                           wire:model="genre_details"
                           id="genre_details" 
                           placeholder="e.g., Alternative Rock, Soft Jazz, Country Ballad"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('genre_details')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempo -->
                <div>
                    <label for="tempo" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempo/Speed
                    </label>
                    <select wire:model="tempo" 
                            id="tempo" 
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        <option value="">No preference</option>
                        <option value="slow">Slow & Gentle</option>
                        <option value="medium">Medium Pace</option>
                        <option value="fast">Fast & Energetic</option>
                        <option value="ballad">Ballad (Slow & Emotional)</option>
                        <option value="upbeat">Upbeat & Dancing</option>
                    </select>
                    @error('tempo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vocals -->
                <div>
                    <label for="vocals" class="block text-sm font-medium text-gray-700 mb-2">
                        Vocal Style Preference
                    </label>
                    <input type="text" 
                           wire:model="vocals"
                           id="vocals" 
                           placeholder="e.g., Male/Female, Soft, Powerful, Raspy, Sweet"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('vocals')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instruments -->
                <div class="md:col-span-2">
                    <label for="instruments" class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Instruments
                    </label>
                    <textarea wire:model="instruments" 
                              id="instruments" 
                              rows="2" 
                              placeholder="Any specific instruments you'd like featured? e.g., Piano, Guitar, Strings, Drums"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                    @error('instruments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Song Structure -->
                <div class="md:col-span-2">
                    <label for="song_structure" class="block text-sm font-medium text-gray-700 mb-2">
                        Song Structure Ideas
                    </label>
                    <textarea wire:model="song_structure" 
                              id="song_structure" 
                              rows="2" 
                              placeholder="Any specific structure requests? e.g., 'Bridge with instrumental solo', 'Simple verse-chorus', 'Include a key change'"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                    @error('song_structure')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
            <h3 class="text-xl font-bold text-orange-900 mb-4 flex items-center">
                💡 Special Instructions
            </h3>
            
            <div>
                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                    Anything Else?
                </label>
                <textarea wire:model="special_instructions" 
                          id="special_instructions" 
                          rows="3" 
                          placeholder="Any other special requests, specific moments to capture, or creative direction you'd like to share?"
                          class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"></textarea>
                @error('special_instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">The more details you provide, the more I can tailor your song to your exact vision!</p>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-xl p-6 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-purple-900">Update Your Song Request</h3>
                    <p class="text-purple-700 text-sm">Save your changes to enhance your custom song</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('song-requests.show', $songRequest) }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105">
                        Cancel
                    </a>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:transform-none">
                        <span wire:loading.remove>
                            💾 Update Song Request
                        </span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>