@props(['for' => '', 'value'])

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'enhanced-label']) }}>
    {{ $value ?? $slot }}
</label>
