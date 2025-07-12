<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'email',
                Rule::unique('subscriptions', 'email')->ignore($this->subscription->id)
            ],
            'phone' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
        ];
    }
} 