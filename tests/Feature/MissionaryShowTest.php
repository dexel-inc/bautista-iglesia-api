<?php

namespace Tests\Feature;

use App\Models\Missionary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionaryShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_missionary_successfully(): void
    {
        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $response = $this->getJson(route('missionaries.show', $missionary));

        $response->assertOk()
            ->assertJson([
                'id' => $missionary->id,
                'title' => 'Misión en África',
                'message' => 'Esta es una misión increíble para llevar esperanza a África.',
                'image' => 'https://example.com/africa-mission.jpg',
            ]);
    }

    public function test_it_returns_404_for_nonexistent_missionary(): void
    {
        $response = $this->getJson(route('missionaries.show', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $missionary = Missionary::factory()->create();

        $response = $this->getJson(route('missionaries.show', $missionary));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'title',
                'message',
                'image',
                'disable_at',
                'created_at',
                'updated_at',
            ]);
    }
} 