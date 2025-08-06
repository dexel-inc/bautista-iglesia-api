<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subscriptionId = $this->route('subscription');

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('subscriptions', 'email')->ignore($subscriptionId),
            ],
        ];
    }
}
