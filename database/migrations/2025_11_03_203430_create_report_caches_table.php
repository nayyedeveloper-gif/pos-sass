<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Report caches for performance
        Schema::create('report_caches', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // sales_by_item, sales_by_category, etc.
            $table->date('report_date');
            $table->json('data');
            $table->timestamps();
            
            $table->unique(['report_type', 'report_date']);
        });

        // Daily sales summary
        Schema::create('daily_sales_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('total_orders')->default(0);
            $table->decimal('gross_sales', 10, 2)->default(0);
            $table->decimal('discounts', 10, 2)->default(0);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('service_charges', 10, 2)->default(0);
            $table->decimal('net_sales', 10, 2)->default(0);
            $table->integer('dine_in_orders')->default(0);
            $table->integer('takeaway_orders')->default(0);
            $table->decimal('cash_payments', 10, 2)->default(0);
            $table->decimal('card_payments', 10, 2)->default(0);
            $table->decimal('mobile_payments', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales_summaries');
        Schema::dropIfExists('report_caches');
    }
};
