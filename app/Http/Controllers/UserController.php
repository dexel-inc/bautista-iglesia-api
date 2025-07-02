<?php

namespace App\Http\Controllers;

use App\Actions\StoreUserAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): array
    {
        return User::all()->toArray();
    }

    public function store(StoreUserRequest $request, StoreUserAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return response()->json([
            'user' => $request->validated(),
            'message' => 'The user was created correctly',
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): JsonResponse
    {
        $action->execute($request->validated(), $user);

        return response()->json([
            'user' => $request->validated(),
            'message' => 'The user was created correctly',
        ]);
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'user deleted' => $user,
            'message' => 'The user was deleted correctly',
        ]);
    }
}
