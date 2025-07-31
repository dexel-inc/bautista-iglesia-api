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
            'title' => ['sometimes', 'string', 'max:255'],
            'message' => ['sometimes', 'string', 'max:2000'],
            'contact_email' => ['sometimes', 'string', 'max:50'],
            'contact_name' => ['sometimes', 'string', 'max:50'],
            'image' => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif,svg'],
            'disable_at' => 'sometimes|date|nullable',
        ];
    }
}
