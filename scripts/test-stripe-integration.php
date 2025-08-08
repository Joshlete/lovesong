<?php
/**
 * Simple script to test Stripe integration in CI/CD environment
 * This script can be run independently to verify Stripe setup
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing Stripe Integration...\n\n";

// Test 1: Verify Stripe classes exist
echo "1. Checking Stripe PHP SDK installation...\n";
try {
    if (!class_exists('Stripe\StripeClient')) {
        throw new Exception('Stripe\StripeClient not found');
    }
    if (!class_exists('Stripe\Checkout\Session')) {
        throw new Exception('Stripe\Checkout\Session not found');
    }
    if (!class_exists('Stripe\PaymentIntent')) {
        throw new Exception('Stripe\PaymentIntent not found');
    }
    echo "   ✅ Stripe PHP SDK is properly installed\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Verify configuration
echo "\n2. Checking Stripe configuration...\n";
try {
    $stripeKey = config('services.stripe.key');
    $stripeSecret = config('services.stripe.secret');
    $webhookSecret = config('services.stripe.webhook_secret');

    if (empty($stripeKey) || !str_starts_with($stripeKey, 'pk_')) {
        throw new Exception('Invalid or missing Stripe publishable key');
    }
    if (empty($stripeSecret) || !str_starts_with($stripeSecret, 'sk_')) {
        throw new Exception('Invalid or missing Stripe secret key');
    }
    if (empty($webhookSecret) || !str_starts_with($webhookSecret, 'whsec_')) {
        throw new Exception('Invalid or missing Stripe webhook secret');
    }
    
    echo "   ✅ Stripe configuration is valid\n";
    echo "   📝 Using key: " . substr($stripeKey, 0, 12) . "...\n";
    echo "   📝 Environment: " . (str_contains($stripeKey, '_test_') ? 'TEST' : 'LIVE') . "\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Test PaymentService instantiation
echo "\n3. Testing PaymentService instantiation...\n";
try {
    $paymentService = app(App\Services\PaymentService::class);
    echo "   ✅ PaymentService instantiated successfully\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Test database connection
echo "\n4. Testing database connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✅ Database connection successful\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 5: Test model factories
echo "\n5. Testing model factories...\n";
try {
    $user = App\Models\User::factory()->make();
    $songRequest = App\Models\SongRequest::factory()->make();
    echo "   ✅ Model factories working correctly\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 6: Verify routes exist
echo "\n6. Checking payment routes...\n";
try {
    $routes = [
        'payments.show',
        'payment.success', 
        'payment.cancel',
        'stripe.webhook'
    ];
    
    foreach ($routes as $routeName) {
        if (!Route::has($routeName)) {
            throw new Exception("Route '{$routeName}' not found");
        }
    }
    echo "   ✅ All payment routes are registered\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🎉 All Stripe integration tests passed!\n";
echo "✨ Stripe is properly installed and configured for CI/CD\n";

// Summary
echo "\n📊 Integration Summary:\n";
echo "   • Stripe PHP SDK: Installed ✅\n";
echo "   • Configuration: Valid ✅\n";
echo "   • PaymentService: Working ✅\n";
echo "   • Database: Connected ✅\n";
echo "   • Model Factories: Working ✅\n";
echo "   • Routes: Registered ✅\n";
echo "\n🚀 Ready for deployment!\n";