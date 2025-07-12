<?php

namespace App\Http\Requests\Missionaries;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMissionaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'message' => 'sometimes|string|max:2000',
            'image' => 'sometimes|string|max:255',
            'disable_at' => 'sometimes|date|nullable',
        ];
    }
} 