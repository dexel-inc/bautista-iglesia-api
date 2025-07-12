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
            ->assertJsonCount(3);
    }

    public function test_it_returns_empty_array_when_no_testimonies_exist(): void
    {
        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    public function test_it_returns_testimonies_with_correct_structure(): void
    {
        Testimony::factory()->create();

        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'content',
                    'rating',
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

    public function test_it_handles_large_number_of_testimonies(): void
    {
        Testimony::factory()->count(100)->create();

        $response = $this->getJson(route('testimonies.index'));

        $response->assertOk();
        $this->assertLessThanOrEqual(100, count($response->json()));
    }

    public function test_it_returns_correct_http_status_codes(): void
    {
        $response = $this->getJson(route('testimonies.index'));
        $response->assertStatus(200);

        $response = $this->getJson('/api/nonexistent');
        $response->assertStatus(404);
    }
}
