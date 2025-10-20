<?php

namespace Tests\Feature\Missionaries\Public;

use App\Models\Missionary;
use Tests\BaseTestCase;

class MissionaryIndexTest extends BaseTestCase
{
    public function test_it_returns_all_missionaries_successfully(): void
    {
        Missionary::factory(['disable_at' => null])->count(3)->create();
        Missionary::factory(['disable_at'  => now()])->count(2)->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_empty_array_when_no_missionaries_exist(): void
    {
        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'status' => [
                    'status' => 'OK'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_missionaries_with_correct_structure(): void
    {
        Missionary::factory()->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'message',
                        'image',
                    ]
                ]
            ]);
    }
}
