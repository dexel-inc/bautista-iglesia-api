<?php

namespace Tests\Feature\Missionaries;

use App\Models\Missionary;

use Tests\BaseTestCase;

class MissionaryIndexTest extends BaseTestCase
{
    public function test_it_returns_all_missionaries_successfully(): void
    {
        $this->actingAsUser();

        Missionary::factory()->count(3)->create();

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
        $this->actingAsUser();

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
        $this->actingAsUser();

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
                        'disable_at',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }
}
