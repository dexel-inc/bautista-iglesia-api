<?php

namespace App\Http\Controllers;

use App\Actions\StoreOrUpdateUserAction;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return ApiResponse::successWithData(UserResource::collection($users));
    }

    public function store(UserRequest $request, StoreOrUpdateUserAction $action): JsonResponse
    {
        $user = $action->execute(new User(), $request->validated());
        return ApiResponse::created(UserResource::make($user));
    }

    public function update(UserRequest $request, User $user, StoreOrUpdateUserAction $action): JsonResponse
    {
        $action->execute($user, $request->validated());
        return ApiResponse::updated($user->id);
    }

    public function show(User $user): JsonResponse
    {
        return ApiResponse::successWithData(UserResource::make($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return ApiResponse::successOnly();
    }
}
