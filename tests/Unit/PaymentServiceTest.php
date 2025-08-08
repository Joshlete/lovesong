<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\SongRequest;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class PaymentServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PaymentService $paymentService;
    protected $stripeClientMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the Stripe client
        $this->stripeClientMock = Mockery::mock(StripeClient::class);
        $this->app->instance(StripeClient::class, $this->stripeClientMock);
        
        $this->paymentService = new PaymentService();
        
        // Use reflection to inject the mock
        $reflection = new \ReflectionClass($this->paymentService);
        $stripeProperty = $reflection->getProperty('stripe');
        $stripeProperty->setAccessible(true);
        $stripeProperty->setValue($this->paymentService, $this->stripeClientMock);
    }

    public function test_create_checkout_session_success()
    {
        // Arrange
        $user = User::factory()->create(['email' => 'test@example.com']);
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'John Doe',
            'price_usd' => 25.00,
            'currency' => 'USD',
            'style' => 'rock',
            'mood' => 'happy'
        ]);

        $sessionMock = Mockery::mock();
        $sessionMock->id = 'cs_test_123';
        $sessionMock->url = 'https://checkout.stripe.com/c/pay/cs_test_123';

        $checkoutMock = Mockery::mock();
        $checkoutMock->sessions = Mockery::mock();
        $checkoutMock->sessions->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($args) use ($songRequest) {
                return $args['payment_method_types'] === ['card'] &&
                       $args['line_items'][0]['price_data']['currency'] === 'usd' &&
                       $args['line_items'][0]['price_data']['unit_amount'] === 2500 &&
                       $args['metadata']['song_request_id'] == $songRequest->id &&
                       $args['customer_email'] === 'test@example.com';
            }))
            ->andReturn($sessionMock);

        $this->stripeClientMock->checkout = $checkoutMock;

        // Act
        $result = $this->paymentService->createCheckoutSession($songRequest);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('https://checkout.stripe.com/c/pay/cs_test_123', $result['checkout_url']);
        $this->assertEquals('cs_test_123', $result['session_id']);
        
        $songRequest->refresh();
        $this->assertEquals('cs_test_123', $songRequest->stripe_checkout_session_id);
        $this->assertEquals('pending', $songRequest->payment_status);
    }

    public function test_create_checkout_session_stripe_error()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        $checkoutMock = Mockery::mock();
        $checkoutMock->sessions = Mockery::mock();
        $checkoutMock->sessions->shouldReceive('create')
            ->once()
            ->andThrow(new \Stripe\Exception\CardException('Card declined', 'card_declined'));

        $this->stripeClientMock->checkout = $checkoutMock;

        // Act
        $result = $this->paymentService->createCheckoutSession($songRequest);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('Card declined', $result['error']);
    }

    public function test_handle_checkout_success()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'stripe_checkout_session_id' => 'cs_test_123',
            'payment_status' => 'pending'
        ]);

        $sessionMock = Mockery::mock();
        $sessionMock->payment_intent = 'pi_test_456';

        $paymentIntentMock = Mockery::mock();
        $paymentIntentMock->id = 'pi_test_456';

        $checkoutMock = Mockery::mock();
        $checkoutMock->sessions = Mockery::mock();
        $checkoutMock->sessions->shouldReceive('retrieve')
            ->once()
            ->with('cs_test_123')
            ->andReturn($sessionMock);

        $paymentIntentsMock = Mockery::mock();
        $paymentIntentsMock->shouldReceive('retrieve')
            ->once()
            ->with('pi_test_456')
            ->andReturn($paymentIntentMock);

        $this->stripeClientMock->checkout = $checkoutMock;
        $this->stripeClientMock->paymentIntents = $paymentIntentsMock;

        // Act
        $result = $this->paymentService->handleCheckoutSuccess('cs_test_123');

        // Assert
        $this->assertTrue($result);
        
        $songRequest->refresh();
        $this->assertEquals('pi_test_456', $songRequest->payment_intent_id);
        $this->assertEquals('succeeded', $songRequest->payment_status);
        $this->assertEquals('in_progress', $songRequest->status);
        $this->assertNotNull($songRequest->payment_completed_at);
    }

    public function test_handle_checkout_success_song_request_not_found()
    {
        // Arrange
        $sessionMock = Mockery::mock();
        $sessionMock->payment_intent = 'pi_test_456';

        $checkoutMock = Mockery::mock();
        $checkoutMock->sessions = Mockery::mock();
        $checkoutMock->sessions->shouldReceive('retrieve')
            ->once()
            ->with('cs_nonexistent')
            ->andReturn($sessionMock);

        $this->stripeClientMock->checkout = $checkoutMock;

        // Act
        $result = $this->paymentService->handleCheckoutSuccess('cs_nonexistent');

        // Assert
        $this->assertFalse($result);
    }

    public function test_create_test_payment_success()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act
        $result = $this->paymentService->createTestPayment($songRequest);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertTrue($result['test_mode']);
        $this->assertEquals('Test payment completed successfully', $result['message']);
        
        $songRequest->refresh();
        $this->assertEquals('succeeded', $songRequest->payment_status);
        $this->assertEquals('in_progress', $songRequest->status);
        $this->assertNotNull($songRequest->payment_completed_at);
        $this->assertStringStartsWith('pi_test_success_', $songRequest->payment_intent_id);
    }

    public function test_create_failed_test_payment()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act
        $result = $this->paymentService->createFailedTestPayment($songRequest);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertTrue($result['test_mode']);
        $this->assertStringContains('Test payment failed', $result['error']);
        
        // Verify the database wasn't updated (as per our current implementation)
        $songRequest->refresh();
        $this->assertEquals('pending', $songRequest->payment_status);
    }

    public function test_convert_to_stripe_amount()
    {
        // Use reflection to test the private method
        $reflection = new \ReflectionClass($this->paymentService);
        $method = $reflection->getMethod('convertToStripeAmount');
        $method->setAccessible(true);

        $this->assertEquals(2500, $method->invoke($this->paymentService, 25.00));
        $this->assertEquals(999, $method->invoke($this->paymentService, 9.99));
        $this->assertEquals(100, $method->invoke($this->paymentService, 1.00));
        $this->assertEquals(1, $method->invoke($this->paymentService, 0.01));
    }

    public function test_build_song_description()
    {
        // Arrange
        $user = User::factory()->create();
        
        // Test with style and mood
        $songRequest1 = SongRequest::factory()->create([
            'user_id' => $user->id,
            'style' => 'rock',
            'mood' => 'energetic'
        ]);

        // Test with only style
        $songRequest2 = SongRequest::factory()->create([
            'user_id' => $user->id,
            'style' => 'jazz',
            'mood' => null
        ]);

        // Test with no style or mood
        $songRequest3 = SongRequest::factory()->create([
            'user_id' => $user->id,
            'style' => null,
            'mood' => null
        ]);

        // Use reflection to test the private method
        $reflection = new \ReflectionClass($this->paymentService);
        $method = $reflection->getMethod('buildSongDescription');
        $method->setAccessible(true);

        // Act & Assert
        $this->assertEquals(
            'A custom song in rock style with a energetic mood.',
            $method->invoke($this->paymentService, $songRequest1)
        );

        $this->assertEquals(
            'A custom song in jazz style.',
            $method->invoke($this->paymentService, $songRequest2)
        );

        $this->assertEquals(
            'A custom song.',
            $method->invoke($this->paymentService, $songRequest3)
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}