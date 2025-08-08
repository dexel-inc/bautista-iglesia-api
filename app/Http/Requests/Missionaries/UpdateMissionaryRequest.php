<?php

namespace App\Http\Requests\Missionaries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'contact_email' => ['nullable', 'string', 'max:50'],
            'contact_name' => ['nullable', 'string', 'max:50'],
            'image' => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif,svg'],
            'isEnabled' => 'sometimes|boolean|nullable',
        ];
    }


    protected function prepareForValidation(): void
    {
        $isEnabled = $this->get('isEnabled');
        if($isEnabled && is_string($isEnabled)) {
            $this->merge([
                'isEnabled' => strtolower($this->get('isEnabled')) === 'true',
            ]);
        }
    }
}
