<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateSubscriptionAction;
use App\Http\Requests\Subscriptions\SubscriptionRequest;
use App\Http\Resources\Api\SubscriptionResource;
use App\Models\Subscription;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SubscriptionResource::collection(Subscription::all());
    }

    public function store(SubscriptionRequest $request, StoreOrUpdateSubscriptionAction $action): JsonResponse
    {
        $action->execute(new Subscription(), $request->validated());
        return ApiResponse::quickCreated('The subscription was created correctly');
    }

    public function update(SubscriptionRequest $request, Subscription $subscription, StoreOrUpdateSubscriptionAction $action): JsonResponse
    {
        $action->execute($subscription, $request->validated());

        return ApiResponse::quickOk('The subscription was updated correctly');
    }

    public function show(Subscription $subscription): SubscriptionResource
    {
        return SubscriptionResource::make($subscription);
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();

        return ApiResponse::quickOk('The subscription was deleted correctly');
    }
} 