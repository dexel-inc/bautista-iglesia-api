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
            'type' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'link' => ['string', 'nullable'],
        ];
    }
}
