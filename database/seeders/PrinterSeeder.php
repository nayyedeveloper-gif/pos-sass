<?php

namespace Database\Seeders;

use App\Models\Printer;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    public function run(): void
    {
        $printers = [
            [
                'name' => 'Cashier Receipt Printer',
                'type' => 'receipt',
                'connection_type' => 'network',
                'ip_address' => env('RECEIPT_PRINTER_IP', '192.168.0.66'),
                'port' => env('RECEIPT_PRINTER_PORT', 9100),
                'is_active' => true, // Enabled by default
                'paper_width' => 80,
            ],
            [
                'name' => 'Kitchen Printer (Food)',
                'type' => 'kitchen',
                'connection_type' => 'network',
                'ip_address' => env('KITCHEN_PRINTER_IP', '192.168.0.77'),
                'port' => env('KITCHEN_PRINTER_PORT', 9100),
                'is_active' => true, // Enabled by default
                'paper_width' => 80,
            ],
            [
                'name' => 'Bar Printer (Beverages)',
                'type' => 'Bar',
                'connection_type' => 'network',
                'ip_address' => env('BAR_PRINTER_IP', '192.168.0.88'),
                'port' => env('BAR_PRINTER_PORT', 9100),
                'is_active' => true, // Enabled by default
                'paper_width' => 80,
            ],
        ];

        foreach ($printers as $printer) {
            Printer::create($printer);
        }
    }
}
