<?php

namespace App\Actions;

use App\Models\Missionary;

class StoreOrUpdateMissionaryAction
{
    public function execute(Missionary $missionary, array $data): Missionary
    {
        $missionary->title = $data['title'];
        $missionary->message = $data['message'];
        $missionary->image = $data['image'];
        $missionary->disable_at = $data['disable_at'] ?? null;
        $missionary->save();

        return $missionary;
    }
}
