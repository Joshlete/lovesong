<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    /**
     * Create a payment intent for a song request
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'song_request_id' => 'required|exists:song_requests,id',
        ]);

        $songRequest = SongRequest::findOrFail($request->song_request_id);

        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if payment is already completed
        if ($songRequest->payment_status === 'succeeded') {
            return response()->json([
                'success' => false,
                'error' => 'Payment already completed for this request.',
            ]);
        }

        $result = $this->paymentService->createPaymentIntent($songRequest);

        return response()->json($result);
    }

    /**
     * Handle test payment for development
     */
    public function testPayment(Request $request, SongRequest $songRequest)
    {
        // Only allow in non-production environments
        if (app()->isProduction()) {
            abort(404);
        }

        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $result = $this->paymentService->createTestPayment($songRequest);

        return response()->json($result);
    }

    /**
     * Handle Stripe webhook events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Verify webhook signature
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );

            // Handle the event
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $session = $event['data']['object'];
                    // Convert Stripe object to array for the service method
                    $sessionArray = is_array($session) ? $session : $session->toArray();
                    $this->paymentService->handleCheckoutSuccess($session['id'], $sessionArray);
                    break;

                case 'payment_intent.succeeded':
                    $paymentIntent = $event['data']['object'];
                    $this->paymentService->handleSuccessfulPayment($paymentIntent['id']);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event['data']['object'];
                    $this->handleFailedPayment($paymentIntent['id']);
                    break;

                default:
                    Log::info('Received unknown Stripe webhook event', ['type' => $event['type']]);
            }

            return response()->json(['status' => 'success']);

        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }

    /**
     * Handle failed payment
     */
    private function handleFailedPayment(string $paymentIntentId)
    {
        $songRequest = SongRequest::where('payment_intent_id', $paymentIntentId)->first();
        
        if ($songRequest) {
            $songRequest->update([
                'payment_status' => 'failed',
            ]);

            Log::info('Payment failed for song request', [
                'song_request_id' => $songRequest->id,
                'payment_intent_id' => $paymentIntentId,
            ]);
        }
    }

    /**
     * Show payment page for a song request
     */
    public function show(SongRequest $songRequest)
    {
        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('payments.show', compact('songRequest'));
    }

    /**
     * Handle successful Stripe Checkout redirect
     */
    public function success(Request $request, SongRequest $songRequest)
    {
        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('payments.show', $songRequest)->with('error', 'Invalid payment session.');
        }

        try {
            $success = $this->paymentService->handleCheckoutSuccess($sessionId);
            
            if ($success) {
                return redirect()->route('song-requests.show', $songRequest)->with('success', 'Payment completed successfully! Your song is now in production.');
            } else {
                return redirect()->route('payments.show', $songRequest)->with('error', 'Payment verification failed. Please contact support.');
            }
        } catch (\Exception $e) {
            Log::error('Payment success handling failed', [
                'song_request_id' => $songRequest->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('payments.show', $songRequest)->with('error', 'Payment processing error. Please contact support.');
        }
    }

    /**
     * Handle cancelled Stripe Checkout redirect
     */
    public function cancel(Request $request, SongRequest $songRequest)
    {
        // Ensure user owns this song request
        if ($songRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return redirect()->route('payments.show', $songRequest)->with('info', 'Payment was cancelled. You can try again anytime.');
    }
}