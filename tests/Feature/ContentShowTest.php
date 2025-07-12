<?php

namespace Tests\Feature;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_content_successfully(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
        ]);

        $response = $this->getJson(route('contents.show', $content));

        $response->assertOk()
            ->assertJson([
                'id' => $content->id,
                'type' => 'blog',
                'title' => 'Mi primer blog post',
                'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
                'image' => 'https://example.com/blog-image.jpg',
            ]);
    }

    public function test_it_returns_404_for_nonexistent_content(): void
    {
        $response = $this->getJson(route('contents.show', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $content = Content::factory()->create();

        $response = $this->getJson(route('contents.show', $content));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'type',
                'title',
                'description',
                'image',
                'created_at',
                'updated_at',
            ]);
    }
} 