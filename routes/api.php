<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::patch('subscriptions/{subscription}/toggle', [SubscriptionController::class, 'toggle'])->name('subscriptions.toggle');
Route::apiResource('testimonies', TestimonyController::class);
Route::apiResource('missionaries', MissionaryController::class);
Route::apiResource('contents', ContentController::class);
