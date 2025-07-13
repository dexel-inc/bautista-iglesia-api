<?php

namespace App\Actions;

use App\Models\Testimony;

class StoreOrUpdateTestimonyAction
{
    public function execute(Testimony $testimony, array $data): Testimony
    {
        $testimony->name = $data['name'];
        $testimony->content = $data['content'];
        $testimony->image = $data['image'];
        $testimony->rating = $data['rating'];
        $testimony->save();

        return $testimony;
    }
}
