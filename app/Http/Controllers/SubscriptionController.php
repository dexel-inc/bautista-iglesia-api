<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateSubscriptionAction;
use App\Http\Requests\Subscriptions\SubscriptionRequest;
use App\Http\Requests\Subscriptions\UpdateSubscriptionRequest;
use App\Http\Resources\Api\SubscriptionResource;
use App\Models\Subscription;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::all();
        return ApiResponse::successWithData(SubscriptionResource::collection($subscriptions));
    }

    public function store(SubscriptionRequest $request, StoreOrUpdateSubscriptionAction $action): JsonResponse
    {
        $subscription = $action->execute(new Subscription(), $request->validated());
        return ApiResponse::created(SubscriptionResource::make($subscription));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription, StoreOrUpdateSubscriptionAction $action): JsonResponse
    {
        $action->execute($subscription, $request->validated());
        return ApiResponse::updated($subscription->id);
    }

    public function show(Subscription $subscription): JsonResponse
    {
        return ApiResponse::successWithData(SubscriptionResource::make($subscription));
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();
        return ApiResponse::successOnly();
    }
}
