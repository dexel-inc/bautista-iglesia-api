<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;
use App\Models\Missionary;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class MissionaryUpdateTest extends BaseTestCase
{
    public function test_it_updates_missionary_successfully(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create([
            'title' => 'Misión Original',
            'message' => 'Mensaje original',
            'image' => 'missionary/images/original.jpg',
            'disable_at' => '2024-06-01 00:00:00',
        ]);

        $updateData = [
            'title' => 'Misión Actualizada',
            'message' => 'Mensaje actualizado',
            'image' => UploadedFile::fake()->image('updated.jpg'),
            'disable_at' => '2024-12-31 23:59:59',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $missionary->id,
                ],
            ]);

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'title' => 'Misión Actualizada',
            'message' => 'Mensaje actualizado',
        ]);

        // Verificamos que se guardó una imagen
        $updatedMissionary = $missionary->fresh();
        $this->assertNotNull($updatedMissionary->image);
        $this->assertStringContainsString('missionary/images/', $updatedMissionary->image);
    }

    public function test_it_updates_missionary_partially(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create([
            'title' => 'Misión Original',
            'message' => 'Mensaje original',
            'image' => 'missionary/images/original.jpg',
            'disable_at' => '2024-06-01 00:00:00',
        ]);

        $updateData = [
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
            'image' => UploadedFile::fake()->image('original.jpg'),
            'disable_at' => '2024-06-01 00:00:00',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ],
                'data' => [
                    'id' => $missionary->id,
                ],
            ]);

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
        ]);

        // Verificamos que se guardó una imagen
        $updatedMissionary = $missionary->fresh();
        $this->assertNotNull($updatedMissionary->image);
        $this->assertStringContainsString('missionary/images/', $updatedMissionary->image);
    }

    public function test_it_validates_string_fields(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create();

        $updateData = [
            'title' => 123,
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create();

        $updateData = [
            'title' => str_repeat('a', 256),
            'message' => str_repeat('b', 2001),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'message']);
    }

    public function test_it_validates_disable_at_date_format(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create();

        $updateData = [
            'disable_at' => 'invalid-date',
            'image' => UploadedFile::fake()->image('image.jpg'),
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['disable_at']);
    }

    public function test_it_can_set_disable_at_to_null(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create([
            'disable_at' => '2024-12-31 23:59:59',
        ]);

        $updateData = [
            'title' => 'Misión Parcialmente Actualizada',
            'message' => 'Mensaje original',
            'image' => UploadedFile::fake()->image('original.jpg'),
            'disable_at' => null,
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertOk();

        $this->assertDatabaseHas('missionaries', [
            'id' => $missionary->id,
            'disable_at' => null,
        ]);
    }

    public function test_it_validates_image_file_type(): void
    {
        $this->actingAsUser();

        Storage::fake('local');

        $this->actingAsUser();

        $missionary = Missionary::factory()->create();

        $updateData = [
            'title' => 'Misión válida',
            'message' => 'Mensaje válido',
            'image' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
            'disable_at' => '2024-12-31 23:59:59',
        ];

        $response = $this->putJson(route('missionaries.update', $missionary), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    }
}
