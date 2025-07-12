<?php

namespace App\Actions;

use App\Models\Content;

class StoreContentAction
{
    public function execute(array $data): Content
    {
        return Content::create($data);
    }
} 