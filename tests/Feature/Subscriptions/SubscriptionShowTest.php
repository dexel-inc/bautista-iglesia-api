<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;

use Tests\BaseTestCase;

class SubscriptionShowTest extends BaseTestCase
{
    public function test_it_shows_subscription_successfully(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
        ]);

        $response = $this->getJson(route('subscriptions.show', $subscription));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'email',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => 'OK',
                ],
                'data' => [
                    'id' => $subscription->id,
                    'email' => 'juan.perez@example.com',
                ]
            ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('subscriptions.show', 999));

        $response->assertNotFound();
    }
}
