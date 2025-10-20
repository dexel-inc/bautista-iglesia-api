<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\Status;
use App\Models\Subscription;

use Carbon\Carbon;
use Tests\BaseTestCase;

class SubscriptionUpdateTest extends BaseTestCase
{
    public function test_it_can_disabled_a_subscription(): void
    {
        Carbon::setTestNow('2024-01-01 12:00:00');
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
            'disabled_at' => null,
        ]);

        $response = $this->patchJson(route('subscriptions.update', $subscription), [
            'isEnabled' => false,
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => $subscription->id,
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'disabled_at' => now(),
        ]);
    }
    public function test_it_can_not_edit_the_email(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
        ]);

        $response = $this->patchJson(route('subscriptions.update', $subscription), [
            'email' => 'juan.perez2@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => $subscription->id,
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'email' => 'juan.perez@example.com',
        ]);
    }

    public function test_it_enabled_a_subscription(): void
    {
        $this->actingAsUser();

        $subscription = Subscription::factory()->create([
            'email' => 'juan.perez@example.com',
            'disabled_at' => now(),
        ]);

        $response = $this->patchJson(route('subscriptions.update', $subscription), [
            'isEnabled' => true,
        ]);

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

        $response = $this->patchJson(route('subscriptions.update', 999));

        $response->assertNotFound();
    }
}
