<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Helpers\StripeTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test to verify Stripe is properly installed and configured in CI/CD
 */
class StripeInstallationTest extends TestCase
{
    public function test_stripe_php_sdk_is_installed()
    {
        $this->assertTrue(
            StripeTestHelper::verifyStripeInstallation(),
            'Stripe PHP SDK should be properly installed'
        );
    }

    public function test_stripe_configuration_is_valid()
    {
        $this->assertTrue(
            StripeTestHelper::verifyStripeConfiguration(),
            'Stripe configuration should be valid'
        );
    }

    public function test_payment_service_can_be_instantiated()
    {
        $this->assertTrue(
            StripeTestHelper::verifyPaymentServiceInstantiation(),
            'PaymentService should be instantiable'
        );
    }

    public function test_complete_environment_verification()
    {
        $results = StripeTestHelper::verifyTestEnvironment();

        foreach ($results as $component => $result) {
            $this->assertTrue(
                $result === true,
                "Environment verification failed for {$component}: " . (is_string($result) ? $result : 'Unknown error')
            );
        }
    }

    public function test_stripe_test_cards_are_available()
    {
        $testCards = StripeTestHelper::getTestCards();

        $this->assertArrayHasKey('success', $testCards);
        $this->assertArrayHasKey('declined', $testCards);
        $this->assertArrayHasKey('insufficient_funds', $testCards);
        
        $this->assertEquals('4242424242424242', $testCards['success']);
        $this->assertEquals('4000000000000002', $testCards['declined']);
    }

    public function test_webhook_signature_generation()
    {
        $payload = '{"id":"evt_test","type":"test.event"}';
        $signature = StripeTestHelper::generateWebhookSignature($payload);

        $this->assertStringContainsString('t=', $signature);
        $this->assertStringContainsString('v1=', $signature);
    }

    public function test_mock_data_creation()
    {
        $session = StripeTestHelper::createMockCheckoutSession();
        $paymentIntent = StripeTestHelper::createMockPaymentIntent();
        $webhookEvent = StripeTestHelper::createMockWebhookEvent('test.event', ['id' => 'test']);

        $this->assertEquals('cs_test_123', $session['id']);
        $this->assertEquals('pi_test_456', $paymentIntent['id']);
        $this->assertEquals('test.event', $webhookEvent['type']);
    }
}