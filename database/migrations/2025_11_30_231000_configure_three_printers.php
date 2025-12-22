<?php

use App\Models\Printer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // 0. Change 'type' column to string to support new types
        // Using raw SQL to avoid doctrine/dbal dependency
        \DB::statement("ALTER TABLE printers MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'receipt'");

        // 1. Restore 'kitchen' printer to default kitchen IP/Name
        // Assuming there is only one printer with type='kitchen' currently (which we renamed to Nan Pyar)
        // Or we might have multiple. Let's find by type.
        
        $kitchenPrinter = Printer::where('type', 'kitchen')->first();
        
        if ($kitchenPrinter) {
            // This was potentially renamed to Nan Pyar in previous migration.
            // We want to revert it to be the actual Kitchen printer.
            $kitchenPrinter->update([
                'name' => 'Kitchen Printer (Food)',
                'ip_address' => '192.168.0.88',
            ]);
        } else {
            // If for some reason it's missing, create it
            Printer::create([
                'name' => 'Kitchen Printer (Food)',
                'type' => 'kitchen',
                'connection_type' => 'network',
                'ip_address' => '192.168.0.88',
                'port' => 9100,
                'is_active' => true,
                'paper_width' => 80,
            ]);
        }
        
        // 2. Create/Ensure 'nan_pyar' printer exists
        $nanPyarPrinter = Printer::where('type', 'nan_pyar')->first();
        if (!$nanPyarPrinter) {
            Printer::create([
                'name' => 'Nan Pyar Printer',
                'type' => 'nan_pyar',
                'connection_type' => 'network',
                'ip_address' => '192.168.0.66',
                'port' => 9100,
                'is_active' => true,
                'paper_width' => 80,
            ]);
        } else {
            $nanPyarPrinter->update([
                'ip_address' => '192.168.0.66',
            ]);
        }
        
        // 3. Ensure Bar printer is correct
        $barPrinter = Printer::where('type', 'Bar')->first();
        if ($barPrinter) {
            $barPrinter->update([
                'ip_address' => '192.168.0.77',
            ]);
        } else {
            Printer::create([
                'name' => 'Bar Printer',
                'type' => 'Bar',
                'connection_type' => 'network',
                'ip_address' => '192.168.0.77',
                'port' => 9100,
                'is_active' => true,
                'paper_width' => 80,
            ]);
        }
    }

    public function down()
    {
        // Remove nan_pyar printer
        Printer::where('type', 'nan_pyar')->delete();
        
        // Revert kitchen printer (optional, but strictly speaking down should reverse up)
        // In this context, we'll leave it as we probably want to keep the config clean.
    }
};
