<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent'])
    ->middleware('auth');

// Stripe webhook (no auth or CSRF required)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');
