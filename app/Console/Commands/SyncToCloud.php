<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class SyncToCloud extends Command
{
    protected $signature = 'sync:cloud';
    protected $description = 'Sync local data to cloud server';

    public function handle()
    {
        if (!config('sync.enabled')) {
            $this->info('Cloud sync is disabled.');
            return;
        }

        $cloudUrl = config('sync.cloud_url');
        $token = config('sync.api_token');

        if (!$cloudUrl || !$token) {
            $this->error('Cloud URL or Token not configured.');
            return;
        }

        $this->info('Starting sync to ' . $cloudUrl);

        // 1. Sync Orders (with Items)
        $this->syncOrders($cloudUrl, $token);

        // 2. Sync Expenses
        $this->syncExpenses($cloudUrl, $token);

        // 3. Sync Items (Master Data)
        $this->syncItems($cloudUrl, $token);

        $this->info('Sync completed.');
    }

    private function syncOrders($url, $token)
    {
        // Get unsynced or updated orders (For simplicity, let's sync last 24 hours or flag based)
        // Ideally, we should have a 'synced_at' column.
        // Here we will just pick orders updated in last hour for demo.
        $orders = Order::with('orderItems')
            ->where('updated_at', '>=', now()->subHour())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No new orders to sync.');
            return;
        }

        $response = Http::withToken($token)->post("$url/api/sync/orders", [
            'orders' => $orders->toArray()
        ]);

        if ($response->successful()) {
            $this->info('Synced ' . $orders->count() . ' orders.');
        } else {
            $this->error('Failed to sync orders: ' . $response->body());
            Log::error('Sync Orders Failed', ['response' => $response->body()]);
        }
    }

    private function syncExpenses($url, $token)
    {
        // Similar logic for expenses
        // Assuming endpoint exists on cloud
    }

    private function syncItems($url, $token)
    {
        $items = Item::where('updated_at', '>=', now()->subHour())->get();

        if ($items->isEmpty()) {
            return;
        }

        $response = Http::withToken($token)->post("$url/api/sync/items", [
            'items' => $items->toArray()
        ]);

        if ($response->successful()) {
            $this->info('Synced ' . $items->count() . ' items.');
        } else {
            $this->error('Failed to sync items.');
        }
    }
}
