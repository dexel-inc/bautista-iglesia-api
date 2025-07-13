<?php

namespace Tests\Feature\Testimonies;

use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_testimony_successfully(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Este es un testimonio increíble sobre mi experiencia.',
            'rating' => 5,
        ]);

        $response = $this->getJson(route('testimonies.show', $testimony));

        $response->assertOk()
            ->assertJson([
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
        $response = $this->getJson(route('testimonies.show', 999));

        $response->assertNotFound();
    }
}
