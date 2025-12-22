<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Table;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Adding tables 1-20 to database...\n";

// Add tables 1-20
for ($i = 1; $i <= 20; $i++) {
    try {
        // Check if table already exists
        $existingTable = Table::where('table_number', $i)->first();
        
        if (!$existingTable) {
            Table::create([
                'table_number' => $i,
                'status' => 'available',
                'capacity' => 4, // Default capacity of 4 people
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Table {$i}: Created successfully\n";
        } else {
            echo "Table {$i}: Already exists, skipping\n";
        }
    } catch (Exception $e) {
        echo "Table {$i}: Error - " . $e->getMessage() . "\n";
    }
}

echo "\nProcess completed!\n";
echo "Total tables in database: " . Table::count() . "\n";
