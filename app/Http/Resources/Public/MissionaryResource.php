<?php

namespace App\Http\Resources\Public;

use App\Helpers\FilesHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MissionaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'image' => FilesHelper::get($this->image),
        ];
    }

}
