<?php

namespace App\Actions;

use App\Models\Subscription;

class StoreOrUpdateSubscriptionAction
{
    public function execute(Subscription $subscription, array $data): Subscription
    {
        $subscription->email = $data['email'];
        $subscription->save();

        return $subscription;
    }
}
