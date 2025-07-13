<?php

namespace Tests\Feature\Testimonies;

use App\Models\Testimony;

use Tests\BaseTestCase;

class TestimonyShowTest extends BaseTestCase
{
    public function test_it_shows_testimony_successfully(): void
    {
        $this->actingAsUser();

        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);

        $response = $this->getJson(route('testimonies.show', $testimony));

        $response->assertOk()
            ->assertJsonStructure([
                'status' => [
                    'status'
                ],
                'data' => [
                    'id',
                    'name',
                    'content',
                    'image',
                    'rating',
                    'created_at',
                    'updated_at',
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => 'OK',
                ],
                'data' => [
                    'id' => $testimony->id,
                    'name' => 'Juan Pérez',
                    'content' => 'Este es un testimonio increíble sobre mi experiencia.',
                    'rating' => 5,
                ]
            ]);
    }

    public function test_it_returns_404_for_nonexistent_testimony(): void
    {
        $this->actingAsUser();

        $response = $this->getJson(route('testimonies.show', 999));

        $response->assertNotFound();
    }
}
