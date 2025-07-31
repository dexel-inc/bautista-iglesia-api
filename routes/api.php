<?php

use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController;

Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'me'])->name('me');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('subscriptions', SubscriptionController::class);
    Route::patch('subscriptions/{subscription}/toggle', [SubscriptionController::class, 'toggle'])->name('subscriptions.toggle');
    Route::apiResource('testimonies', TestimonyController::class)->except('update');
    Route::post('testimonies/{testimony}/edit', [TestimonyController::class, 'update'])->name('testimonies.update');

    Route::apiResource('missionaries', MissionaryController::class)->except('update');
    Route::post('missionaries/{missionary}/edit', [MissionaryController::class, 'update'])->name('missionaries.update');

});
