@props(['submit' => null])

<div {{ $attributes->merge(['class' => 'profile-form-section']) }}>
    <form @if($submit) wire:submit="{{ $submit }}" @endif>
        <!-- Header Section -->
        <div class="form-section-header">
            <div class="form-section-title">
                {{ $title }}
            </div>
            
            @if(isset($description))
                <div class="form-section-description">
                    {{ $description }}
                </div>
            @endif
        </div>

        <!-- Form Content -->
        <div class="form-section-content">
            <div class="grid grid-cols-6 gap-6">
                {{ $form }}
            </div>
        </div>

        <!-- Form Actions -->
        @if(isset($actions))
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                {{ $actions }}
            </div>
        @endif
    </form>
</div>
