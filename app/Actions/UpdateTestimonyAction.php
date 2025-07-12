<?php

namespace App\Actions;

use App\Models\Testimony;

class UpdateTestimonyAction
{
    public function execute(array $data, Testimony $testimony): Testimony
    {
        $testimony->update($data);
        
        return $testimony;
    }
} 