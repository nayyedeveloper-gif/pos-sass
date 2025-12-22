<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create table_sections table for organizing tables
        if (!Schema::hasTable('table_sections')) {
            Schema::create('table_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('name_mm')->nullable();
                $table->integer('floor')->default(1); // Floor/Level: 1, 2, 3, -1 (basement)
                $table->string('layout_size')->default('1280x620'); // Layout canvas size
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Add new columns to tables
        Schema::table('tables', function (Blueprint $table) {
            if (!Schema::hasColumn('tables', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tables', 'section_id')) {
                $table->foreignId('section_id')->nullable()->after('tenant_id');
            }
            if (!Schema::hasColumn('tables', 'floor')) {
                $table->integer('floor')->default(1)->after('section_id');
            }
            if (!Schema::hasColumn('tables', 'shape')) {
                $table->enum('shape', ['square', 'round', 'rectangle'])->default('square')->after('capacity');
            }
            if (!Schema::hasColumn('tables', 'position_x')) {
                $table->integer('position_x')->nullable()->after('shape');
            }
            if (!Schema::hasColumn('tables', 'position_y')) {
                $table->integer('position_y')->nullable()->after('position_x');
            }
            if (!Schema::hasColumn('tables', 'width')) {
                $table->integer('width')->default(100)->after('position_y');
            }
            if (!Schema::hasColumn('tables', 'height')) {
                $table->integer('height')->default(100)->after('width');
            }
            if (!Schema::hasColumn('tables', 'merged_with')) {
                $table->json('merged_with')->nullable()->after('height'); // Array of merged table IDs
            }
            if (!Schema::hasColumn('tables', 'is_merged')) {
                $table->boolean('is_merged')->default(false)->after('merged_with');
            }
            if (!Schema::hasColumn('tables', 'merge_parent_id')) {
                $table->foreignId('merge_parent_id')->nullable()->after('is_merged');
            }
            if (!Schema::hasColumn('tables', 'current_order_id')) {
                $table->foreignId('current_order_id')->nullable()->after('merge_parent_id');
            }
            if (!Schema::hasColumn('tables', 'occupied_at')) {
                $table->timestamp('occupied_at')->nullable()->after('current_order_id');
            }
            if (!Schema::hasColumn('tables', 'guest_count')) {
                $table->integer('guest_count')->nullable()->after('occupied_at');
            }
            if (!Schema::hasColumn('tables', 'waiter_id')) {
                $table->foreignId('waiter_id')->nullable()->after('guest_count');
            }
        });

        // Create table_layout_elements for barriers, labels, etc.
        if (!Schema::hasTable('table_layout_elements')) {
            Schema::create('table_layout_elements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('section_id')->nullable();
                $table->enum('type', ['barrier', 'label', 'decoration'])->default('barrier');
                $table->string('name')->nullable();
                $table->integer('position_x')->default(0);
                $table->integer('position_y')->default(0);
                $table->integer('width')->default(100);
                $table->integer('height')->default(40);
                $table->string('color')->default('#6b7280');
                $table->integer('rotation')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('table_layout_elements');
        Schema::dropIfExists('table_sections');
        
        Schema::table('tables', function (Blueprint $table) {
            $columns = ['section_id', 'floor', 'shape', 'position_x', 'position_y', 'width', 'height', 
                       'merged_with', 'is_merged', 'merge_parent_id', 'current_order_id', 
                       'occupied_at', 'guest_count', 'waiter_id'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tables', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
