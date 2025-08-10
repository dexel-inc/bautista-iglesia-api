<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class NewsLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }
}
