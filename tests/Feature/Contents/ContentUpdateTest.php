<?php

namespace Tests\Feature\Contents;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_content_successfully(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $updateData = [
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => 'https://example.com/updated.jpg',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'content' => [
                    'type' => 'article',
                    'title' => 'Título actualizado',
                    'description' => 'Descripción actualizada',
                    'image' => 'https://example.com/updated.jpg',
                ],
                'message' => 'The content was updated correctly',
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'type' => 'article',
            'title' => 'Título actualizado',
            'description' => 'Descripción actualizada',
            'image' => 'https://example.com/updated.jpg',
        ]);
    }

    public function test_it_updates_content_partially(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $updateData = [
            'title' => 'Título parcialmente actualizado',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'content' => [
                    'title' => 'Título parcialmente actualizado',
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Título parcialmente actualizado',
            'type' => 'blog',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);
    }

    public function test_it_validates_string_fields(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'type' => 123,
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => str_repeat('d', 256),
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'title' => 'Título actualizado',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJsonStructure([
                'content' => [
                    'title',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_nonexistent_content(): void
    {
        $updateData = [
            'title' => 'Título actualizado',
        ];

        $response = $this->putJson(route('contents.update', 999), $updateData);

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_fields(): void
    {
        $content = Content::factory()->create();

        $updateData = [
            'type' => 'artículo',
            'title' => 'Título con caracteres especiales: ñáéíóú',
            'description' => 'Descripción con caracteres especiales y símbolos únicos.',
        ];

        $response = $this->putJson(route('contents.update', $content), $updateData);

        $response->assertOk()
            ->assertJson([
                'content' => [
                    'type' => 'artículo',
                    'title' => 'Título con caracteres especiales: ñáéíóú',
                    'description' => 'Descripción con caracteres especiales y símbolos únicos.',
                ],
            ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'type' => 'artículo',
            'title' => 'Título con caracteres especiales: ñáéíóú',
            'description' => 'Descripción con caracteres especiales y símbolos únicos.',
        ]);
    }

    public function test_it_updates_only_provided_fields(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $updateData = [
            'title' => 'Título actualizado',
        ];

        $this->putJson(route('contents.update', $content), $updateData);

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'title' => 'Título actualizado',
            'type' => 'blog',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);
    }

    public function test_it_accepts_empty_update_data(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);

        $response = $this->putJson(route('contents.update', $content), []);

        $response->assertOk();

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
            'type' => 'blog',
            'title' => 'Título original',
            'description' => 'Descripción original',
            'image' => 'https://example.com/original.jpg',
        ]);
    }
}
