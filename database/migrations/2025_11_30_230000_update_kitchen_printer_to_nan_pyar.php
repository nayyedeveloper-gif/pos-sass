<?php

use App\Models\Printer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Find the kitchen printer
        $kitchenPrinter = Printer::where('type', 'kitchen')->first();
        
        if ($kitchenPrinter) {
            $kitchenPrinter->update([
                'name' => 'Nan Pyar Printer',
                'ip_address' => '192.168.0.66',
            ]);
        } else {
            // Create if not exists
            Printer::create([
                'name' => 'Nan Pyar Printer',
                'type' => 'kitchen',
                'connection_type' => 'network',
                'ip_address' => '192.168.0.66',
                'port' => 9100,
                'is_active' => true,
                'paper_width' => 80,
            ]);
        }
        
        // Update the Receipt printer if it conflicts or if user wanted to change "Cashier Printer place"
        // But assuming the user just wanted to use the 192.168.0.66 IP for Nan Pyar.
        // If Receipt printer is also 192.168.0.66, it's fine, they share the IP.
    }

    public function down()
    {
        // Revert changes if needed
        $kitchenPrinter = Printer::where('type', 'kitchen')->first();
        if ($kitchenPrinter) {
            $kitchenPrinter->update([
                'name' => 'Kitchen Printer (Food)',
                'ip_address' => '192.168.0.88', // Revert to old default
            ]);
        }
    }
};
