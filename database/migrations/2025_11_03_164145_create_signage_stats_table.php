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
        Schema::create('signage_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_views')->default(0);
            $table->integer('category_rotations')->default(0);
            $table->integer('media_displays')->default(0);
            $table->integer('total_uptime_minutes')->default(0);
            $table->json('popular_categories')->nullable();
            $table->json('media_views')->nullable();
            $table->timestamps();
            
            $table->unique('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_stats');
    }
};
