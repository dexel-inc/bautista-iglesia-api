<?php

namespace Tests\Feature\Contents;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_contents_successfully(): void
    {
        Content::factory()->count(3)->create();

        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function test_it_returns_empty_array_when_no_contents_exist(): void
    {
        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }

    public function test_it_returns_contents_with_correct_structure(): void
    {
        Content::factory()->create();

        $response = $this->getJson(route('contents.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'type',
                    'title',
                    'description',
                    'image',
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

    public function test_it_handles_large_number_of_contents(): void
    {
        Content::factory()->count(100)->create();

        $response = $this->getJson(route('contents.index'));

        $response->assertOk();
        $this->assertLessThanOrEqual(100, count($response->json()));
    }

    public function test_it_returns_correct_http_status_codes(): void
    {
        $response = $this->getJson(route('contents.index'));
        $response->assertStatus(200);

        $response = $this->getJson('/api/nonexistent');
        $response->assertStatus(404);
    }
}
