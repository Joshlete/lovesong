<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/ping', fn () => 'pong');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::resource('song-requests', \App\Http\Controllers\SongRequestController::class);
    Route::get('song-requests/{song_request}/download', [\App\Http\Controllers\SongRequestController::class, 'download'])->name('song-requests.download');
    
    // Payment routes
    Route::get('song-requests/{song_request}/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');
    Route::post('song-requests/{song_request}/payment-intent', [\App\Http\Controllers\PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
    Route::post('song-requests/{song_request}/test-payment', [\App\Http\Controllers\PaymentController::class, 'testPayment'])->name('payments.test');
    
    // Stripe Checkout redirect routes
    Route::get('song-requests/{song_request}/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('song-requests/{song_request}/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Admin Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('song-requests', \App\Http\Controllers\Admin\SongRequestController::class)->except(['create', 'store']);
    Route::patch('song-requests/{song_request}/status', [\App\Http\Controllers\Admin\SongRequestController::class, 'updateStatus'])->name('song-requests.update-status');
    Route::get('song-requests/{song_request}/download', [\App\Http\Controllers\Admin\SongRequestController::class, 'download'])->name('song-requests.download');
});
