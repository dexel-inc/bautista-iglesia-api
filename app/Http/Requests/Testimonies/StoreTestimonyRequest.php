<?php

namespace App\Http\Requests\Testimonies;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'rating' => 'sometimes|integer|min:1|max:5',
        ];
    }
} 