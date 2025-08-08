<?php

namespace Tests\Feature\Missionaries;

use App\Models\Missionary;

use Tests\BaseTestCase;

class MissionaryShowTest extends BaseTestCase
{
    public function test_it_shows_missionary_successfully(): void
    {
        $this->actingAsUser();

        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $response = $this->getJson(route('missionaries.show', $missionary));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'title',
                    'message',
                    'image',
                    'isEnabled',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => 'OK',
                ],
                'data' => [
                    'id' => $missionary->id,
                    'title' => 'Misión en África',
                    'message' => 'Esta es una misión increíble para llevar esperanza a África.',
                    'user' => [
                        'name' => null,
                        'email' => null,
                    ],
                    'image' => '/storage/https://example.com/africa-mission.jpg',
                    'isEnabled' => false,
                ]
            ]);
    }

    public function test_it_returns_404_for_nonexistent_missionary(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('missionaries.show', 999));

        $response->assertNotFound();
    }
}
