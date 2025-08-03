<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Testimony;

class StoreOrUpdateTestimonyAction
{
    public function execute(Testimony $testimony, array $data): Testimony
    {
        $testimony->name = $data['name'] ?? $testimony->name;
        $testimony->content = $data['content'] ?? $testimony->content;
        $testimony->rating = $data['rating'] ?? '5';
        $testimony->save();

        return $testimony;
    }
}
