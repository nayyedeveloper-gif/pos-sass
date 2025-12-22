<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change printer_type to string/varchar to support 'nan_pyar' and future types
        // Using raw SQL to avoid Doctrine DBAL dependency issues and handle enum change
        DB::statement("ALTER TABLE categories MODIFY COLUMN printer_type VARCHAR(50) NOT NULL DEFAULT 'none'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum (optional, but good practice to define)
        // Note: If there are values other than the enum set, this might fail or truncate.
        // We'll just set it back to string for now or leave it as is in a real revert scenario,
        // but strictly speaking we should revert to enum if possible.
        // However, reverting to a restrictive enum is risky if new data exists.
        // For safety in this specific project context, we will attempt to revert.
        
        // Cleaning up 'nan_pyar' data before reverting would be necessary in a strict revert.
        // Here we will just modify it back to the enum definition if possible.
        DB::statement("ALTER TABLE categories MODIFY COLUMN printer_type ENUM('kitchen', 'bar', 'none') NOT NULL DEFAULT 'none'");
    }
};
