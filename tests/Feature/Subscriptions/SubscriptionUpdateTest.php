<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_subscription_successfully(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'email' => 'juan.carlos@example.com',
            'phone' => '0987654321',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertOk()
            ->assertJson([
                'subscription' => [
                    'name' => 'Juan Carlos Pérez',
                    'email' => 'juan.carlos@example.com',
                    'phone' => '0987654321',
                ],
                'message' => 'The subscription was updated correctly',
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'name' => 'Juan Carlos Pérez',
            'email' => 'juan.carlos@example.com',
            'phone' => '0987654321',
        ]);
    }

    public function test_it_updates_subscription_partially(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertOk()
            ->assertJson([
                'subscription' => [
                    'name' => 'Juan Carlos Pérez',
                ],
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'name' => 'Juan Carlos Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_validates_email_format(): void
    {
        $subscription = Subscription::factory()->create();

        $updateData = [
            'email' => 'invalid-email',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        $subscription1 = Subscription::factory()->create(['email' => 'user1@example.com']);
        $subscription2 = Subscription::factory()->create(['email' => 'user2@example.com']);

        $updateData = [
            'email' => 'user1@example.com',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription2), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_allows_same_email_for_same_subscription(): void
    {
        $subscription = Subscription::factory()->create(['email' => 'juan.perez@example.com']);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'email' => 'juan.perez@example.com',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertOk()
            ->assertJson([
                'subscription' => [
                    'name' => 'Juan Carlos Pérez',
                    'email' => 'juan.perez@example.com',
                ],
            ]);
    }

    public function test_it_validates_string_fields(): void
    {
        $subscription = Subscription::factory()->create();

        $updateData = [
            'name' => 123,
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $subscription = Subscription::factory()->create();

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertOk()
            ->assertJsonStructure([
                'subscription' => [
                    'name',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_nonexistent_subscription(): void
    {
        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('subscriptions.update', 999), $updateData);

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $subscription = Subscription::factory()->create();

        $updateData = [
            'name' => 'María José García-López',
        ];

        $response = $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $response->assertOk()
            ->assertJson([
                'subscription' => [
                    'name' => 'María José García-López',
                ],
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'name' => 'María José García-López',
        ]);
    }

    public function test_it_updates_only_provided_fields(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $this->putJson(route('subscriptions.update', $subscription), $updateData);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'name' => 'Juan Carlos Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_accepts_empty_update_data(): void
    {
        $subscription = Subscription::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);

        $response = $this->putJson(route('subscriptions.update', $subscription), []);

        $response->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }
}
