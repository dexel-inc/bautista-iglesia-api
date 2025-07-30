<?php

namespace App\Http\Requests\Testimonies;

use Illuminate\Foundation\Http\FormRequest;

class TestimonyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg'],
            'content' => ['required', 'string', 'max:1000'],
            'rating' => 'sometimes|integer|min:1|max:5',
        ];
    }
}
