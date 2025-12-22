<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['name' => 'Table 1', 'name_mm' => 'စားပွဲ ၁', 'capacity' => 2, 'sort_order' => 1],
            ['name' => 'Table 2', 'name_mm' => 'စားပွဲ ၂', 'capacity' => 2, 'sort_order' => 2],
            ['name' => 'Table 3', 'name_mm' => 'စားပွဲ ၃', 'capacity' => 4, 'sort_order' => 3],
            ['name' => 'Table 4', 'name_mm' => 'စားပွဲ ၄', 'capacity' => 4, 'sort_order' => 4],
            ['name' => 'Table 5', 'name_mm' => 'စားပွဲ ၅', 'capacity' => 4, 'sort_order' => 5],
            ['name' => 'Table 6', 'name_mm' => 'စားပွဲ ၆', 'capacity' => 4, 'sort_order' => 6],
            ['name' => 'Table 7', 'name_mm' => 'စားပွဲ ၇', 'capacity' => 6, 'sort_order' => 7],
            ['name' => 'Table 8', 'name_mm' => 'စားပွဲ ၈', 'capacity' => 6, 'sort_order' => 8],
            ['name' => 'Table 9', 'name_mm' => 'စားပွဲ ၉', 'capacity' => 8, 'sort_order' => 9],
            ['name' => 'Table 10', 'name_mm' => 'စားပွဲ ၁၀', 'capacity' => 8, 'sort_order' => 10],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
