<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PasswordResetController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [AuthController::class, 'index'])->middleware('is.admin');
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('email/verify', [AuthController::class, 'resendVerificationEmail'])->name('verification.notice');
    Route::post('email/verify/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');


    Route::controller(TicketController::class)->group(function () {
        Route::post('tickets', 'store');
        Route::get('tickets', 'getAll');
        Route::get('tickets/{id}', 'show');
        Route::put('tickets/{id}', 'update');
        Route::delete('tickets/{id}', 'destroy');

        Route::post('tickets/{code}/replies', 'storeReply');
        Route::get('tickets/{code}/replies', 'getReplies');
    });

    // Route::controller(TicketController::class)->group(function () {
    //     Route::post('tickets/{code}/replies', 'storeReply');
    //     Route::get('tickets/{code}/replies', 'getReplies');
    // });
});
