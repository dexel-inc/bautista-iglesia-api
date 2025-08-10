<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Helpers\FilesHelper;
use App\Mail\NewsLetterMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsLetterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private const BATCH_SIZE = 50;

    public function __construct(
        private readonly string $subject,
        private readonly ?string $description = null,
        private readonly ?string $filePath = null
    ) {}

    public function handle(): void
    {
        try {
            $subscriptions = Subscription::whereNull('disabled_at')->get();

            if ($subscriptions->isEmpty()) {
                Log::warning('No active subscriptions found to send newsletter');
                return;
            }

            $emails = $subscriptions->pluck('email')->filter()->toArray();

            if (empty($emails)) {
                Log::warning('No valid emails found for subscriptions');
                return;
            }

            $emailBatches = array_chunk($emails, self::BATCH_SIZE);
            $totalBatches = count($emailBatches);
            $totalEmails = count($emails);

            Log::info("Sending newsletter to {$totalEmails} subscribers in {$totalBatches} batches");

            foreach ($emailBatches as $index => $batch) {
                Mail::to(config('mail.from.address'))
                    ->bcc($batch)
                    ->send(new NewsLetterMail($this->description, $this->filePath, $this->subject));

                Log::info("Newsletter batch " . ($index + 1) . "/{$totalBatches} sent to " . count($batch) . " subscribers");
            }

            Log::info("Newsletter sent successfully to {$totalEmails} subscribers in {$totalBatches} batches");

        } catch (\Exception $e) {
            Log::error('Failed to send newsletter: ' . $e->getMessage());
            throw $e;
        } finally {
            if ($this->filePath) {
                FilesHelper::delete($this->filePath);
            }
        }
    }
}
