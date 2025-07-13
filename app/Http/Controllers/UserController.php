<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateUserAction;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    public function store(UserRequest $request, StoreOrUpdateUserAction $action): JsonResponse
    {
        $action->execute(new User(), $request->validated());
        return ApiResponse::quickCreated('The user was created correctly');
    }

    public function update(UserRequest $request, User $user, StoreOrUpdateUserAction $action): JsonResponse
    {
        $action->execute($user, $request->validated());

        return ApiResponse::quickOk('The user was updated correctly');
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return ApiResponse::quickOk('The user was deleted correctly');
    }
}
