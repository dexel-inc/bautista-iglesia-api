<?php

namespace App\Http\Resources\Public;

use App\Constants\TypesPrayletter;
use App\Helpers\FilesHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MissionaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'image' => FilesHelper::get($this->image),
            'type' => $this->type,
            'url' => $this->type === TypesPrayletter::FILE->value && $this->url
                ? FilesHelper::get($this->url)
                : $this->url,
        ];
    }

}
