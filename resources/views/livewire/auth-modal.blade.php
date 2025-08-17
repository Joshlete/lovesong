<div>
    <!-- Backdrop -->
    <div 
        x-data="{ show: @entangle('showModal') }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$wire.closeModal()"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40"
        style="display: none;"
    ></div>

    <!-- Modal Container -->
    <div 
        x-data="{ show: @entangle('showModal') }"
        x-show="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
        style="display: none;"
    >
        <!-- Modal Content - Slides from bottom on mobile, fades in on desktop -->
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            @click.stop
            class="w-full max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl fixed bottom-0 sm:relative sm:bottom-auto max-h-[90vh] overflow-hidden"
        >
            @include('livewire.partials.auth-modal-header')
            @include('livewire.partials.auth-modal-tabs')
            @include('livewire.partials.auth-modal-forms')
        </div>
    </div>
</div>