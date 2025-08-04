<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Subscription;

use Tests\BaseTestCase;

class SubscriptionDestroyTest extends BaseTestCase
{
    public function test_it_deletes_subscription_successfully(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
        ]);

        $response = $this->deleteJson(route('subscriptions.destroy', $subscription));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
            ]);

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $this->actingAsUser();

        $response = $this->deleteJson(route('subscriptions.destroy', 999));

        $response->assertNotFound();
    }
}
