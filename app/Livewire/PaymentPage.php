<?php

namespace App\Livewire;

use App\Models\SongRequest;
use App\Services\PaymentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentPage extends Component
{
    public SongRequest $songRequest;
    public $paymentProcessing = false;
    public $paymentSuccess = false;
    public $paymentError = '';

    public function mount(SongRequest $songRequest)
    {
        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $this->songRequest = $songRequest;
        
        // Check if payment is already completed
        if ($this->songRequest->payment_status === 'succeeded') {
            $this->paymentSuccess = true;
        }

        // Check if Stripe is configured
        try {
            $paymentService = app(PaymentService::class);
            if (!$paymentService->isConfigured()) {
                $this->paymentError = 'Payment system is temporarily unavailable. Please contact support.';
            }
        } catch (\Exception $e) {
            $this->paymentError = 'Payment system is temporarily unavailable. Please contact support.';
        }
    }

    public function processPayment()
    {
        $this->paymentProcessing = true;
        $this->paymentError = '';

        try {
            $paymentService = app(PaymentService::class);
            
            // Check if Stripe is configured before attempting payment
            if (!$paymentService->isConfigured()) {
                $this->paymentError = 'Payment system is temporarily unavailable. Please contact support.';
                $this->paymentProcessing = false;
                return;
            }

            $result = $paymentService->createCheckoutSession($this->songRequest);

            if ($result['success']) {
                // Redirect to Stripe Checkout
                return redirect()->away($result['checkout_url']);
            } else {
                $this->paymentError = $result['error'] ?? 'Failed to create checkout session';
            }
        } catch (\Exception $e) {
            // Log the actual error for developers
            \Illuminate\Support\Facades\Log::error('Payment processing error', [
                'song_request_id' => $this->songRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Show user-friendly error
            $this->paymentError = 'Payment system is temporarily unavailable. Please contact support.';
        }

        $this->paymentProcessing = false;
    }

    public function processTestPayment($type = 'success')
    {
        // Only allow test payments in development
        if (app()->isProduction()) {
            $this->paymentError = 'Test payments are not allowed in production.';
            return;
        }

        $this->paymentProcessing = true;
        $this->paymentError = '';

        try {
            $paymentService = app(PaymentService::class);
            
            if ($type === 'fail') {
                $result = $paymentService->createFailedTestPayment($this->songRequest);
            } else {
                $result = $paymentService->createTestPayment($this->songRequest);
            }

            if ($result['success']) {
                $this->paymentSuccess = true;
                $this->songRequest->refresh(); // Refresh the model
                
                // Dispatch browser event for success animation
                $this->dispatch('payment-success');
            } else {
                $this->paymentError = $result['error'] ?? 'Payment failed';
                // Don't refresh the model for failed payments to preserve the UI state
            }
        } catch (\Exception $e) {
            $this->paymentError = 'Payment processing error: ' . $e->getMessage();
        }

        $this->paymentProcessing = false;
    }

    public function refreshPaymentStatus()
    {
        $this->songRequest->refresh();
        
        if ($this->songRequest->payment_status === 'succeeded') {
            $this->paymentSuccess = true;
            $this->dispatch('payment-success');
        }
    }

    public function render()
    {
        return view('livewire.payment-page');
    }
}