<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Subscription;

use Tests\BaseTestCase;

class SubscriptionToggleTest extends BaseTestCase
{
    public function test_it_disables_enabled_subscription(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
            'disabled_at' => null,
        ]);

        $response = $this->patchJson(route('subscriptions.toggle', $subscription));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => $subscription->id,
            ]);

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
            'disabled_at' => null,
        ]);
    }

    public function test_it_enables_disabled_subscription(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
            'disabled_at' => now(),
        ]);

        $response = $this->patchJson(route('subscriptions.toggle', $subscription));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => $subscription->id,
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'disabled_at' => null,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $this->actingAsUser();

        $response = $this->patchJson(route('subscriptions.toggle', 999));

        $response->assertNotFound();
    }
}
