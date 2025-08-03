<?php

namespace Tests\Feature\Missionaries;

use App\Constants\Response;
use App\Constants\Status;
use App\Jobs\SendPrayLetterJob;
use App\Mail\PrayLetterMail;
use App\Models\Missionary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTestCase;

class PrayLetterTest extends BaseTestCase
{
    public function test_it_sends_pray_letter_successfully(): void
    {
        $this->actingAsUser();
        Storage::fake('local');
        Queue::fake();
        Mail::fake();

        $missionary = Missionary::factory()->create([
            'contact_email' => 'missionary@example.com',
            'disable_at' => null,
        ]);

        $prayLetterData = [
            'subject' => 'Carta de Oración - Enero 2024',
            'description' => 'Esta es una carta de oración importante para todos los misioneros.',
            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('pray-letters.send'), $prayLetterData);

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

        Queue::assertPushed(SendPrayLetterJob::class);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->actingAsUser();
        $response = $this->postJson(route('pray-letters.send'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject', 'file']);
    }

    public function test_it_validates_string_fields(): void
    {
        $this->actingAsUser();
        Storage::fake('local');

        $prayLetterData = [
            'subject' => 123,
            'description' => 'Descripción válida',
            'file' => UploadedFile::fake()->create('pray-letter.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->postJson(route('pray-letters.send'), $prayLetterData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['subject']);
    }

    public function test_it_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('POST', route('pray-letters.send'), [
            'subject' => 'Test',
            'description' => 'Test description',
            'file' => UploadedFile::fake()->create('test.pdf', 1024, 'application/pdf'),
        ]);
    }

    public function test_job_sends_email_to_all_active_missionaries(): void
    {
        Storage::fake('local');
        Mail::fake();

        Missionary::factory()->create([
            'contact_email' => 'missionary1@example.com',
            'disable_at' => null,
        ]);

        Missionary::factory()->create([
            'contact_email' => 'missionary2@example.com',
            'disable_at' => null,
        ]);

        Missionary::factory()->create([
            'contact_email' => 'disabled@example.com',
            'disable_at' => now(),
        ]);

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertSent(PrayLetterMail::class);
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_no_missionaries(): void
    {
        Storage::fake('local');
        Mail::fake();

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }

    public function test_job_handles_missionaries_without_email(): void
    {
        Storage::fake('local');
        Mail::fake();

        Missionary::factory()->create([
            'contact_email' => null,
            'disable_at' => null,
        ]);

        $filePath = 'pray-letters/test.pdf';
        Storage::disk('local')->put($filePath, 'test content');

        $job = new SendPrayLetterJob('Test Subject', 'Test Description', $filePath);
        $job->handle();

        Mail::assertNothingSent();
        $this->assertFalse(Storage::disk('local')->exists($filePath));
    }
}
