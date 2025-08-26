<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk otentikasi
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rute untuk reset kata sandi
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);

// Rute yang membutuhkan otentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Rute dasar yang memerlukan otentikasi
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [AuthController::class, 'index'])->middleware('is.admin');
    Route::post('logout', [AuthController::class, 'logout']);

    // Rute untuk verifikasi email
    Route::get('email/verify', [AuthController::class, 'resendVerificationEmail'])->name('verification.notice');
    Route::post('email/verify/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
});
