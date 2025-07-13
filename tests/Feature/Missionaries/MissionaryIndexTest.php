<?php

namespace Tests\Feature\Missionaries;

use App\Models\Missionary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionaryIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_missionaries_successfully(): void
    {
        Missionary::factory()->count(3)->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_it_returns_empty_array_when_no_missionaries_exist(): void
    {
        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'data' => []
            ]);
    }

    public function test_it_returns_missionaries_with_correct_structure(): void
    {
        Missionary::factory()->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonStructure([
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
