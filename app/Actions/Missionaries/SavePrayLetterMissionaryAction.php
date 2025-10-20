<?php

namespace App\Actions\Missionaries;

use App\Helpers\FilesHelper;
use App\Models\Missionary;

class SavePrayLetterMissionaryAction
{
    public function execute(array $data, Missionary $missionary)
    {
        if ($data['type'] == 'file') {
            $filePath = FilesHelper::save('pray-letters', $data['file']);
        } else {
            $filePath = $data['link'];
        }

        $missionary->type = $data['type'];
        $missionary->url = $filePath;
        $missionary->save();
    }
}
