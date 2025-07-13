<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\LoginUserAction;
use App\Actions\Users\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Http\Requests\Users\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerUserAction,
        private readonly LoginUserAction $loginUserAction
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->registerUserAction->execute($request->validated());

        return ApiResponse::created(UserResource::make($user));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->loginUserAction->execute($request->validated());

        return ApiResponse::successWithData([
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
            'user' => UserResource::make($result['user']),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::successWithData(UserResource::make($request->user()));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::successOnly();
    }
}
