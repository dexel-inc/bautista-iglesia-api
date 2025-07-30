<?php

namespace App\Http\Requests\Testimonies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'image' => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif,svg'],
            'content' => ['sometimes', 'string', 'max:1000'],
            'rating' => 'sometimes|integer|min:1|max:5',
        ];
    }
}
