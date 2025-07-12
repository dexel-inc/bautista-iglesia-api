<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_subscription_successfully(): void
    {
        $subscriptionData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertCreated()
            ->assertJson([
                'subscription' => [
                    'name' => 'Juan Pérez',
                    'email' => 'juan.perez@example.com',
                    'phone' => '1234567890',
                ],
                'message' => 'The subscription was created correctly',
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('subscriptions.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone']);
    }

    public function test_it_validates_email_format(): void
    {
        $subscriptionData = [
            'name' => 'Juan Pérez',
            'email' => 'invalid-email',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        Subscription::factory()->create(['email' => 'juan.perez@example.com']);

        $subscriptionData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_string_fields(): void
    {
        $subscriptionData = [
            'name' => 123,
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $subscriptionData = [
            'name' => str_repeat('a', 256),
            'email' => str_repeat('c', 250) . '@example.com',
            'phone' => str_repeat('1', 21),
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'phone']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $subscriptionData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertCreated()
            ->assertJsonStructure([
                'subscription' => [
                    'name',
                    'email',
                    'phone',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->postJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $subscriptionData = [
            'name' => 'María José García-López',
            'email' => 'maria.garcia@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertCreated()
            ->assertJson([
                'subscription' => [
                    'name' => 'María José García-López',
                    'email' => 'maria.garcia@example.com',
                    'phone' => '1234567890',
                ],
            ]);
    }
}
