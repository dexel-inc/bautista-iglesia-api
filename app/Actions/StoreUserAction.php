<?php

namespace App\Actions;

use App\Models\User;

class StoreUserAction
{
    public function execute(array $data): void
    {
        User::create($data);
    }
}
