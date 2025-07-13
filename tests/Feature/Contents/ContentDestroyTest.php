<?php

namespace Tests\Feature\Contents;

use App\Constants\Response;
use App\Constants\Status;
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
                'body' => [
                    'status' => Status::OK,
                    'reason' => Response::HTTP_OK,
                    'message' => 'The content was deleted correctly',
                ],
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
}
