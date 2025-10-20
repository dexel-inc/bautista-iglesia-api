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
            'message' => ['sometimes', 'string', 'max:2000'],
            'contact_email' => ['nullable', 'string', 'max:50'],
            'contact_name' => ['nullable', 'string', 'max:50'],
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'isEnabled' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        if($this->get('isEnabled')) {
            $this->merge([
                'isEnabled' => $this->get('isEnabled') === 'true',
            ]);
        }
    }
}
