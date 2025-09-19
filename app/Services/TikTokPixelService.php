<?php

namespace App\Services;

class TikTokPixelService
{
    private string $pixelId;

    public function __construct()
    {
        $this->pixelId = config('services.tiktok.pixel_id', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->pixelId);
    }

    public function getPixelId(): string
    {
        return $this->pixelId;
    }

    public function generateTrackingData(string $event, array $data = []): array
    {
        return [
            'event' => $event,
            'data' => $data,
            'pixel_id' => $this->pixelId,
        ];
    }

    /**
     * Generate event data for song request creation (InitiateCheckout)
     */
    public function generateInitiateCheckoutEvent(array $songRequestData = []): array
    {
        return $this->generateTrackingData('InitiateCheckout', [
            'content_type' => 'product',
            'content_name' => 'Custom Song Request',
            'currency' => $songRequestData['currency'] ?? 'USD',
            'value' => $songRequestData['price'] ?? null,
        ]);
    }

    /**
     * Generate event data for payment page (AddToCart)
     */
    public function generateAddToCartEvent(array $songRequestData): array
    {
        return $this->generateTrackingData('AddToCart', [
            'content_type' => 'product',
            'content_name' => 'Custom Song Request',
            'value' => $songRequestData['price_usd'] ?? 0,
            'currency' => $songRequestData['currency'] ?? 'USD',
            'contents' => [[
                'content_id' => (string) ($songRequestData['id'] ?? ''),
                'content_name' => 'Custom Song for ' . ($songRequestData['recipient_name'] ?? 'Customer'),
                'quantity' => 1,
                'price' => $songRequestData['price_usd'] ?? 0
            ]]
        ]);
    }

    /**
     * Generate event data for successful purchase
     */
    public function generatePurchaseEvent(array $songRequestData): array
    {
        return $this->generateTrackingData('Purchase', [
            'value' => $songRequestData['price_usd'] ?? 0,
            'currency' => $songRequestData['currency'] ?? 'USD',
            'content_type' => 'product',
            'content_name' => 'Custom Song Request',
            'contents' => [[
                'content_id' => (string) ($songRequestData['id'] ?? ''),
                'content_name' => 'Custom Song for ' . ($songRequestData['recipient_name'] ?? 'Customer'),
                'quantity' => 1,
                'price' => $songRequestData['price_usd'] ?? 0
            ]]
        ]);
    }
}