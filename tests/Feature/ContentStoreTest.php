<?php

namespace Tests\Feature;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_content_successfully(): void
    {
        $contentData = [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertCreated()
            ->assertJson([
                'content' => [
                    'type' => 'blog',
                    'title' => 'Mi primer blog post',
                    'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
                    'image' => 'https://example.com/blog-image.jpg',
                ],
                'message' => 'The content was created correctly',
            ]);

        $this->assertDatabaseHas('contents', [
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson(route('contents.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }

    public function test_it_validates_string_fields(): void
    {
        $contentData = [
            'type' => 123,
            'title' => 'Título válido',
            'description' => 'Descripción válida',
            'image' => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type']);
    }

    public function test_it_validates_field_maximum_lengths(): void
    {
        $contentData = [
            'type' => str_repeat('a', 256),
            'title' => str_repeat('b', 256),
            'description' => str_repeat('c', 2001),
            'image' => str_repeat('d', 256),
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'title', 'description', 'image']);
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $contentData = [
            'type' => 'video',
            'title' => 'Video tutorial',
            'description' => 'Un video tutorial muy útil.',
            'image' => 'https://example.com/video-thumbnail.jpg',
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertCreated()
            ->assertJsonStructure([
                'content' => [
                    'type',
                    'title',
                    'description',
                    'image',
                ],
                'message'
            ]);
    }

    public function test_it_returns_404_for_invalid_route(): void
    {
        $response = $this->postJson('/api/invalid-route');

        $response->assertNotFound();
    }

    public function test_it_handles_special_characters_in_fields(): void
    {
        $contentData = [
            'type' => 'artículo',
            'title' => 'Artículo con caracteres especiales: ñáéíóú',
            'description' => 'Descripción con caracteres especiales y símbolos únicos.',
            'image' => 'https://example.com/artículo-especial.jpg',
        ];

        $response = $this->postJson(route('contents.store'), $contentData);

        $response->assertCreated()
            ->assertJson([
                'content' => [
                    'type' => 'artículo',
                    'title' => 'Artículo con caracteres especiales: ñáéíóú',
                    'description' => 'Descripción con caracteres especiales y símbolos únicos.',
                    'image' => 'https://example.com/artículo-especial.jpg',
                ],
            ]);
    }

    public function test_it_handles_different_content_types(): void
    {
        $contentTypes = ['blog', 'video', 'podcast', 'article', 'news'];

        foreach ($contentTypes as $type) {
            $contentData = [
                'type' => $type,
                'title' => "Contenido de tipo {$type}",
                'description' => "Descripción para contenido de tipo {$type}.",
                'image' => "https://example.com/{$type}-image.jpg",
            ];

            $response = $this->postJson(route('contents.store'), $contentData);

            $response->assertCreated();

            $this->assertDatabaseHas('contents', [
                'type' => $type,
                'title' => "Contenido de tipo {$type}",
            ]);
        }
    }
} 