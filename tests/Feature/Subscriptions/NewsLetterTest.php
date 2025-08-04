<?php

namespace Tests\Feature\Subscriptions;

use App\Constants\Response;
use App\Constants\Status;
use App\Jobs\SendNewsLetterJob;
use App\Mail\NewsLetterMail;
use App\Models\Subscription;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class NewsLetterTest extends BaseTestCase
{
    public function test_it_sends_newsletter_successfully(): void
    {
        $this->actingAsUser();
        Storage::fake('local');
        Queue::fake();
        Mail::fake();

        Subscription::factory()->create([
            'email' => 'subscriber@example.com',
            'disabled_at' => null,
        ]);

        $newsletterData = [
            'subject' => 'Newsletter - Enero 2024',
            'description' => 'Esta es una newsletter importante para todos los subscriptores.',
            'file' => UploadedFile::fake()->create('newsletter.pdf', 1000, 'application/pdf'),
        ];

        $response = $this->postJson(route('newsletters.send'), $newsletterData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status' => [
                    'status'
                ]
            ])
            ->assertJson([
                'status' => [
                    'status' => Status::OK,
                ]
            ]);

        Queue::assertPushed(SendNewsLetterJob::class);
    }

    public function test_it_sends_newsletter_without_file(): void
    {
        $this->actingAsUser();
        Queue::fake();
        Mail::fake();

        Subscription::factory()->create([
            'email' => 'subscriber@example.com',
            'disabled_at' => null,
        ]);

        $newsletterData = [
            'subject' => 'Newsletter sin archivo',
            'description' => 'Esta es una newsletter sin archivo adjunto.',
        ];

        $response = $this->postJson(route('newsletters.send'), $newsletterData);

        $response->assertStatus(Response::HTTP_OK);
        Queue::assertPushed(SendNewsLetterJob::class);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->actingAsUser();
        $response = $this->postJson(route('newsletters.send'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject', 'description']);
    }

    public function test_it_validates_string_fields(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $newsletterData = [
            'subject' => 123,
            'description' => 'Descripción válida',
            'file' => UploadedFile::fake()->create('newsletter.pdf', 1000, 'application/pdf'),
        ];

        $response = $this->postJson(route('newsletters.send'), $newsletterData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject']);
    }

    public function test_it_validates_file_types(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $newsletterData = [
            'subject' => 'Test Subject',
            'description' => 'Test description',
            'file' => UploadedFile::fake()->create('newsletter.txt', 1024, 'text/plain'),
        ];

        $response = $this->postJson(route('newsletters.send'), $newsletterData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }

    public function test_it_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('POST', route('newsletters.send'), [
            'subject' => 'Test',
            'description' => 'Test description',
        ]);
    }

    public function test_job_sends_email_to_all_active_subscriptions(): void
    {
        Storage::fake('local');
        Mail::fake();

        Subscription::factory()->create([
            'email' => 'subscriber1@example.com',
            'disabled_at' => null,
        ]);

        Subscription::factory()->create([
            'email' => 'subscriber2@example.com',
            'disabled_at' => null,
        ]);

        Subscription::factory()->create([
            'email' => 'inactive@example.com',
            'disabled_at' => now(),
        ]);

        $filePath = 'newsletters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendNewsLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertSent(NewsLetterMail::class);
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_no_subscriptions(): void
    {
        Storage::fake('local');
        Mail::fake();

        $filePath = 'newsletters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendNewsLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_subscriptions_without_email(): void
    {
        Storage::fake('local');
        Mail::fake();

        Subscription::factory()->create([
            'email' => '',
            'disabled_at' => null,
        ]);

        $filePath = 'newsletters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendNewsLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_large_subscription_list_with_batching(): void
    {
        Storage::fake('local');
        Mail::fake();

        for ($i = 1; $i <= 120; $i++) {
            Subscription::factory()->create([
                'email' => "subscriber{$i}@example.com",
                'disabled_at' => null,
            ]);
        }

        $filePath = 'newsletters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendNewsLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertSent(NewsLetterMail::class, 3);
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_newsletter_without_file(): void
    {
        Mail::fake();

        Subscription::factory()->create([
            'email' => 'subscriber@example.com',
            'disabled_at' => null,
        ]);

        $job = new SendNewsLetterJob('Test Subject', 'Test Description', null);
        $job->handle();

        Mail::assertSent(NewsLetterMail::class);
    }
}
