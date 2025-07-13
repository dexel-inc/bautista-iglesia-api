<?php

namespace Tests\Feature\Testimonies;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Testimony;

use Tests\BaseTestCase;

class TestimonyDestroyTest extends BaseTestCase
{
    public function test_it_deletes_testimony_successfully(): void
    {
        $this->actingAsUser();

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);

        $response = $this->deleteJson(route('testimonies.destroy', $testimony));

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
            ]);

        $this->assertDatabaseMissing('testimonies', [
            'id' => $testimony->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_testimony(): void
    {
        $this->actingAsUser();

        $response = $this->deleteJson(route('testimonies.destroy', 999));

        $response->assertNotFound();
    }
}
