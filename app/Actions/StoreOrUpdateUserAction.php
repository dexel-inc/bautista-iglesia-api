<?php

namespace App\Actions;

use App\Models\User;

class StoreOrUpdateUserAction
{
    public function execute(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];

        if (isset($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return $user;
    }
}
