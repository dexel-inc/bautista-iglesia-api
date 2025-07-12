<?php

namespace Tests\Feature;

use App\Models\Testimony;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_testimony_successfully(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'testimony' => [
                    'name' => 'Juan Carlos Pérez',
                    'content' => 'Contenido actualizado',
                    'rating' => 5,
                ],
                'message' => 'The testimony was updated correctly',
            ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido actualizado',
            'rating' => 5,
        ]);
    }

    public function test_it_updates_testimony_partially(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'testimony' => [
                    'name' => 'Juan Carlos Pérez',
                ],
            ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 123,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256),
            'content' => str_repeat('b', 1001),
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }

    public function test_it_validates_rating_range(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 6,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_validates_rating_minimum(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'rating' => 0,
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJsonStructure([
                'testimony' => [
                    'name',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_nonexistent_testimony(): void
    {
        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $response = $this->putJson(route('testimonies.update', 999), $updateData);

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_names(): void
    {
        $testimony = Testimony::factory()->create();

        $updateData = [
            'name' => 'María José García-López',
        ];

        $response = $this->putJson(route('testimonies.update', $testimony), $updateData);

        $response->assertOk()
            ->assertJson([
                'testimony' => [
                    'name' => 'María José García-López',
                ],
            ]);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'María José García-López',
        ]);
    }

    public function test_it_updates_only_provided_fields(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $updateData = [
            'name' => 'Juan Carlos Pérez',
        ];

        $this->putJson(route('testimonies.update', $testimony), $updateData);

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Carlos Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);
    }

    public function test_it_accepts_empty_update_data(): void
    {
        $testimony = Testimony::factory()->create([
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);

        $response = $this->putJson(route('testimonies.update', $testimony), []);

        $response->assertOk();

        $this->assertDatabaseHas('testimonies', [
            'id' => $testimony->id,
            'name' => 'Juan Pérez',
            'content' => 'Contenido original',
            'rating' => 3,
        ]);
    }
} 