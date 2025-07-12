<?php

namespace App\Actions;

use App\Models\Missionary;

class StoreMissionaryAction
{
    public function execute(array $data): Missionary
    {
        return Missionary::create($data);
    }
} 