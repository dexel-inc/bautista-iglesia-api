<?php

namespace Tests\Feature\Subscriptions\Public;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Subscription;
use Tests\BaseTestCase;

class SubscriptionStoreTest extends BaseTestCase
{
    public function test_it_creates_subscription_successfully(): void
    {
        $subscriptionData = [
            'email' => 'juan.perez@example.com',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertStatus(Response::HTTP_CREATED)
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
                    'status' => Status::OK,
                ],
                'data' => [
                    'email' => 'juan.perez@example.com',
                ]
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'email' => 'juan.perez@example.com',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('subscriptions.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_format(): void
    {
        $subscriptionData = [
            'email' => 'invalid-email',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_uniqueness(): void
    {
        Subscription::factory()->create(['email' => 'juan.perez@example.com']);

        $subscriptionData = [
            'email' => 'juan.perez@example.com',
        ];

        $response = $this->postJson(route('subscriptions.store'), $subscriptionData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
