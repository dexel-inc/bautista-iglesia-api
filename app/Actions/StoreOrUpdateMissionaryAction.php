<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Missionary;

class StoreOrUpdateMissionaryAction
{
    public function execute(Missionary $missionary, array $data): Missionary
    {
        $missionary->title = $data['title'];
        $missionary->message = $data['message'];
        $missionary->disable_at = $data['disable_at'] ?? null;

        if (isset($data['image'])) {
            $imagePath = FilesHelper::save('missionary/images', $data['image']);
            $missionary->image = $imagePath;
        }

        $missionary->save();

        return $missionary;
    }
}
