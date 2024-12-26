<?php

use App\Http\Controllers\Auth\Client\AuthenticatedSessionClientController;
use App\Http\Controllers\Auth\Client\RegisteredUserClientController;
use App\Http\Controllers\Auth\Vendor\AuthenticatedSessionVendorController;
use App\Http\Controllers\Auth\Vendor\RegisteredUserVendorController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('client_register', [RegisteredUserClientController::class, 'create'])
        ->name('client_register');

    Route::post('client_register', [RegisteredUserClientController::class, 'store']);

    Route::get('client_login', [AuthenticatedSessionClientController::class, 'create'])
        ->name('client_sign');

    Route::post('client_login', [AuthenticatedSessionClientController::class, 'store'])
        ->name('client_login');

    Route::get('vendor_register', [RegisteredUserVendorController::class, 'create'])
        ->name('vendor_register');

    Route::post('vendor_register', [RegisteredUserVendorController::class, 'store']);

    Route::get('vendor_login', [AuthenticatedSessionVendorController::class, 'create'])
        ->name('vendor_sign');

    Route::post('vendor_login', [AuthenticatedSessionVendorController::class, 'store'])
        ->name('vendor_login');;

//    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//        ->name('password.request');
//
//    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//        ->name('password.email');
//
//    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//        ->name('password.reset');
//
//    Route::post('reset-password', [NewPasswordController::class, 'store'])
//        ->name('password.store');
});
//
Route::middleware('auth:client')->group(function () {
//    Route::get('verify-email', EmailVerificationPromptController::class)
//        ->name('verification.notice');
//
//    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//        ->middleware(['signed', 'throttle:6,1'])
//        ->name('verification.verify');
//
//    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//        ->middleware('throttle:6,1')
//        ->name('verification.send');
//
//    Route::get('confirm-password', [ConfirmablePasswordClientController::class, 'show'])
//        ->name('password.confirm');
//
//    Route::post('confirm-password', [ConfirmablePasswordClientController::class, 'store']);
//
//    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('client_logout', [AuthenticatedSessionClientController::class, 'destroy'])
        ->name('client_logout');
});

Route::middleware('auth:vendor')->group(function () {
//    Route::get('verify-email', EmailVerificationPromptController::class)
//        ->name('verification.notice');
//
//    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//        ->middleware(['signed', 'throttle:6,1'])
//        ->name('verification.verify');
//
//    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//        ->middleware('throttle:6,1')
//        ->name('verification.send');
//
//    Route::get('confirm-password', [ConfirmablePasswordClientController::class, 'show'])
//        ->name('password.confirm');
//
//    Route::post('confirm-password', [ConfirmablePasswordClientController::class, 'store']);
//
//    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('vendor_logout', [AuthenticatedSessionVendorController::class, 'destroy'])
        ->name('vendor_logout');
});
