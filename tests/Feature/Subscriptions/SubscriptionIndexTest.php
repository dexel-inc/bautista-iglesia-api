<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_subscriptions_successfully(): void
    {
        Subscription::factory()->count(3)->create();

        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_it_returns_empty_array_when_no_subscriptions_exist(): void
    {
        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson(['data' => []]);
    }

    public function test_it_returns_subscriptions_with_correct_structure(): void
    {
        Subscription::factory()->create();

        $response = $this->getJson(route('subscriptions.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'phone',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }
}
