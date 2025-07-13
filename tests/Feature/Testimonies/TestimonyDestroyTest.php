<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_testimony_successfully(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);

        $response = $this->deleteJson(route('testimonies.destroy', $testimony));

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The testimony was deleted correctly',
                ],
            ]);

        $this->assertDatabaseMissing('testimonies', [
            'id' => $testimony->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_testimony(): void
    {
        $response = $this->deleteJson(route('testimonies.destroy', 999));

        $response->assertNotFound();
    }
}
