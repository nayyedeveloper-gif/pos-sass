<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['kitchen', 'Bar', 'receipt'])->default('receipt');
            $table->string('ip_address');
            $table->integer('port')->default(9100);
            $table->boolean('is_active')->default(true);
            $table->integer('paper_width')->default(80); // mm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
