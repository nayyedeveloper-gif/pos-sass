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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['diamond', 'gold', 'platinum']);
            $table->string('name');
            $table->string('name_mm')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            
            // Common fields
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Type-specific fields stored as JSON
            // Diamond: carat, clarity, color, cut, certificate_number, certificate_file
            // Gold: weight, purity, karat, hallmark, manufacturer
            // Platinum: weight, purity, hallmark, manufacturer, serial_number
            $table->json('type_data')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
