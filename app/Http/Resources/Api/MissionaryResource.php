<?php

namespace App\Http\Resources\Api;

use App\Helpers\FilesHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MissionaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'user' => [
                'name' => $this->contact_name,
                'email' => $this->contact_email
            ],
            'image' => FilesHelper::get($this->image),
            'disable_at' => $this->disable_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
