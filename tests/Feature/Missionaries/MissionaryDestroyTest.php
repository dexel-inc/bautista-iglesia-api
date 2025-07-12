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
                'missionary deleted' => [
                    'id' => $missionary->id,
                    'title' => 'Misión en África',
                    'message' => 'Esta es una misión increíble para llevar esperanza a África.',
                    'image' => 'https://example.com/africa-mission.jpg',
                ],
                'message' => 'The missionary was deleted correctly',
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

    public function test_it_returns_correct_response_structure(): void
    {
        $missionary = Missionary::factory()->create();

        $response = $this->deleteJson(route('missionaries.destroy', $missionary));

        $response->assertOk()
            ->assertJsonStructure([
                'missionary deleted' => [
                    'id',
                    'title',
                    'message',
                    'image',
                    'disable_at',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }

    public function test_it_removes_missionary_from_database(): void
    {
        $missionary = Missionary::factory()->create();

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
        ]);

        $this->deleteJson(route('missionaries.destroy', $missionary));

        $this->assertDatabaseMissing('missionaries', [
            'id' => $missionary->id,
        ]);
    }

    public function test_it_does_not_affect_other_missionaries(): void
    {
        $missionary1 = Missionary::factory()->create(['title' => 'Misión 1']);
        $missionary2 = Missionary::factory()->create(['title' => 'Misión 2']);

        $this->deleteJson(route('missionaries.destroy', $missionary1));

        $this->assertDatabaseMissing('missionaries', [
            'id' => $missionary1->id,
        ]);

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary2->id,
            'title' => 'Misión 2',
        ]);
    }
}
