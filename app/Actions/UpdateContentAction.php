<?php

namespace App\Actions;

use App\Models\Content;

class UpdateContentAction
{
    public function execute(array $data, Content $content): Content
    {
        $content->update($data);
        
        return $content;
    }
} 