<?php

namespace Tests\Feature;

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_content_successfully(): void
    {
        $content = Content::factory()->create([
            'type' => 'blog',
            'title' => 'Mi primer blog post',
            'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
            'image' => 'https://example.com/blog-image.jpg',
        ]);

        $response = $this->deleteJson(route('contents.destroy', $content));

        $response->assertOk()
            ->assertJson([
                'content deleted' => [
                    'id' => $content->id,
                    'type' => 'blog',
                    'title' => 'Mi primer blog post',
                    'description' => 'Esta es la descripción de mi primer blog post con contenido increíble.',
                    'image' => 'https://example.com/blog-image.jpg',
                ],
                'message' => 'The content was deleted correctly',
            ]);

        $this->assertDatabaseMissing('contents', [
            'id' => $content->id,
        ]);
    }

    public function test_it_returns_404_for_nonexistent_content(): void
    {
        $response = $this->deleteJson(route('contents.destroy', 999));

        $response->assertNotFound();
    }

    public function test_it_returns_correct_response_structure(): void
    {
        $content = Content::factory()->create();

        $response = $this->deleteJson(route('contents.destroy', $content));

        $response->assertOk()
            ->assertJsonStructure([
                'content deleted' => [
                    'id',
                    'type',
                    'title',
                    'description',
                    'image',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }

    public function test_it_removes_content_from_database(): void
    {
        $content = Content::factory()->create();

        $this->assertDatabaseHas('contents', [
            'id' => $content->id,
        ]);

        $this->deleteJson(route('contents.destroy', $content));

        $this->assertDatabaseMissing('contents', [
            'id' => $content->id,
        ]);
    }

    public function test_it_does_not_affect_other_contents(): void
    {
        $content1 = Content::factory()->create(['title' => 'Contenido 1']);
        $content2 = Content::factory()->create(['title' => 'Contenido 2']);

        $this->deleteJson(route('contents.destroy', $content1));

        $this->assertDatabaseMissing('contents', [
            'id' => $content1->id,
        ]);

        $this->assertDatabaseHas('contents', [
            'id' => $content2->id,
            'title' => 'Contenido 2',
        ]);
    }
} 