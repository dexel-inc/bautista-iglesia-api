<?php

namespace App\Actions;

use App\Models\Content;

class StoreOrUpdateContentAction
{
    public function execute(Content $content, array $data): Content
    {
        $content->title = $data['title'];
        $content->type = $data['type'];
        $content->description = $data['description'];
        $content->image = $data['image'];
        $content->save();

        return $content;
    }
}
