<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_subscription_successfully(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->deleteJson(route('subscriptions.destroy', $subscription));

        $response->assertOk()
            ->assertJson([
                'subscription deleted' => [
                    'id' => $subscription->id,
                    'name' => 'Juan Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ],
                'message' => 'The subscription was deleted correctly',
            ]);

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $response = $this->deleteJson(route('subscriptions.destroy', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $subscription = Subscription::factory()->create();

        $response = $this->deleteJson(route('subscriptions.destroy', $subscription));

        $response->assertOk()
            ->assertJsonStructure([
                'subscription deleted' => [
                    'id',
                    'email',
                    'phone',
                    'name',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }

    public function test_it_removes_subscription_from_database(): void
    {
        $subscription = Subscription::factory()->create();

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
        ]);

        $this->deleteJson(route('subscriptions.destroy', $subscription));

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription->id,
        ]);
    }

    public function test_it_does_not_affect_other_subscriptions(): void
    {
        $subscription1 = Subscription::factory()->create(['email' => 'user1@example.com']);
        $subscription2 = Subscription::factory()->create(['email' => 'user2@example.com']);

        $this->deleteJson(route('subscriptions.destroy', $subscription1));

        $this->assertDatabaseMissing('subscriptions', [
            'id' => $subscription1->id,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription2->id,
            'email' => 'user2@example.com',
        ]);
    }
}
