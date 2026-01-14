<?php

namespace Tests\Feature\Testimonies;

use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyIndexTest extends TestCase
{
    use RefreshDatabase;

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
                'data' => []
            ]);
    }

    public function test_it_returns_empty_array_when_no_testimonies_exist(): void
    {
        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'status' => [
                    'status' => 'OK'
                ],
                'data' => []
            ]);
    }

    public function test_it_returns_testimonies_with_correct_structure(): void
    {
        Testimony::factory()->create();

        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'content',
                        'image',
                        'rating',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }
}
