<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Google OAuth 2.0 Web Redirect & Callback
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleWebCallback']);

