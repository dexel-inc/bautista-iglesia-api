<?php

namespace App\Actions;

use App\Helpers\FilesHelper;
use App\Models\Content;

class StoreOrUpdateContentAction
{
    public function execute(Content $content, array $data): Content
    {
        $content->title = $data['title'];
        $content->type = $data['type'];
        $content->description = $data['description'];

        if (isset($data['image'])) {
            $imagePath = FilesHelper::save('content/images', $data['image']);
            $content->image = $imagePath;
        }

        $content->save();

        return $content;
    }
}
