<?php

namespace Tests\Feature\Missionaries;

use App\Models\Missionary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionaryDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_missionary_successfully(): void
    {
        $missionary = Missionary::factory()->create([
            'title' => 'Misión en África',
            'message' => 'Esta es una misión increíble para llevar esperanza a África.',
            'image' => 'https://example.com/africa-mission.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $response = $this->deleteJson(route('missionaries.destroy', $missionary));

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => 'ok',
                    'reason' => 200,
                    'message' => 'The missionary was deleted correctly',
                ],
            ]);

        $this->assertDatabaseMissing('missionaries', [
            'id' => $missionary->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_missionary(): void
    {
        $response = $this->deleteJson(route('missionaries.destroy', 999));

        $response->assertNotFound();
    }
}
