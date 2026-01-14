<?php

namespace App\Http\Requests\Contents;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
} 