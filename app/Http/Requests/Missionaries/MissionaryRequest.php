<?php

namespace App\Http\Requests\Missionaries;

use Illuminate\Foundation\Http\FormRequest;

class MissionaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'contact_email' => ['required', 'string', 'max:50'],
            'contact_name' => ['required', 'string', 'max:50'],
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'disable_at' => 'date|nullable',
        ];
    }
}
