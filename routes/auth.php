<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:api-login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// OTP Verification (session-protected, no auth required yet)
Route::get('/admin/verify-otp', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'create'])
    ->name('admin.otp');
Route::post('/admin/verify-otp', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('admin.otp.submit');
Route::post('/admin/resend-otp', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'resend'])
    ->middleware('throttle:3,1')
    ->name('admin.otp.resend');

// Admin Forgot Password (custom named routes so we can redirect correctly)
Route::get('/admin/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('admin.password.request');
Route::post('/admin/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('admin.password.reset');
Route::post('/admin/reset-password', [NewPasswordController::class, 'store'])
    ->name('admin.password.store');


Route::middleware('auth')->group(function () {
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
