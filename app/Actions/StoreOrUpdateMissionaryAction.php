<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Missionary;

class StoreOrUpdateMissionaryAction
{
    public function execute(Missionary $missionary, array $data): Missionary
    {
        $missionary->title = $data['title'] ?? $missionary->title;
        $missionary->message = $data['message'] ?? $missionary->message;
        $missionary->contact_name = $data['contact_name'] ?? $missionary->contact_name;
        $missionary->contact_email = $data['contact_email'] ?? $missionary->contact_email;
        $missionary->disable_at = $data['disable_at'] ?? null;

        if (isset($data['image'])) {
            $imagePath = FilesHelper::save('missionary/images', $data['image']);
            $missionary->image = $imagePath;
        }

        $missionary->save();

        return $missionary;
    }
}
