<?php

namespace App\Actions;

use App\Models\Subscription;

class UpdateSubscriptionAction
{
    public function execute(array $data, Subscription $subscription): Subscription
    {
        $subscription->update($data);
        
        return $subscription;
    }
} 