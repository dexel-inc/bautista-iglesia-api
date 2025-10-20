<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    private const TOKEN_NAME = 'auth_token';
    private const TOKEN_TYPE = 'Bearer';

    public function execute(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken(self::TOKEN_NAME)->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => self::TOKEN_TYPE,
            'user' => $user,
        ];
    }

}
