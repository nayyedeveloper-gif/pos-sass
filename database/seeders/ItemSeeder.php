<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Nan Pyar / Paratha (နံပြား/ပလာတာ/ အီကြာ)
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Nan Pyar', 'name_mm' => 'နံပြား', 'price' => 500],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Palata', 'name_mm' => 'ပလာတာ', 'price' => 1000],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Egg Palata', 'name_mm' => 'ကြက်ဥပလာတာ', 'price' => 1500],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'E Kyar Kway', 'name_mm' => 'အီကြာကွေး', 'price' => 500],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Pe Byouk', 'name_mm' => 'ပဲပြုတ်', 'price' => 500],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Butter Naan', 'name_mm' => 'ထောပတ်နံပြား', 'price' => 1000],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Mutton Curry', 'name_mm' => 'ဆိတ်သားဟင်း', 'price' => 3000],
            ['category' => 'Nan Pyar / Paratha', 'name' => 'Pe Palata', 'name_mm' => 'ပဲပလာတာ', 'price' => 1200],

            // Foods (အစားအစာများ)
            ['category' => 'Foods', 'name' => 'Mohinga', 'name_mm' => 'မုန့်ဟင်းခါး', 'price' => 2000],
            ['category' => 'Foods', 'name' => 'Shan Noodles', 'name_mm' => 'ရှမ်းခေါက်ဆွဲ', 'price' => 2500],
            ['category' => 'Foods', 'name' => 'Nan Gyi Thoke', 'name_mm' => 'နန်းကြီးသုပ်', 'price' => 2500],
            ['category' => 'Foods', 'name' => 'Fried Rice', 'name_mm' => 'ထမင်းကြော်', 'price' => 3500],
            ['category' => 'Foods', 'name' => 'Chicken Rice', 'name_mm' => 'ကြက်ဆီထမင်း', 'price' => 4000],
            ['category' => 'Foods', 'name' => 'Fried Noodles', 'name_mm' => 'ခေါက်ဆွဲကြော်', 'price' => 3000],
            ['category' => 'Foods', 'name' => 'Chicken Wings', 'name_mm' => 'ကြက်သားတောင်ပံ', 'price' => 4000],
            ['category' => 'Foods', 'name' => 'French Fries', 'name_mm' => 'အာလူးကြော်', 'price' => 2500],
            ['category' => 'Foods', 'name' => 'Samosa', 'name_mm' => 'ဆမူဆာ', 'price' => 1500],
            ['category' => 'Foods', 'name' => 'Spring Rolls', 'name_mm' => 'ကော်ပြန့်', 'price' => 3000],
            ['category' => 'Foods', 'name' => 'Pork Stick', 'name_mm' => 'ဝက်သားတုတ်ထိုး', 'price' => 500],

            // Drinks (သောက်စရာများ)
            ['category' => 'Drinks', 'name' => 'Myanmar Tea', 'name_mm' => 'လက်ဖက်ရည်', 'price' => 1000],
            ['category' => 'Drinks', 'name' => 'Coffee', 'name_mm' => 'ကော်ဖီ', 'price' => 1000],
            ['category' => 'Drinks', 'name' => 'Black Coffee', 'name_mm' => 'ကော်ဖီမဲ', 'price' => 1000],
            ['category' => 'Drinks', 'name' => 'Iced Coffee', 'name_mm' => 'ကော်ဖီအေး', 'price' => 1500],
            ['category' => 'Drinks', 'name' => 'Lemon Tea', 'name_mm' => 'သံပုရာလက်ဖက်ရည်', 'price' => 1500],
            ['category' => 'Drinks', 'name' => 'Lime Juice', 'name_mm' => 'သံပုရာရည်', 'price' => 1500],
            ['category' => 'Drinks', 'name' => 'Orange Juice', 'name_mm' => 'လိမ္မော်ရည်', 'price' => 2000],
            ['category' => 'Drinks', 'name' => 'Strawberry Smoothie', 'name_mm' => 'စတော်ဘယ်ရီဖျော်ရည်', 'price' => 2500],
            ['category' => 'Drinks', 'name' => 'Avocado Smoothie', 'name_mm' => 'ထောပတ်သီးဖျော်ရည်', 'price' => 3000],
            ['category' => 'Drinks', 'name' => 'Cola', 'name_mm' => 'ကိုလာ', 'price' => 1000],
        ];

        foreach ($items as $itemData) {
            $category = Category::where('name', $itemData['category'])->first();
            
            if ($category) {
                Item::updateOrCreate(
                    ['name' => $itemData['name']],
                    [
                        'category_id' => $category->id,
                        'name_mm' => $itemData['name_mm'],
                        'price' => $itemData['price'],
                        'is_available' => true,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
