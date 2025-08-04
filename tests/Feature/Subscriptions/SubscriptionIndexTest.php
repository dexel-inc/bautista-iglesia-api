<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;

use Tests\BaseTestCase;

class SubscriptionIndexTest extends BaseTestCase
{
    public function test_it_returns_all_subscriptions_successfully(): void
    {
        $this->actingAsUser();

        Subscription::factory()->count(3)->create();

        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_empty_array_when_no_subscriptions_exist(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'status' => [
                    'status' => 'OK'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_subscriptions_with_correct_structure(): void
    {
        $this->actingAsUser();

        Subscription::factory()->create();

        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }
}
