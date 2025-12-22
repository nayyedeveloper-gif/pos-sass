<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-transactions {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all transactional data (Orders, Expenses) but keep Master data (Users, Items, Tables)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('အရောင်းအ၀ယ်မှတ်တမ်းများနှင့် အသုံးစရိတ်များအားလုံးကို ဖျက်ပစ်မှာ သေချာပါသလား? (ဝန်ထမ်းများ၊ ပစ္စည်းများနှင့် စားပွဲများ ကျန်ရှိနေပါမည်)', true)) {
            
            $this->info('Data များကို ရှင်းလင်းနေပါသည်...');

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Transactional Tables
            $tables = [
                'order_items',
                'orders',
                'expenses',
                'notifications', // If you have notifications
                // 'activity_log' // If you have activity logs
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    $this->line("- Truncated table: {$table}");
                }
            }

            // Reset Table Statuses to 'available'
            if (Schema::hasTable('tables')) {
                DB::table('tables')->update(['status' => 'available']);
                $this->line("- Reset table statuses to 'available'");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('✅ အောင်မြင်ပါသည်။ Data များအားလုံး ရှင်းလင်းပြီးပါပြီ။');
            
            // Clear Cache
            $this->call('cache:clear');
            $this->call('view:clear');
            $this->call('config:clear');
        }
    }
}
