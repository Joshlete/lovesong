<?php

namespace Tests\Helpers;

use Stripe\StripeClient;
use App\Services\PaymentService;

/**
 * Helper class for Stripe testing utilities
 */
class StripeTestHelper
{
    /**
     * Verify that Stripe PHP SDK is properly installed
     */
    public static function verifyStripeInstallation(): bool
    {
        try {
            // Check if Stripe classes exist
            if (!class_exists(StripeClient::class)) {
                throw new \Exception('Stripe\StripeClient class not found');
            }

            if (!class_exists(\Stripe\Checkout\Session::class)) {
                throw new \Exception('Stripe\Checkout\Session class not found');
            }

            if (!class_exists(\Stripe\PaymentIntent::class)) {
                throw new \Exception('Stripe\PaymentIntent class not found');
            }

            if (!class_exists(\Stripe\Webhook::class)) {
                throw new \Exception('Stripe\Webhook class not found');
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Stripe installation verification failed: " . $e->getMessage());
        }
    }

    /**
     * Verify that PaymentService can be instantiated
     */
    public static function verifyPaymentServiceInstantiation(): bool
    {
        try {
            $service = new PaymentService();
            return true;
        } catch (\Exception $e) {
            throw new \Exception("PaymentService instantiation failed: " . $e->getMessage());
        }
    }

    /**
     * Verify Stripe configuration is present
     */
    public static function verifyStripeConfiguration(): bool
    {
        $stripeKey = config('services.stripe.key');
        $stripeSecret = config('services.stripe.secret');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (empty($stripeKey)) {
            throw new \Exception('Stripe publishable key not configured');
        }

        if (empty($stripeSecret)) {
            throw new \Exception('Stripe secret key not configured');
        }

        if (empty($webhookSecret)) {
            throw new \Exception('Stripe webhook secret not configured');
        }

        // Verify key formats
        if (!str_starts_with($stripeKey, 'pk_')) {
            throw new \Exception('Invalid Stripe publishable key format');
        }

        if (!str_starts_with($stripeSecret, 'sk_')) {
            throw new \Exception('Invalid Stripe secret key format');
        }

        if (!str_starts_with($webhookSecret, 'whsec_')) {
            throw new \Exception('Invalid Stripe webhook secret format');
        }

        return true;
    }

    /**
     * Generate test Stripe webhook signature
     */
    public static function generateWebhookSignature(string $payload, string $secret = 'whsec_test_webhook_secret'): string
    {
        $timestamp = time();
        $signedPayload = $timestamp . '.' . $payload;
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        
        return "t={$timestamp},v1={$signature}";
    }

    /**
     * Create mock Stripe checkout session data
     */
    public static function createMockCheckoutSession(string $sessionId = 'cs_test_123', string $paymentIntentId = 'pi_test_456'): array
    {
        return [
            'id' => $sessionId,
            'object' => 'checkout.session',
            'payment_intent' => $paymentIntentId,
            'payment_status' => 'paid',
            'status' => 'complete',
            'url' => "https://checkout.stripe.com/c/pay/{$sessionId}",
            'customer_email' => 'test@example.com',
            'amount_total' => 2500,
            'currency' => 'usd'
        ];
    }

    /**
     * Create mock Stripe payment intent data
     */
    public static function createMockPaymentIntent(string $paymentIntentId = 'pi_test_456'): array
    {
        return [
            'id' => $paymentIntentId,
            'object' => 'payment_intent',
            'status' => 'succeeded',
            'amount' => 2500,
            'currency' => 'usd',
            'client_secret' => "{$paymentIntentId}_secret_test",
            'created' => time(),
            'metadata' => [
                'song_request_id' => '1',
                'user_id' => '1'
            ]
        ];
    }

    /**
     * Create mock webhook event data
     */
    public static function createMockWebhookEvent(string $type, array $data): array
    {
        return [
            'id' => 'evt_' . uniqid(),
            'object' => 'event',
            'type' => $type,
            'data' => [
                'object' => $data
            ],
            'created' => time(),
            'livemode' => false,
            'pending_webhooks' => 1,
            'request' => [
                'id' => 'req_' . uniqid(),
                'idempotency_key' => null
            ]
        ];
    }

    /**
     * Test card numbers for different scenarios
     */
    public static function getTestCards(): array
    {
        return [
            'success' => '4242424242424242',
            'declined' => '4000000000000002',
            'insufficient_funds' => '4000000000009995',
            'expired_card' => '4000000000000069',
            'incorrect_cvc' => '4000000000000127',
            'processing_error' => '4000000000000119',
            'authentication_required' => '4000002500003155'
        ];
    }

    /**
     * Verify test environment setup
     */
    public static function verifyTestEnvironment(): array
    {
        $results = [
            'stripe_installation' => false,
            'payment_service' => false,
            'configuration' => false,
            'database' => false
        ];

        try {
            static::verifyStripeInstallation();
            $results['stripe_installation'] = true;
        } catch (\Exception $e) {
            $results['stripe_installation'] = $e->getMessage();
        }

        try {
            static::verifyPaymentServiceInstantiation();
            $results['payment_service'] = true;
        } catch (\Exception $e) {
            $results['payment_service'] = $e->getMessage();
        }

        try {
            static::verifyStripeConfiguration();
            $results['configuration'] = true;
        } catch (\Exception $e) {
            $results['configuration'] = $e->getMessage();
        }

        try {
            // Test database connection
            \DB::connection()->getPdo();
            $results['database'] = true;
        } catch (\Exception $e) {
            $results['database'] = $e->getMessage();
        }

        return $results;
    }
}