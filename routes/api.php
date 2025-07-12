<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('testimonies', TestimonyController::class);
