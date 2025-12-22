<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'stock_quantity')) {
                $table->integer('stock_quantity')->nullable()->after('price');
            }
            if (!Schema::hasColumn('items', 'reorder_level')) {
                $table->integer('reorder_level')->nullable()->after('stock_quantity');
            }
            if (!Schema::hasColumn('items', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->nullable()->after('reorder_level');
            }
            if (!Schema::hasColumn('items', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('cost_price');
            }
            if (!Schema::hasColumn('items', 'generic_name')) {
                $table->string('generic_name')->nullable()->after('name_mm');
            }
            if (!Schema::hasColumn('items', 'requires_prescription')) {
                $table->boolean('requires_prescription')->default(false)->after('expiry_date');
            }
            if (!Schema::hasColumn('items', 'dosage')) {
                $table->string('dosage')->nullable()->after('requires_prescription');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'stock_quantity',
                'reorder_level', 
                'cost_price',
                'expiry_date',
                'generic_name',
                'requires_prescription',
                'dosage'
            ]);
        });
    }
};
