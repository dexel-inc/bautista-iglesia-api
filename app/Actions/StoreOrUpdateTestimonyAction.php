<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Testimony;

class StoreOrUpdateTestimonyAction
{
    public function execute(Testimony $testimony, array $data): Testimony
    {
        $testimony->name = $data['name'];
        $testimony->content = $data['content'];
        $testimony->rating = $data['rating'];

        if (isset($data['image'])) {
            $imagePath = FilesHelper::save('testimony/images', $data['image']);
            $testimony->image = $imagePath;
        }

        $testimony->save();

        return $testimony;
    }
}
