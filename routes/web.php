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
});
