<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Redirect old auth routes to landing page with modal
Route::get('/login', function () {
    return redirect('/')->with('openModal', 'login');
})->name('login');

Route::get('/register', function () {
    return redirect('/')->with('openModal', 'register');
})->name('register');

Route::get('/ping', fn () => 'pong');

// Legal pages
Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])
    ->middleware('throttle:5,1') // 5 contact forms per minute max
    ->name('contact.store');

// Routes that don't require email verification (dashboard, song creation)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('song-requests', \App\Http\Controllers\SongRequestController::class);
    Route::get('song-requests/{song_request}/download', [\App\Http\Controllers\SongRequestController::class, 'download'])->name('song-requests.download');
});

// Routes that require email verification (payments)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Payment routes
    Route::get('song-requests/{song_request}/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');
    Route::post('song-requests/{song_request}/payment-intent', [\App\Http\Controllers\PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
    Route::post('song-requests/{song_request}/test-payment', [\App\Http\Controllers\PaymentController::class, 'testPayment'])->name('payments.test');

    // Stripe Checkout redirect routes
    Route::get('song-requests/{song_request}/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('song-requests/{song_request}/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Admin Routes (no email verification required for admins)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'admin',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('song-requests', \App\Http\Controllers\Admin\SongRequestController::class)->except(['create', 'store']);
    Route::patch('song-requests/{song_request}/status', [\App\Http\Controllers\Admin\SongRequestController::class, 'updateStatus'])->name('song-requests.update-status');
    Route::get('song-requests/{song_request}/download', [\App\Http\Controllers\Admin\SongRequestController::class, 'download'])->name('song-requests.download');
    
    Route::get('settings', function () {
        return view('admin.settings');
    })->name('settings');
});
