<div>
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Application Settings</h2>
        
        @if (session()->has('success'))
            <div class="mb-4 border border-green-200 rounded-md p-3 bg-green-50 text-green-600">
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Song Price Setting -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">
                    🎵 Song Pricing
                </h3>
                <div class="max-w-xl">
                    <label for="song_price" class="block text-sm font-medium text-gray-700">
                        Default Song Price (USD)
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               wire:model="song_price"
                               id="song_price" 
                               step="0.01"
                               min="1"
                               max="999.99"
                               class="block w-full pl-7 pr-12 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="100.00">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">USD</span>
                        </div>
                    </div>
                    @error('song_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        This price will be applied to all new song requests. Users will pay this amount when requesting a custom song.
                    </p>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50">
                <span wire:loading.remove>Save Settings</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>