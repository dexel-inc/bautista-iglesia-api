<?php

namespace App\Actions;

use App\Models\Missionary;

class UpdateMissionaryAction
{
    public function execute(array $data, Missionary $missionary): Missionary
    {
        $missionary->update($data);
        
        return $missionary;
    }
} 