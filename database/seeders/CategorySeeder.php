<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nan Pyar / Paratha',
                'name_mm' => 'နံပြား/ပလာတာ/ အီကြာ',
                'printer_type' => 'nan_pyar',
                'sort_order' => 1,
            ],
            [
                'name' => 'Foods',
                'name_mm' => 'အစားအစာများ',
                'printer_type' => 'kitchen',
                'sort_order' => 2,
            ],
            [
                'name' => 'Drinks',
                'name_mm' => 'သောက်စရာများ',
                'printer_type' => 'bar',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']], 
                $category
            );
        }
    }
}
