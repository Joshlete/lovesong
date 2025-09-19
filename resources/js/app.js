import './bootstrap';
import './time-manager';

// TikTok Pixel helper functions
window.trackTikTokEvent = function(event, data = {}) {
    if (typeof ttq !== 'undefined') {
        ttq.track(event, data);
    } else {
        console.warn('TikTok pixel not available for event:', event);
    }
};

// Enhanced TikTok tracking helpers
window.TikTokTracker = {
    // Track page view (automatically done by pixel)
    pageView() {
        if (typeof ttq !== 'undefined') {
            ttq.page();
        }
    },

    // Track when user starts creating a song request
    initiateCheckout(data = {}) {
        this.track('InitiateCheckout', {
            content_type: 'product',
            content_name: 'Custom Song Request',
            currency: 'USD',
            ...data
        });
    },

    // Track when user reaches payment page
    addToCart(songRequestData) {
        this.track('AddToCart', {
            content_type: 'product',
            content_name: 'Custom Song Request',
            value: songRequestData.price_usd || 0,
            currency: songRequestData.currency || 'USD',
            contents: [{
                content_id: String(songRequestData.id || ''),
                content_name: `Custom Song for ${songRequestData.recipient_name || 'Customer'}`,
                quantity: 1,
                price: songRequestData.price_usd || 0
            }]
        });
    },

    // Track successful purchase
    purchase(songRequestData) {
        this.track('Purchase', {
            value: songRequestData.price_usd || 0,
            currency: songRequestData.currency || 'USD',
            content_type: 'product',
            content_name: 'Custom Song Request',
            contents: [{
                content_id: String(songRequestData.id || ''),
                content_name: `Custom Song for ${songRequestData.recipient_name || 'Customer'}`,
                quantity: 1,
                price: songRequestData.price_usd || 0
            }]
        });
    },

    // Generic track method
    track(event, data = {}) {
        window.trackTikTokEvent(event, data);
    }
};

// Listen for Livewire events for better integration
document.addEventListener('livewire:init', function () {
    // Track payment success from Livewire components
    Livewire.hook('message.processed', (message, component) => {
        // Check if this component should trigger TikTok tracking
        if (component.el.dataset.tiktokTrack) {
            const trackingType = component.el.dataset.tiktokTrack;
            const songRequestData = component.get('songRequest');
            
            if (trackingType === 'payment-success' && songRequestData?.payment_status === 'succeeded') {
                window.TikTokTracker.purchase(songRequestData);
            }
        }
    });
    
    // Track errors for debugging
    Livewire.hook('message.failed', (message, component) => {
        console.error('Livewire error - TikTok tracking may be affected:', message);
    });
});
