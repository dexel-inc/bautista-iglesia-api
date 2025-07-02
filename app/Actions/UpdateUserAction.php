<?php

namespace App\Actions;

use App\Models\User;

class UpdateUserAction
{
    public function execute(array $data, User $user): void
    {
        $user->update($data);
    }
}
