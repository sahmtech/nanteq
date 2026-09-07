<?php

use App\Http\Controllers\Api\AgeController;
use App\Http\Controllers\Api\AiModelController;
use App\Http\Controllers\Api\AudioController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SoundProgressController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SoundController;
use App\Http\Controllers\Api\LetterController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('complete-profile', [UserController::class, 'completeProfile']);

    Route::get('sounds', [SoundController::class, 'index']);

    Route::get('letters', [LetterController::class, 'index']);
    Route::get('ages', [AgeController::class, 'index']);


    Route::middleware('completed-profile')->group(function () {
        Route::post('subscribe', [SubscriptionController::class, 'subscribe']);

        Route::get('plans', [PlanController::class, 'index']);

        Route::get('sounds/{level_id}', [SoundController::class, 'index'])->whereNumber('level_id');
        Route::get('levels/{letter_id}', [LevelController::class, 'index'])->whereNumber('letter_id');

        Route::get('level-details/{level_id}', [LevelController::class, 'show'])->whereNumber('level_id');

        Route::post('assign-sound', [AudioController::class, 'assignSound']);

        Route::get('user-details', [UserController::class, 'userDetails']);
        Route::middleware('has-subscription')->group(function () {
            Route::get('letters-progresses', [LetterController::class, 'lettersProgresses']);
            Route::get('current-subscription', [SubscriptionController::class, 'currentSubscription']);            
        });
    });
});
