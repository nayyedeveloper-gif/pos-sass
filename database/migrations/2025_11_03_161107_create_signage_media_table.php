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
        Schema::create('signage_media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_mm')->nullable();
            $table->enum('type', ['video', 'image'])->default('image');
            $table->string('file_path');
            $table->integer('duration')->default(10); // seconds for images, auto for videos
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_media');
    }
};
