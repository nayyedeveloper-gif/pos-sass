<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing orders with preparing/ready status to pending
        DB::table('orders')
            ->whereIn('status', ['preparing', 'ready'])
            ->update(['status' => 'pending']);
        
        // Modify the enum to only have 3 statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Restore original statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
