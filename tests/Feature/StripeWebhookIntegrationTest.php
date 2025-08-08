<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SongRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;

class StripeWebhookIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test webhook secret
        config(['services.stripe.webhook_secret' => 'whsec_test_secret']);
        
        // Mock Stripe webhook verification
        $this->mockStripeWebhookVerification();
    }

    public function test_checkout_session_completed_webhook_updates_song_request()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_123',
            'payment_status' => 'pending',
            'status' => 'pending'
        ]);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'payment_intent' => 'pi_test_456',
                    'payment_status' => 'paid',
                    'customer_email' => $user->email
                ]
            ]
        ];

        // Mock Stripe API calls
        $this->mockStripeCheckoutSessionRetrieve('cs_test_123', 'pi_test_456');
        $this->mockStripePaymentIntentRetrieve('pi_test_456');

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $songRequest->refresh();
        $this->assertEquals('pi_test_456', $songRequest->payment_intent_id);
        $this->assertEquals('succeeded', $songRequest->payment_status);
        $this->assertEquals('in_progress', $songRequest->status);
        $this->assertNotNull($songRequest->payment_completed_at);
    }

    public function test_payment_intent_succeeded_webhook_updates_song_request()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_intent_id' => 'pi_test_123',
            'payment_status' => 'pending',
            'status' => 'pending'
        ]);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'status' => 'succeeded',
                    'amount' => 2500,
                    'currency' => 'usd'
                ]
            ]
        ];

        // Mock Stripe API call
        $this->mockStripePaymentIntentRetrieve('pi_test_123');

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $songRequest->refresh();
        $this->assertEquals('succeeded', $songRequest->payment_status);
        $this->assertEquals('in_progress', $songRequest->status);
        $this->assertNotNull($songRequest->payment_completed_at);
    }

    public function test_payment_intent_failed_webhook_updates_song_request()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_intent_id' => 'pi_test_failed',
            'payment_status' => 'pending'
        ]);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'pi_test_failed',
                    'status' => 'failed',
                    'last_payment_error' => [
                        'message' => 'Your card was declined.'
                    ]
                ]
            ]
        ];

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $songRequest->refresh();
        $this->assertEquals('failed', $songRequest->payment_status);
        $this->assertEquals('pending', $songRequest->status); // Should remain pending for retry
    }

    public function test_webhook_with_unknown_event_type_logs_info()
    {
        // Arrange
        Log::shouldReceive('info')
            ->once()
            ->with('Received unknown Stripe webhook event', ['type' => 'unknown.event.type']);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'unknown.event.type',
            'data' => [
                'object' => [
                    'id' => 'obj_test_123'
                ]
            ]
        ];

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_webhook_with_invalid_signature_returns_error()
    {
        // Arrange
        $this->mockStripeWebhookVerificationFailure();

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'checkout.session.completed',
            'data' => []
        ];

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => 'invalid_signature'
        ]);

        // Assert
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }

    public function test_webhook_checkout_session_with_nonexistent_song_request()
    {
        // Arrange
        Log::shouldReceive('error')
            ->once()
            ->with('Song request not found for checkout session', ['session_id' => 'cs_nonexistent']);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_nonexistent',
                    'payment_intent' => 'pi_test_456'
                ]
            ]
        ];

        // Mock Stripe API calls
        $this->mockStripeCheckoutSessionRetrieve('cs_nonexistent', 'pi_test_456');

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_webhook_payment_intent_with_nonexistent_song_request()
    {
        // Arrange
        Log::shouldReceive('error')
            ->once()
            ->with('Song request not found for payment intent', ['payment_intent_id' => 'pi_nonexistent']);

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_nonexistent',
                    'status' => 'succeeded'
                ]
            ]
        ];

        // Mock Stripe API call
        $this->mockStripePaymentIntentRetrieve('pi_nonexistent');

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_webhook_handles_stripe_api_errors_gracefully()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_error',
            'payment_status' => 'pending'
        ]);

        Log::shouldReceive('error')
            ->once()
            ->with('Stripe checkout success handling failed', \Mockery::any());

        $webhookPayload = [
            'id' => 'evt_test_webhook',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_error',
                    'payment_intent' => 'pi_test_error'
                ]
            ]
        ];

        // Mock Stripe API to throw an error
        $this->mockStripeCheckoutSessionRetrieveError('cs_test_error');

        // Act
        $response = $this->postJson(route('stripe.webhook'), $webhookPayload, [
            'Stripe-Signature' => $this->generateTestSignature(json_encode($webhookPayload))
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    // Helper methods for mocking Stripe API calls

    private function mockStripeWebhookVerification()
    {
        $this->mock(\Stripe\Webhook::class, function ($mock) {
            $mock->shouldReceive('constructEvent')
                ->andReturnUsing(function ($payload, $signature, $secret) {
                    return json_decode($payload, true);
                });
        });
    }

    private function mockStripeWebhookVerificationFailure()
    {
        $this->mock(\Stripe\Webhook::class, function ($mock) {
            $mock->shouldReceive('constructEvent')
                ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature', 'sig_header'));
        });
    }

    private function mockStripeCheckoutSessionRetrieve($sessionId, $paymentIntentId)
    {
        $sessionMock = \Mockery::mock();
        $sessionMock->payment_intent = $paymentIntentId;

        $checkoutMock = \Mockery::mock();
        $checkoutMock->sessions = \Mockery::mock();
        $checkoutMock->sessions->shouldReceive('retrieve')
            ->with($sessionId)
            ->andReturn($sessionMock);

        $stripeClientMock = \Mockery::mock(\Stripe\StripeClient::class);
        $stripeClientMock->checkout = $checkoutMock;
        
        $this->app->instance(\Stripe\StripeClient::class, $stripeClientMock);
    }

    private function mockStripeCheckoutSessionRetrieveError($sessionId)
    {
        $checkoutMock = \Mockery::mock();
        $checkoutMock->sessions = \Mockery::mock();
        $checkoutMock->sessions->shouldReceive('retrieve')
            ->with($sessionId)
            ->andThrow(new \Stripe\Exception\ApiErrorException('Session not found'));

        $stripeClientMock = \Mockery::mock(\Stripe\StripeClient::class);
        $stripeClientMock->checkout = $checkoutMock;
        
        $this->app->instance(\Stripe\StripeClient::class, $stripeClientMock);
    }

    private function mockStripePaymentIntentRetrieve($paymentIntentId)
    {
        $paymentIntentMock = \Mockery::mock();
        $paymentIntentMock->id = $paymentIntentId;

        $paymentIntentsMock = \Mockery::mock();
        $paymentIntentsMock->shouldReceive('retrieve')
            ->with($paymentIntentId)
            ->andReturn($paymentIntentMock);

        if ($this->app->bound(\Stripe\StripeClient::class)) {
            $stripeClientMock = $this->app->make(\Stripe\StripeClient::class);
        } else {
            $stripeClientMock = \Mockery::mock(\Stripe\StripeClient::class);
            $this->app->instance(\Stripe\StripeClient::class, $stripeClientMock);
        }
        
        $stripeClientMock->paymentIntents = $paymentIntentsMock;
    }

    private function generateTestSignature($payload)
    {
        // In a real test environment, you would generate a proper HMAC signature
        // For this test, we're mocking the verification, so any signature will work
        return 'test_signature_' . hash('sha256', $payload);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}