@props(['type' => 'submit', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'enhanced-button-primary',
    'secondary' => 'enhanced-button-secondary', 
    'danger' => 'enhanced-button-danger',
    default => 'enhanced-button-primary'
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
