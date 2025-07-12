<?php

namespace Tests\Feature;

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
                'testimony deleted' => [
                    'id' => $testimony->id,
                    'name' => 'Juan Pérez',
                    'content' => 'Este es un testimonio increíble sobre mi experiencia.',
                    'rating' => 5,
                ],
                'message' => 'The testimony was deleted correctly',
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

    public function test_it_returns_correct_response_structure(): void
    {
        $testimony = Testimony::factory()->create();

        $response = $this->deleteJson(route('testimonies.destroy', $testimony));

        $response->assertOk()
            ->assertJsonStructure([
                'testimony deleted' => [
                    'id',
                    'name',
                    'content',
                    'rating',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }

    public function test_it_removes_testimony_from_database(): void
    {
        $testimony = Testimony::factory()->create();

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
        ]);

        $this->deleteJson(route('testimonies.destroy', $testimony));

        $this->assertDatabaseMissing('testimonies', [
            'id' => $testimony->id,
        ]);
    }

    public function test_it_does_not_affect_other_testimonies(): void
    {
        $testimony1 = Testimony::factory()->create(['name' => 'Juan Pérez']);
        $testimony2 = Testimony::factory()->create(['name' => 'María García']);

        $this->deleteJson(route('testimonies.destroy', $testimony1));

        $this->assertDatabaseMissing('testimonies', [
            'id' => $testimony1->id,
        ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony2->id,
            'name' => 'María García',
        ]);
    }
} 