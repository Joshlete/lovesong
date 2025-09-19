@if(config('services.tiktok.pixel_id'))
@push('tiktok-events')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Safely default payload if not provided
    const payload = @json(isset($data) ? $data : []);
    if (typeof ttq !== 'undefined') {
        ttq.track('{{ $event }}', payload);
        @if(config('app.debug'))
        console.log('TikTok Event Tracked:', '{{ $event }}', payload);
        @endif
    } else {
        console.warn('TikTok pixel not loaded for event:', '{{ $event }}');
    }
});
</script>
@endpush
@endif