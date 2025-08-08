<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SongRequest;
use App\Services\PaymentService;
use App\Livewire\PaymentPage;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class PaymentPageLivewireTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test environment
        $this->app['env'] = 'local';
    }

    public function test_payment_page_mounts_correctly_for_pending_payment()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending',
            'recipient_name' => 'John Doe',
            'price_usd' => 25.00
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSet('songRequest.id', $songRequest->id)
            ->assertSet('paymentSuccess', false)
            ->assertSet('paymentError', '')
            ->assertSee('John Doe')
            ->assertSee('$25.00');
    }

    public function test_payment_page_mounts_with_success_for_completed_payment()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'succeeded'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSet('paymentSuccess', true);
    }

    public function test_payment_page_forbidden_for_non_owner()
    {
        // Arrange
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $owner->id]);

        // Act & Assert
        $this->actingAs($otherUser);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest]);
    }

    public function test_process_payment_creates_checkout_session()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('createCheckoutSession')
            ->once()
            ->with($songRequest)
            ->andReturn([
                'success' => true,
                'checkout_url' => 'https://checkout.stripe.com/c/pay/cs_test_123'
            ]);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act & Assert
        $this->actingAs($user);
        $component = Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->call('processPayment');

        // Note: We can't easily test the redirect()->away() in Livewire tests
        // But we can verify that no error was set
        $component->assertSet('paymentError', '');
    }

    public function test_process_payment_handles_stripe_error()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('createCheckoutSession')
            ->once()
            ->with($songRequest)
            ->andReturn([
                'success' => false,
                'error' => 'Card declined'
            ]);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->call('processPayment')
            ->assertSet('paymentError', 'Card declined')
            ->assertSet('paymentProcessing', false);
    }

    public function test_process_test_payment_success()
    {
        // Arrange
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

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->call('processTestPayment', 'success')
            ->assertSet('paymentSuccess', true)
            ->assertSet('paymentError', '')
            ->assertSet('paymentProcessing', false)
            ->assertDispatched('payment-success');
    }

    public function test_process_test_payment_failure()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $paymentServiceMock = Mockery::mock(PaymentService::class);
        $paymentServiceMock->shouldReceive('createFailedTestPayment')
            ->once()
            ->with($songRequest)
            ->andReturn([
                'success' => false,
                'test_mode' => true,
                'error' => 'Test payment failed: Your card was declined.'
            ]);

        $this->app->instance(PaymentService::class, $paymentServiceMock);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->call('processTestPayment', 'fail')
            ->assertSet('paymentSuccess', false)
            ->assertSet('paymentError', 'Test payment failed: Your card was declined.')
            ->assertSet('paymentProcessing', false);
    }

    public function test_process_test_payment_blocked_in_production()
    {
        // Arrange
        $this->app['env'] = 'production';
        
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->call('processTestPayment')
            ->assertSet('paymentError', 'Test payments are not allowed in production.')
            ->assertSet('paymentProcessing', false);
    }

    public function test_refresh_payment_status_with_succeeded_payment()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        $this->actingAs($user);
        $component = Livewire::test(PaymentPage::class, ['songRequest' => $songRequest]);

        // Update the database to simulate external payment completion
        $songRequest->update(['payment_status' => 'succeeded']);

        // Act & Assert
        $component->call('refreshPaymentStatus')
            ->assertSet('paymentSuccess', true)
            ->assertDispatched('payment-success');
    }

    public function test_payment_page_shows_debug_info()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSee('Debug:')
            ->assertSee('Payment Status: pending')
            ->assertSee('Payment Success: false');
    }

    public function test_payment_page_shows_test_buttons_in_local_environment()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSee('✅ Test Successful Payment')
            ->assertSee('❌ Test Failed Payment')
            ->assertSee('Development Mode: These buttons simulate payment outcomes')
            ->assertSee('For real Stripe testing, use these cards:')
            ->assertSee('4242 4242 4242 4242');
    }

    public function test_payment_page_hides_test_buttons_in_production()
    {
        // Arrange
        $this->app['env'] = 'production';
        
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertDontSee('✅ Test Successful Payment')
            ->assertDontSee('❌ Test Failed Payment')
            ->assertDontSee('Development Mode');
    }

    public function test_payment_page_shows_order_summary()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'pending',
            'recipient_name' => 'Jane Smith',
            'style' => 'jazz',
            'mood' => 'romantic',
            'price_usd' => 35.00
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSee('Order Summary')
            ->assertSee('Custom Song for')
            ->assertSee('Jane Smith')
            ->assertSee('Style')
            ->assertSee('Jazz')
            ->assertSee('Mood')
            ->assertSee('Romantic')
            ->assertSee('Total')
            ->assertSee('$35.00');
    }

    public function test_payment_page_shows_success_state_when_payment_completed()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'succeeded'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSee('Payment Successful!')
            ->assertSee('Your custom song is now in production.')
            ->assertSee("What's next?")
            ->assertSee('Review within 24 hours')
            ->assertSee('Composition & recording')
            ->assertSee('Delivery in 7-14 days')
            ->assertSee('Create Another Song');
    }

    public function test_payment_page_shows_already_paid_state()
    {
        // Arrange
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'completed' // Different from 'pending'
        ]);

        // Act & Assert
        $this->actingAs($user);
        Livewire::test(PaymentPage::class, ['songRequest' => $songRequest])
            ->assertSee('Payment Complete')
            ->assertSee('Your song is in production.');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}