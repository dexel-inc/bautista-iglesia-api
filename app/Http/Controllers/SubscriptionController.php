<?php

namespace App\Http\Controllers;

use App\Actions\StoreSubscriptionAction;
use App\Actions\UpdateSubscriptionAction;
use App\Http\Requests\Subscriptions\StoreSubscriptionRequest;
use App\Http\Requests\Subscriptions\UpdateSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function index(): array
    {
        return Subscription::all()->toArray();
    }

    public function store(StoreSubscriptionRequest $request, StoreSubscriptionAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'subscription' => $request->validated(),
            'message' => 'The subscription was created correctly',
        ], 201);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription, UpdateSubscriptionAction $action): JsonResponse
    {
        $action->execute($request->validated(), $subscription);

        return response()->json([
            'subscription' => $request->validated(),
            'message' => 'The subscription was updated correctly',
        ]);
    }

    public function show(Subscription $subscription): Subscription
    {
        return $subscription;
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();

        return response()->json([
            'subscription deleted' => $subscription,
            'message' => 'The subscription was deleted correctly',
        ]);
    }
} 