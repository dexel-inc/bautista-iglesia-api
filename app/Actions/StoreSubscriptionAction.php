<?php

namespace App\Actions;

use App\Models\Subscription;

class StoreSubscriptionAction
{
    public function execute(array $data): Subscription
    {
        return Subscription::create($data);
    }
} 