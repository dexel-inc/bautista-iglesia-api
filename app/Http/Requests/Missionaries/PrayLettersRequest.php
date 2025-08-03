<?php

namespace App\Http\Requests\Missionaries;

use Illuminate\Foundation\Http\FormRequest;

class PrayLettersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
