<?php

use App\Livewire\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('register', Auth\Register::class)->name('register');
    Route::get('login', Auth\Login::class)->name('login');
    Route::get('forgot-password', Auth\ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', Auth\NewPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', Auth\VerifyEmail::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::get('email/verification-notification', Auth\VerifyEmail::class)->name('verification.send');
    Route::get('confirm-password', Auth\ConfirmablePassword::class)->name('password.confirm');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::any('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});