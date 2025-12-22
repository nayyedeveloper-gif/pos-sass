<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncDataToCloud;
use App\Models\Order;
use App\Models\Item;
use App\Models\Setting;

class SyncToCloudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cloud {--type= : Type to sync (orders,items,settings,all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync local data to cloud server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?: 'all';

        $this->info("Starting cloud sync for: {$type}");

        if ($type === 'orders' || $type === 'all') {
            $orders = Order::where('updated_at', '>', now()->subMinutes(5))->get()->toArray();
            if (!empty($orders)) {
                SyncDataToCloud::dispatch($orders, 'orders');
                $this->info("Queued " . count($orders) . " orders for sync");
            }
        }

        if ($type === 'items' || $type === 'all') {
            $items = Item::where('updated_at', '>', now()->subMinutes(5))->get()->toArray();
            if (!empty($items)) {
                SyncDataToCloud::dispatch($items, 'items');
                $this->info("Queued " . count($items) . " items for sync");
            }
        }

        if ($type === 'settings' || $type === 'all') {
            $settings = Setting::where('updated_at', '>', now()->subMinutes(5))->get()->toArray();
            if (!empty($settings)) {
                SyncDataToCloud::dispatch($settings, 'settings');
                $this->info("Queued " . count($settings) . " settings for sync");
            }
        }

        $this->info('Cloud sync completed successfully!');
    }
}
