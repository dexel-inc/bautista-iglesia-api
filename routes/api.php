<?php

use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\PrayLetterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
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
    Route::apiResource('subscriptions', SubscriptionController::class)->except('store');
    Route::apiResource('testimonies', TestimonyController::class)->except('update');
    Route::post('testimonies/{testimony}/edit', [TestimonyController::class, 'update'])->name('testimonies.update');

    Route::apiResource('missionaries', MissionaryController::class)->except('update');
    Route::post('missionaries/{missionary}/edit', [MissionaryController::class, 'update'])->name('missionaries.update');
    Route::post('pray-letters/send', [PrayLetterController::class, 'send'])->name('pray-letters.send');
    Route::post('newsletters/send', [NewsLetterController::class, 'send'])->name('newsletters.send');
    Route::post('/visits/stats', [VisitController::class, 'stats']);
});

Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
Route::post('/visits', [VisitController::class, 'store']);
Route::get('public/testimonies', [\App\Http\Controllers\Public\TestimonyController::class, 'index'])->name('public.testimonies.index');
Route::get('public/missionaries', [\App\Http\Controllers\Public\MissionaryController::class, 'index'])->name('public.missionaries.index');
