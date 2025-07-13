<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Missionary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionaryUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_missionary_successfully(): void
    {
        $missionary = Missionary::factory()->create([
            'title' => 'Misión Original',
            'message' => 'Mensaje original',
            'image' => 'https://example.com/original.jpg',
            'disable_at' => '2024-06-01 00:00:00',
        ]);

        $updateData = [
            'title' => 'Misión Actualizada',
            'message' => 'Mensaje actualizado',
            'image' => 'https://example.com/updated.jpg',
            'disable_at' => '2024-12-31 23:59:59',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The missionary was updated correctly',
                ],
            ]);

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'title' => 'Misión Actualizada',
            'message' => 'Mensaje actualizado',
            'image' => 'https://example.com/updated.jpg',
        ]);
    }

    public function test_it_updates_missionary_partially(): void
    {
        $missionary = Missionary::factory()->create([
            'title' => 'Misión Original',
            'message' => 'Mensaje original',
            'image' => 'https://example.com/original.jpg',
            'disable_at' => '2024-06-01 00:00:00',
        ]);

        $updateData = [
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
            'image' => 'https://example.com/original.jpg',
            'disable_at' => '2024-06-01 00:00:00',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk()
            ->assertJson([
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The missionary was updated correctly',
                ],
            ]);

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
            'image' => 'https://example.com/original.jpg',
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        $missionary = Missionary::factory()->create();

        $updateData = [
            'title' => 123,
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $missionary = Missionary::factory()->create();

        $updateData = [
            'title' => str_repeat('a', 256),
            'message' => str_repeat('b', 2001),
            'image' => str_repeat('c', 256),
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'message', 'image']);
    }

    public function test_it_validates_disable_at_date_format(): void
    {
        $missionary = Missionary::factory()->create();

        $updateData = [
            'disable_at' => 'invalid-date',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['disable_at']);
    }

    public function test_it_can_set_disable_at_to_null(): void
    {
        $missionary = Missionary::factory()->create([
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $updateData = [
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
            'image' => 'https://example.com/original.jpg',
            'disable_at' => null,
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk();

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'disable_at' => null,
        ]);
    }
}
