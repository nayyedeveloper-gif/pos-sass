<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Item;
use App\Models\Setting;

class SyncDataToCloud implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $type;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, string $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $cloudUrl = config('app.cloud_url'); // Set in config
            $apiToken = config('app.cloud_api_token');

            if (!$cloudUrl || !$apiToken) {
                Log::warning('Cloud sync skipped: missing config');
                return;
            }

            $response = Http::withToken($apiToken)
                ->timeout(30)
                ->post("{$cloudUrl}/api/sync/{$this->type}", [
                    $this->type => $this->data
                ]);

            if ($response->successful()) {
                Log::info("Cloud sync successful for {$this->type}", [
                    'count' => count($this->data),
                    'response' => $response->json()
                ]);
            } else {
                Log::error("Cloud sync failed for {$this->type}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                // Could retry or notify admin
            }
        } catch (\Exception $e) {
            Log::error("Cloud sync exception for {$this->type}", [
                'error' => $e->getMessage()
            ]);
        }
    }
}
