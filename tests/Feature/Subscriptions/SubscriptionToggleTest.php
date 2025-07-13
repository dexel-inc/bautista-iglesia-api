<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_disables_enabled_subscription(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'disabled_at' => null,
        ]);

        $response = $this->patchJson(route('subscriptions.toggle', $subscription));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $subscription->id,
                ],
            ]);

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
            'disabled_at' => null,
        ]);
    }

    public function test_it_enables_disabled_subscription(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
            'disabled_at' => now(),
        ]);

        $response = $this->patchJson(route('subscriptions.toggle', $subscription));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $subscription->id,
                ],
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'disabled_at' => null,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $response = $this->patchJson(route('subscriptions.toggle', 999));

        $response->assertNotFound();
    }
} 