<?php

namespace App\Actions;

use App\Models\Subscription;

class StoreOrUpdateSubscriptionAction
{
    public function execute(Subscription $subscription, array $data): Subscription
    {
        $subscription->email = $data['email'] ?? $subscription->email;
        $subscription->disabled_at = isset($data['isEnabled']) ? ($data['isEnabled'] ? null : now()) : $subscription->disabled_at;
        $subscription->save();

        return $subscription;
    }
}
