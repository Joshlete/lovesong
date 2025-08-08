# Stripe Integration Testing Guide

This document outlines the comprehensive testing strategy for Stripe payment integration in the LoveSong application.

## Overview

The test suite ensures that:
- ✅ Stripe PHP SDK is properly installed
- ✅ Payment processing works correctly
- ✅ Webhook handling is robust
- ✅ CI/CD integration is reliable

## Test Structure

### 1. Unit Tests (`tests/Unit/PaymentServiceTest.php`)

Tests the core PaymentService functionality:

- **Checkout Session Creation**: Tests successful session creation and error handling
- **Payment Success Handling**: Verifies proper processing of completed payments
- **Test Payment Methods**: Validates both success and failure test scenarios
- **Amount Conversion**: Ensures correct USD to Stripe cents conversion
- **Description Building**: Tests dynamic product description generation

```bash
php artisan test tests/Unit/PaymentServiceTest.php
```

### 2. Feature Tests (`tests/Feature/PaymentControllerTest.php`)

Tests HTTP endpoints and controller logic:

- **Payment Page Access**: Authorization and route handling
- **Payment Intent Creation**: API endpoint functionality
- **Success/Cancel Redirects**: Post-payment flow handling
- **Test Payment Endpoints**: Development-only functionality
- **Webhook Processing**: Event handling and verification

```bash
php artisan test tests/Feature/PaymentControllerTest.php
```

### 3. Livewire Tests (`tests/Feature/PaymentPageLivewireTest.php`)

Tests the interactive payment interface:

- **Component Mounting**: Authorization and state initialization
- **Payment Processing**: Stripe checkout integration
- **Test Payments**: Success and failure simulation
- **Environment Handling**: Production vs development behavior
- **UI State Management**: Form visibility and error display

```bash
php artisan test tests/Feature/PaymentPageLivewireTest.php
```

### 4. Webhook Integration Tests (`tests/Feature/StripeWebhookIntegrationTest.php`)

Tests webhook event processing:

- **Checkout Session Events**: Payment completion handling
- **Payment Intent Events**: Success and failure processing
- **Security**: Signature verification and invalid requests
- **Error Handling**: Missing records and API failures
- **Logging**: Proper error and info logging

```bash
php artisan test tests/Feature/StripeWebhookIntegrationTest.php
```

### 5. Installation Tests (`tests/Feature/StripeInstallationTest.php`)

Verifies environment setup:

- **SDK Installation**: Class availability verification
- **Configuration**: Environment variable validation
- **Service Instantiation**: Dependency injection verification
- **Test Data**: Mock data and helper functionality

```bash
php artisan test tests/Feature/StripeInstallationTest.php
```

## CI/CD Integration

### GitHub Actions Workflow (`.github/workflows/stripe-tests.yml`)

Automated testing pipeline that:
- Sets up MySQL database
- Configures Stripe test environment
- Runs comprehensive test suite
- Generates coverage reports
- Verifies installation integrity

### Integration Verification Script (`scripts/test-stripe-integration.php`)

Standalone verification script for:
- SDK installation verification
- Configuration validation
- Service instantiation testing
- Database connectivity
- Route registration

```bash
php scripts/test-stripe-integration.php
```

## Test Environment Configuration

### PHPUnit Configuration (`phpunit.xml`)

Test-specific environment variables:
```xml
<env name="STRIPE_KEY" value="pk_test_51234567890abcdef"/>
<env name="STRIPE_SECRET" value="sk_test_51234567890abcdef"/>
<env name="STRIPE_WEBHOOK_SECRET" value="whsec_test_webhook_secret"/>
```

### Test Helper (`tests/Helpers/StripeTestHelper.php`)

Utility functions for:
- Environment verification
- Mock data generation
- Webhook signature creation
- Test card numbers
- Installation validation

## Running Tests

### All Stripe Tests
```bash
php artisan test --filter=Stripe
```

### Specific Test Categories
```bash
# Unit tests only
php artisan test tests/Unit/PaymentServiceTest.php

# Feature tests only
php artisan test tests/Feature/PaymentControllerTest.php

# Installation verification
php artisan test tests/Feature/StripeInstallationTest.php
```

### With Coverage
```bash
php artisan test --coverage --filter=Payment
```

## Test Data

### Test Card Numbers

The tests use official Stripe test cards:

- **Success**: `4242 4242 4242 4242`
- **Declined**: `4000 0000 0000 0002`
- **Insufficient Funds**: `4000 0000 0000 9995`
- **Authentication Required**: `4000 0025 0000 3155`

### Mock Data

Tests use factories and mocks for:
- User accounts
- Song requests
- Stripe API responses
- Webhook events

## Environment Requirements

### Development
- PHP 8.2+
- Laravel 11.x
- Stripe PHP SDK
- MySQL/SQLite database
- Valid Stripe test keys

### CI/CD
- All development requirements
- GitHub Actions runner
- MySQL service container
- Test environment configuration

## Troubleshooting

### Common Issues

1. **Database Transaction Errors**
   - Solution: Use `DatabaseTransactions` instead of `RefreshDatabase`

2. **Stripe Exception Mocking**
   - Solution: Use concrete exception classes like `CardException`

3. **Configuration Missing**
   - Solution: Verify environment variables in `phpunit.xml`

4. **Route Not Found**
   - Solution: Ensure all payment routes are registered

### Debug Commands

```bash
# Verify Stripe configuration
php artisan tinker --execute="dd(config('services.stripe'))"

# Test PaymentService instantiation
php artisan tinker --execute="dd(app(App\Services\PaymentService::class))"

# List payment routes
php artisan route:list --name=payment
```

## Security Considerations

- All test keys are safe to expose in CI
- Production keys must never be committed
- Webhook signatures are properly verified
- Test environment isolation is maintained

## Performance

- Tests use database transactions for speed
- Mocking reduces external API calls
- Parallel test execution supported
- Coverage reporting included

## Monitoring

The test suite provides:
- ✅ Installation verification
- ✅ Configuration validation
- ✅ Integration testing
- ✅ Error handling coverage
- ✅ CI/CD pipeline validation

This ensures reliable Stripe integration across all environments.