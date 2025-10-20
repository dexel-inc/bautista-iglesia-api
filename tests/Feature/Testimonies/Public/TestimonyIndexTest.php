<?php

namespace Tests\Feature\Testimonies\Public;

use App\Models\Testimony;
use Tests\BaseTestCase;

class TestimonyIndexTest extends BaseTestCase
{
    public function test_it_returns_all_testimonies_successfully(): void
    {
        Testimony::factory()->count(3)->create();

        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data',
            ]);
    }

    public function test_it_returns_empty_array_when_no_testimonies_exist(): void
    {
        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'status' => [
                    'status' => 'OK',
                ],
                'data' => [],
            ]);
    }

    public function test_it_returns_testimonies_with_correct_structure(): void
    {
        Testimony::factory()->count(3)->create();

        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'content',
                    ]
                ]
            ]);
    }
}
