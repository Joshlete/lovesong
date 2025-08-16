<form wire:submit="save" class="space-y-6">
    <!-- Status -->
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">
            Status <span class="text-red-500">*</span>
        </label>
        <select wire:model.live="status" 
                id="status" 
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
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
               wire:model="payment_reference"
               id="payment_reference" 
               placeholder="Enter payment reference or transaction ID"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('payment_reference')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Song File Upload -->
    <div class="mb-6">
        @livewire('admin.file-upload', ['songRequest' => $songRequest], key($songRequest->id.'-file-upload'))
    </div>

    <!-- Delivered At -->
    <div>
        <label for="delivered_at" class="block text-sm font-medium text-gray-700">
            Delivery Date & Time
        </label>
        <input type="datetime-local" 
               wire:model="delivered_at"
               id="delivered_at" 
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
                wire:loading.attr="disabled"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50">
            <span wire:loading.remove>Update Song Request</span>
            <span wire:loading>Updating...</span>
        </button>
    </div>
</form>

@script
<script>
    // Listen for file upload events from the FileUpload component
    $wire.on('fileUploaded', () => {
        $wire.call('handleFileUploaded');
    });
</script>
@endscript