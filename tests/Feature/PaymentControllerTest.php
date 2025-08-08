<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SongRequest;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class PaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up Stripe test configuration
        config(['services.stripe.secret' => 'sk_test_fake']);
        config(['services.stripe.webhook_secret' => 'whsec_test_fake']);
    }

    public function test_payment_show_page_loads_for_owner()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('payments.show', $songRequest));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
        $response->assertViewHas('songRequest', $songRequest);
    }

    public function test_payment_show_page_forbidden_for_non_owner()
    {
        // Arrange
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $owner->id]);

        // Act
        $response = $this->actingAs($otherUser)->get(route('payments.show', $songRequest));

        // Assert
        $response->assertStatus(403);
    }

    public function test_create_payment_intent_success()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('createPaymentIntent')
            ->once()
            ->with($songRequest)
            ->andReturn([
                'success' => true,
                'client_secret' => 'pi_test_123_secret_test',
                'payment_intent_id' => 'pi_test_123'
            ]);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act
        $response = $this->actingAs($user)
            ->postJson(route('payments.create-intent'), [
                'song_request_id' => $songRequest->id
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'client_secret' => 'pi_test_123_secret_test',
            'payment_intent_id' => 'pi_test_123'
        ]);
    }

    public function test_create_payment_intent_already_paid()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'succeeded'
        ]);

        // Act
        $response = $this->actingAs($user)
            ->postJson(route('payments.create-intent'), [
                'song_request_id' => $songRequest->id
            ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'error' => 'Payment already completed for this request.'
        ]);
    }

    public function test_create_payment_intent_unauthorized()
    {
        // Arrange
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $owner->id]);

        // Act
        $response = $this->actingAs($otherUser)
            ->postJson(route('payments.create-intent'), [
                'song_request_id' => $songRequest->id
            ]);

        // Assert
        $response->assertStatus(403);
    }

    public function test_test_payment_success_in_non_production()
    {
        // Arrange
        $this->app['env'] = 'local'; // Ensure we're in non-production
        
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('createTestPayment')
            ->once()
            ->with($songRequest)
            ->andReturn([
                'success' => true,
                'test_mode' => true,
                'message' => 'Test payment completed successfully'
            ]);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act
        $response = $this->actingAs($user)
            ->postJson(route('payments.test', $songRequest));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'test_mode' => true,
            'message' => 'Test payment completed successfully'
        ]);
    }

    public function test_test_payment_forbidden_in_production()
    {
        // Arrange
        $this->app['env'] = 'production';
        
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)
            ->postJson(route('payments.test', $songRequest));

        // Assert
        $response->assertStatus(404);
    }

    public function test_payment_success_redirect()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_123',
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('handleCheckoutSuccess')
            ->once()
            ->with('cs_test_123')
            ->andReturn(true);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act
        $response = $this->actingAs($user)
            ->get(route('payment.success', ['song_request' => $songRequest->id, 'session_id' => 'cs_test_123']));

        // Assert
        $response->assertRedirect(route('song-requests.show', $songRequest));
        $response->assertSessionHas('success', 'Payment completed successfully! Your song is now in production.');
    }

    public function test_payment_success_redirect_missing_session_id()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)
            ->get(route('payment.success', ['song_request' => $songRequest->id]));

        // Assert
        $response->assertRedirect(route('payments.show', $songRequest));
        $response->assertSessionHas('error', 'Invalid payment session.');
    }

    public function test_payment_success_redirect_verification_failed()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_123'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('handleCheckoutSuccess')
            ->once()
            ->with('cs_test_123')
            ->andReturn(false);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act
        $response = $this->actingAs($user)
            ->get(route('payment.success', ['song_request' => $songRequest->id, 'session_id' => 'cs_test_123']));

        // Assert
        $response->assertRedirect(route('payments.show', $songRequest));
        $response->assertSessionHas('error', 'Payment verification failed. Please contact support.');
    }

    public function test_payment_cancel_redirect()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)
            ->get(route('payment.cancel', ['song_request' => $songRequest->id]));

        // Assert
        $response->assertRedirect(route('payments.show', $songRequest));
        $response->assertSessionHas('info', 'Payment was cancelled. You can try again anytime.');
    }

    public function test_webhook_checkout_session_completed()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_123',
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('handleCheckoutSuccess')
            ->once()
            ->with('cs_test_123')
            ->andReturn(true);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        $webhookPayload = json_encode([
            'id' => 'evt_test_123',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'payment_status' => 'paid'
                ]
            ]
        ]);

        // Mock the Stripe webhook verification
        $this->mock(\Stripe\Webhook::class, function ($mock) use ($webhookPayload) {
            $mock->shouldReceive('constructEvent')
                ->once()
                ->andReturn(json_decode($webhookPayload, true));
        });

        // Act
        $response = $this->postJson(route('stripe.webhook'), [], [
            'Stripe-Signature' => 'test_signature'
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_webhook_payment_intent_succeeded()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_intent_id' => 'pi_test_123',
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('handleSuccessfulPayment')
            ->once()
            ->with('pi_test_123')
            ->andReturn(true);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        $webhookPayload = json_encode([
            'id' => 'evt_test_123',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'status' => 'succeeded'
                ]
            ]
        ]);

        // Mock the Stripe webhook verification
        $this->mock(\Stripe\Webhook::class, function ($mock) use ($webhookPayload) {
            $mock->shouldReceive('constructEvent')
                ->once()
                ->andReturn(json_decode($webhookPayload, true));
        });

        // Act
        $response = $this->postJson(route('stripe.webhook'), [], [
            'Stripe-Signature' => 'test_signature'
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_webhook_invalid_signature()
    {
        // Mock the Stripe webhook verification to throw exception
        $this->mock(\Stripe\Webhook::class, function ($mock) {
            $mock->shouldReceive('constructEvent')
                ->once()
                ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature', 'sig_header'));
        });

        // Act
        $response = $this->postJson(route('stripe.webhook'), [], [
            'Stripe-Signature' => 'invalid_signature'
        ]);

        // Assert
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}