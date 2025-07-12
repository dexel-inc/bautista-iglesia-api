<?php

namespace App\Actions;

use App\Models\Testimony;

class StoreTestimonyAction
{
    public function execute(array $data): Testimony
    {
        return Testimony::create($data);
    }
} 