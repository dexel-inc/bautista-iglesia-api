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
            ->assertJsonCount(3);
    }

    public function test_it_returns_empty_array_when_no_missionaries_exist(): void
    {
        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    public function test_it_returns_missionaries_with_correct_structure(): void
    {
        Missionary::factory()->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'message',
                    'image',
                    'disable_at',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->getJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_large_number_of_missionaries(): void
    {
        Missionary::factory()->count(100)->create();

        $response = $this->getJson(route('missionaries.index'));

        $response->assertOk();
        $this->assertLessThanOrEqual(100, count($response->json()));
    }

    public function test_it_returns_correct_http_status_codes(): void
    {
        $response = $this->getJson(route('missionaries.index'));
        $response->assertStatus(200);

        $response = $this->getJson('/api/nonexistent');
        $response->assertStatus(404);
    }
}
