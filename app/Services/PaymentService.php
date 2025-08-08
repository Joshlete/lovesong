<?php

namespace App\Services;

use App\Models\SongRequest;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for a song request
     */
    public function createPaymentIntent(SongRequest $songRequest): array
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->convertToStripeAmount($songRequest->price_usd),
                'currency' => strtolower($songRequest->currency),
                'payment_method_types' => ['card'],
                'metadata' => [
                    'song_request_id' => $songRequest->id,
                    'user_id' => $songRequest->user_id,
                    'recipient_name' => $songRequest->recipient_name,
                ],
                'description' => "Custom song for {$songRequest->recipient_name}",
            ]);

            // Update song request with payment intent ID
            $songRequest->update([
                'payment_intent_id' => $paymentIntent->id,
                'payment_status' => 'pending',
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe payment intent creation failed', [
                'song_request_id' => $songRequest->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle successful payment confirmation
     */
    public function handleSuccessfulPayment(string $paymentIntentId): bool
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            
            $songRequest = SongRequest::where('payment_intent_id', $paymentIntentId)->first();
            
            if (!$songRequest) {
                Log::error('Song request not found for payment intent', ['payment_intent_id' => $paymentIntentId]);
                return false;
            }

            $songRequest->update([
                'payment_status' => 'succeeded',
                'payment_completed_at' => now(),
                'status' => 'in_progress', // Move to in_progress after payment
            ]);

            // TODO: Send notification to admin about new paid request
            // TODO: Send confirmation email to customer

            return true;

        } catch (ApiErrorException $e) {
            Log::error('Stripe payment confirmation failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Convert USD amount to Stripe amount (cents)
     */
    private function convertToStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Get payment status from Stripe
     */
    public function getPaymentStatus(string $paymentIntentId): ?string
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            return $paymentIntent->status;
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve payment status', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create a Stripe Checkout session
     */
    public function createCheckoutSession(SongRequest $songRequest): array
    {
        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($songRequest->currency),
                        'product_data' => [
                            'name' => "Custom Song for {$songRequest->recipient_name}",
                            'description' => $this->buildSongDescription($songRequest),
                        ],
                        'unit_amount' => $this->convertToStripeAmount($songRequest->price_usd),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['song_request' => $songRequest->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', ['song_request' => $songRequest->id]),
                'metadata' => [
                    'song_request_id' => $songRequest->id,
                    'user_id' => $songRequest->user_id,
                ],
                'customer_email' => $songRequest->user->email,
                'billing_address_collection' => 'required',
            ]);

            // Update song request with checkout session ID
            $songRequest->update([
                'stripe_checkout_session_id' => $session->id,
                'payment_status' => 'pending',
            ]);

            return [
                'success' => true,
                'checkout_url' => $session->url,
                'session_id' => $session->id,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout session creation failed', [
                'song_request_id' => $songRequest->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle successful checkout session
     */
    public function handleCheckoutSuccess(string $sessionId, ?array $sessionData = null): bool
    {
        try {
            // Use provided session data from webhook if available, otherwise retrieve from Stripe
            if ($sessionData) {
                $session = (object) $sessionData;
            } else {
                $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            }
            
            $songRequest = SongRequest::where('stripe_checkout_session_id', $sessionId)->first();
            
            if (!$songRequest) {
                Log::error('Song request not found for checkout session', ['session_id' => $sessionId]);
                return false;
            }

            // Check if we have a payment intent ID in the session data
            if (empty($session->payment_intent)) {
                Log::error('No payment intent found in checkout session', ['session_id' => $sessionId]);
                return false;
            }

            // Retrieve the payment intent from Stripe
            $paymentIntent = $this->stripe->paymentIntents->retrieve($session->payment_intent);

            $songRequest->update([
                'payment_intent_id' => $paymentIntent->id,
                'payment_status' => 'succeeded',
                'payment_completed_at' => now(),
                'status' => 'in_progress', // Move to in_progress after payment
            ]);

            // TODO: Send notification to admin about new paid request
            // TODO: Send confirmation email to customer

            return true;

        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout success handling failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Build description for the song product
     */
    private function buildSongDescription(SongRequest $songRequest): string
    {
        $description = "A custom song";
        
        if ($songRequest->style) {
            $description .= " in {$songRequest->style} style";
        }
        
        if ($songRequest->mood) {
            $description .= " with a {$songRequest->mood} mood";
        }
        
        return $description . ".";
    }

    /**
     * Create test payment data for development
     */
    public function createTestPayment(SongRequest $songRequest): array
    {
        // For testing: create a fake successful payment
        $songRequest->update([
            'payment_intent_id' => 'pi_test_success_' . uniqid(),
            'payment_status' => 'succeeded',
            'payment_completed_at' => now(),
            'status' => 'in_progress',
        ]);

        return [
            'success' => true,
            'test_mode' => true,
            'message' => 'Test payment completed successfully'
        ];
    }

    /**
     * Create failed test payment data for development
     */
    public function createFailedTestPayment(SongRequest $songRequest): array
    {
        // For testing: simulate a failed payment without updating the database
        // This keeps the payment_status as 'pending' so the user can try again
        
        return [
            'success' => false,
            'test_mode' => true,
            'error' => 'Test payment failed: Your card was declined. Please try a different payment method.'
        ];
    }
}