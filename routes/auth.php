<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\MobileVerificationNotificationController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\TokenAuthController;
use App\Http\Controllers\Api\Auth\VerifyMobileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::post('otp-request', [AuthController::class, 'otpRequest'])->name('otp-request')->middleware(['throttle:10,1']);

Route::post('login', [AuthController::class, 'login'])->name('login');
