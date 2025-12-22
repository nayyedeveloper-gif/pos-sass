<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class MenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Item::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get or create categories
        $categories = [
            'food' => Category::firstOrCreate(
                ['name' => 'Food'],
                [
                    'name_mm' => 'အစားအစာ',
                    'description' => 'Food items',
                    'printer_type' => 'kitchen',
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            ),
            'rice' => Category::firstOrCreate(
                ['name' => 'Rice Dishes'],
                [
                    'name_mm' => 'ထမင်းဟင်းလျာ',
                    'description' => 'Rice and curry dishes',
                    'printer_type' => 'kitchen',
                    'is_active' => true,
                    'sort_order' => 2,
                ]
            ),
            'noodles' => Category::firstOrCreate(
                ['name' => 'Noodles'],
                [
                    'name_mm' => 'ခေါက်ဆွဲ',
                    'description' => 'Noodle dishes',
                    'printer_type' => 'kitchen',
                    'is_active' => true,
                    'sort_order' => 3,
                ]
            ),
            'salads' => Category::firstOrCreate(
                ['name' => 'Salads'],
                [
                    'name_mm' => 'သုပ်',
                    'description' => 'Myanmar salads',
                    'printer_type' => 'kitchen',
                    'is_active' => true,
                    'sort_order' => 4,
                ]
            ),
            'snacks' => Category::firstOrCreate(
                ['name' => 'Snacks'],
                [
                    'name_mm' => 'ငုတ်သုပ်',
                    'description' => 'Snacks and appetizers',
                    'printer_type' => 'kitchen',
                    'is_active' => true,
                    'sort_order' => 5,
                ]
            ),
            'beverages' => Category::firstOrCreate(
                ['name' => 'Beverages'],
                [
                    'name_mm' => 'အဖျော်ယမကာ',
                    'description' => 'Drinks and beverages',
                    'printer_type' => 'Bar',
                    'is_active' => true,
                    'sort_order' => 6,
                ]
            ),
            'tea' => Category::firstOrCreate(
                ['name' => 'Tea'],
                [
                    'name_mm' => 'လက်ဖက်ရည်',
                    'description' => 'Tea varieties',
                    'printer_type' => 'Bar',
                    'is_active' => true,
                    'sort_order' => 7,
                ]
            ),
            'coffee' => Category::firstOrCreate(
                ['name' => 'Coffee'],
                [
                    'name_mm' => 'ကော်ဖီ',
                    'description' => 'Coffee varieties',
                    'printer_type' => 'Bar',
                    'is_active' => true,
                    'sort_order' => 8,
                ]
            ),
            'juice' => Category::firstOrCreate(
                ['name' => 'Juice & Drinks'],
                [
                    'name_mm' => 'ဖျော်ရည်',
                    'description' => 'Fresh juices and drinks',
                    'printer_type' => 'Bar',
                    'is_active' => true,
                    'sort_order' => 9,
                ]
            ),
            'cigarettes' => Category::firstOrCreate(
                ['name' => 'Cigarettes'],
                [
                    'name_mm' => 'စီးကရက်',
                    'description' => 'Cigarette brands',
                    'printer_type' => 'none',
                    'is_active' => true,
                    'sort_order' => 10,
                ]
            ),
        ];

        // Menu items data
        $items = [
            // Rice Dishes
            ['name' => 'Fried Rice (Chicken)', 'name_mm' => 'ထမင်းကြော် (ကြက်)', 'price' => 4500, 'category' => 'rice'],
            ['name' => 'Fried Rice (Pork)', 'name_mm' => 'ထမင်းကြော် (ဝက်)', 'price' => 4500, 'category' => 'rice'],
            ['name' => 'Rice + Chicken', 'name_mm' => 'ထမင်း + ကြက်', 'price' => 4500, 'category' => 'rice'],
            ['name' => 'Rice + Pork', 'name_mm' => 'ထမင်း+ဝက်သား', 'price' => 4500, 'category' => 'rice'],
            ['name' => 'Oil Rice', 'name_mm' => 'ဆီချက်', 'price' => 3000, 'category' => 'rice'],
            ['name' => 'Tea Leaf Rice', 'name_mm' => 'လဖက်ထမင်း', 'price' => 3500, 'category' => 'rice'],
            ['name' => 'Rice Salad', 'name_mm' => 'ထမင်းသုပ်', 'price' => 3500, 'category' => 'rice'],
            ['name' => 'Plain Rice (Pack)', 'name_mm' => 'ထမင်းဖြူ (တစ်ထုပ်)', 'price' => 1000, 'category' => 'rice'],
            ['name' => 'Plain Rice (Table)', 'name_mm' => 'ထမင်းဖြူ (စားပွဲ)', 'price' => 1500, 'category' => 'rice'],
            ['name' => 'Rice Side', 'name_mm' => 'ထမင်း လိုက်ပွဲ', 'price' => 700, 'category' => 'rice'],
            ['name' => 'Butter Rice', 'name_mm' => 'ထမင်း ဆီဆမ်း', 'price' => 3500, 'category' => 'rice'],

            // Noodles
            ['name' => 'Shan Noodles', 'name_mm' => 'ရှမ်းခေါက်ဆွဲ', 'price' => 3500, 'category' => 'noodles'],
            ['name' => 'Noodle Salad', 'name_mm' => 'ခေါ်က်ဆွဲသုပ်', 'price' => 1500, 'category' => 'noodles'],
            ['name' => 'Wheat Noodle Salad', 'name_mm' => 'ဂျုံ ခေါက်ဆွဲသုပ်', 'price' => 1500, 'category' => 'noodles'],
            ['name' => 'Mohinga (Plain)', 'name_mm' => 'မုန့်ဟင်းခါး အလွတ်', 'price' => 2000, 'category' => 'noodles'],
            ['name' => 'Mohinga', 'name_mm' => 'မုန့်ဟင်းခါး', 'price' => 2000, 'category' => 'noodles'],
            ['name' => 'Mohinga with Fried Bean', 'name_mm' => 'မုန့်ဟင်းခါး ပဲကြော်', 'price' => 2500, 'category' => 'noodles'],

            // Salads
            ['name' => 'Tea Leaf Salad', 'name_mm' => 'လက်ဖက်သုပ်', 'price' => 2500, 'category' => 'salads'],
            ['name' => 'Tomato Salad', 'name_mm' => 'ခရမ်းချဉ်သီးသုပ်', 'price' => 2000, 'category' => 'salads'],
            ['name' => 'Ginger Salad', 'name_mm' => 'ကြာဇံသုပ်', 'price' => 1500, 'category' => 'salads'],
            ['name' => 'Pennywort Salad', 'name_mm' => 'ညှပ်ဖက်သုပ်', 'price' => 1500, 'category' => 'salads'],
            ['name' => 'Nan Gyi Salad', 'name_mm' => 'နန်းကြီးသုပ်', 'price' => 4000, 'category' => 'salads'],
            ['name' => 'Nan Pyar Salad (Chicken)', 'name_mm' => 'နန်းပြားသုပ် (ကြက်)', 'price' => 4000, 'category' => 'salads'],
            ['name' => 'Nan Pyar (Plain)', 'name_mm' => 'နန်းပြား အလွတ်', 'price' => 3500, 'category' => 'salads'],
            ['name' => 'Nan Pyar Rolled', 'name_mm' => 'နန်းပြား လိပ်ခုတ်', 'price' => 2000, 'category' => 'salads'],
            ['name' => 'Nan Pyar Wrapped Salad', 'name_mm' => 'နန်းပြား အုပ်သုပ်', 'price' => 2000, 'category' => 'salads'],
            ['name' => 'Nan Pyar Tomato Salad', 'name_mm' => 'နံပြားထောပက်သုပ်', 'price' => 1500, 'category' => 'salads'],
            ['name' => 'Tomato Salad', 'name_mm' => 'ထောပက်သုပ်', 'price' => 1500, 'category' => 'salads'],
            ['name' => 'Bread Tomato Salad', 'name_mm' => 'ပေါင်မုန့် ထောပက် သုပ်', 'price' => 2500, 'category' => 'salads'],
            ['name' => 'Grilled Bread Tomato Salad', 'name_mm' => 'ပေါင်မုန့် မီးကင် ထောက်ပက်သုပ်', 'price' => 2500, 'category' => 'salads'],

            // Snacks & Appetizers
            ['name' => 'Samosa', 'name_mm' => 'စမူဆာ', 'price' => 1000, 'category' => 'snacks'],
            ['name' => 'Nan Pyar', 'name_mm' => 'နံပြား', 'price' => 1000, 'category' => 'snacks'],
            ['name' => 'Bean Bread', 'name_mm' => 'ပဲပေါင်မုန့်', 'price' => 1300, 'category' => 'snacks'],
            ['name' => 'Bean Rolled', 'name_mm' => 'ပဲလိပ်ခုတ်', 'price' => 2000, 'category' => 'snacks'],
            ['name' => 'Bean Rolled', 'name_mm' => 'ပဲ လိပ်ခုတ်', 'price' => 2500, 'category' => 'snacks'],
            ['name' => 'Fried Bean', 'name_mm' => 'ပဲကြော်', 'price' => 500, 'category' => 'snacks'],
            ['name' => 'Bean Nan Pyar', 'name_mm' => 'ပဲနံပြား', 'price' => 1500, 'category' => 'snacks'],
            ['name' => 'Fried Bean Leaves', 'name_mm' => 'ပဲရွက်ကြော်', 'price' => 2000, 'category' => 'snacks'],
            ['name' => 'Bean Ikra', 'name_mm' => 'ပဲအီကြာ', 'price' => 1500, 'category' => 'snacks'],
            ['name' => 'Egg Bread', 'name_mm' => 'ကြက်ဉပေါင်မုန့်', 'price' => 1300, 'category' => 'snacks'],
            ['name' => 'Fried Egg Bread', 'name_mm' => 'ပေါင်မုန့်ကြက်ဉကြော်', 'price' => 2500, 'category' => 'snacks'],
            ['name' => 'Fried Egg', 'name_mm' => 'ကြက်ဉ ကြော်', 'price' => 700, 'category' => 'snacks'],
            ['name' => 'Boiled Egg', 'name_mm' => 'ဆေးဘဲဉ', 'price' => 2500, 'category' => 'snacks'],
            ['name' => 'Bread (Plain)', 'name_mm' => 'ပေါင်မုန့် အလွတ်', 'price' => 800, 'category' => 'snacks'],
            ['name' => 'Pork Bread', 'name_mm' => 'ဝက်သား ပေါင်မုန့်', 'price' => 1300, 'category' => 'snacks'],
            ['name' => 'Milk Bread', 'name_mm' => 'ပေါင်မုန့် နို့ဆမ်း', 'price' => 2500, 'category' => 'snacks'],
            ['name' => 'Fried Snack', 'name_mm' => 'အကြော်', 'price' => 500, 'category' => 'snacks'],
            ['name' => 'Ikra Kwe', 'name_mm' => 'အီကြာကွေး', 'price' => 1000, 'category' => 'snacks'],
            ['name' => 'Ikra Kwe', 'name_mm' => 'အီကြာကွေး', 'price' => 1000, 'category' => 'snacks'],
            ['name' => 'Pot Bean Egg', 'name_mm' => 'အိုးပဲ ဉ', 'price' => 1000, 'category' => 'snacks'],
            ['name' => 'Meat Mix', 'name_mm' => 'အသားပေါင်း', 'price' => 1700, 'category' => 'snacks'],
            ['name' => 'Meat Mix', 'name_mm' => 'အသားပေါင်း', 'price' => 1500, 'category' => 'snacks'],
            ['name' => 'Ahara', 'name_mm' => 'အဟာရ', 'price' => 2500, 'category' => 'snacks'],
            ['name' => 'Fork Bottle', 'name_mm' => 'ဖော့ဗူး', 'price' => 200, 'category' => 'snacks'],

            // Specialty Dishes
            ['name' => 'Kap Gyi Kaik (Plain)', 'name_mm' => 'ကပ်ကြီးကိုက် အလွတ်', 'price' => 3000, 'category' => 'food'],
            ['name' => 'Kap Gyi Kaik Seafood (Small)', 'name_mm' => 'ကပ်ကြီးကိုက် ပင်လယ်စာ (သေး)', 'price' => 7000, 'category' => 'food'],
            ['name' => 'Kap Gyi Kaik Chicken (Small)', 'name_mm' => 'ကပ်ကြီးကိုက် ကြက်သား (သေး)', 'price' => 5000, 'category' => 'food'],
            ['name' => 'Kap Gyi Kaik Pork (Small)', 'name_mm' => 'ကပ်ကြီးကိုက် ဝက်သား ( သေး)', 'price' => 5000, 'category' => 'food'],
            ['name' => 'Kap Gyi Kaik Set (Large)', 'name_mm' => 'ကပ်ကြီးကိုက် အစုံ (ပွဲကြီး)', 'price' => 10000, 'category' => 'food'],
            ['name' => 'Pork Porksee', 'name_mm' => 'ဝက်ပေါက်စီ', 'price' => 2000, 'category' => 'food'],
            ['name' => 'Chicken Porksee', 'name_mm' => 'ကြက်ပေါက်စီ', 'price' => 2000, 'category' => 'food'],
            ['name' => 'Bean Porksee', 'name_mm' => 'ပဲပေါက်စီ', 'price' => 1400, 'category' => 'food'],
            ['name' => 'Bean Palata', 'name_mm' => 'ပဲပလာတာ', 'price' => 2000, 'category' => 'food'],
            ['name' => 'Chicken Lime', 'name_mm' => 'ကြက် သံပုရာ', 'price' => 2000, 'category' => 'food'],
            ['name' => 'Chicken Lime (Hot)', 'name_mm' => 'ကြက် သံပုရာ အပူ', 'price' => 2000, 'category' => 'food'],
            ['name' => 'Chicken Lime (Cold)', 'name_mm' => 'ကြက် သံပုရာ အအေး', 'price' => 2500, 'category' => 'food'],
            ['name' => 'Chicken Ka', 'name_mm' => 'ကာ ကြက်', 'price' => 700, 'category' => 'food'],
            ['name' => 'Kyay Sein', 'name_mm' => 'ကျွဲစိမ်း', 'price' => 3000, 'category' => 'food'],
            ['name' => 'Kyay Sein', 'name_mm' => 'ကျွဲစိမ်း', 'price' => 2800, 'category' => 'food'],
            ['name' => 'Aung San', 'name_mm' => 'အော်စွန်း', 'price' => 3500, 'category' => 'food'],

            // Tea
            ['name' => 'Iced Tea', 'name_mm' => 'လက်ဖက်ရည်အး', 'price' => 4000, 'category' => 'tea'],
            ['name' => 'Lemon Tea (Cold)', 'name_mm' => 'လီမွန်တီး အအေး', 'price' => 2500, 'category' => 'tea'],
            ['name' => 'Lemon Tea', 'name_mm' => 'လီမွန်တီး', 'price' => 1000, 'category' => 'tea'],
            ['name' => 'Milk Tea (Cold)', 'name_mm' => 'နို့စိမ်းတီး', 'price' => 2000, 'category' => 'tea'],
            ['name' => 'Ceylon Tea', 'name_mm' => 'စီလုံတီး', 'price' => 3500, 'category' => 'tea'],

            // Coffee
            ['name' => 'Black Coffee', 'name_mm' => 'ဘလက်အော', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Black Coffee', 'name_mm' => 'Black Coffee', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Iced Coffee', 'name_mm' => 'ကော်ဖီအေး', 'price' => 4000, 'category' => 'coffee'],
            ['name' => 'Ovaltine', 'name_mm' => 'အိုဗာတင်း', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Iced Ovaltine', 'name_mm' => 'အိုဗာတင်း အအေး', 'price' => 4000, 'category' => 'coffee'],
            ['name' => 'Singapore', 'name_mm' => 'စင်္ကာပူ', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Regular Coffee', 'name_mm' => 'ပုံမှန် ကျရည်ကဲ', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Regular', 'name_mm' => 'ပုံမှန်', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Sweet Coffee', 'name_mm' => 'ချိုကျ', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Light Coffee', 'name_mm' => 'ကျရည်ပေါ့', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Sweet Lite', 'name_mm' => 'ချိုစိမ့်', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Lite Coffee', 'name_mm' => 'ပေါ့စိမ့်', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Lite Coffee Pack (Small)', 'name_mm' => 'ပေါ့စိမ့် ပါဆယ် (သေး)', 'price' => 2000, 'category' => 'coffee'],
            ['name' => 'Lite Coffee Pack (Large)', 'name_mm' => 'ပေါ့စိမ့် ပါဆယ် (ကြီး)', 'price' => 3300, 'category' => 'coffee'],
            ['name' => 'Kyay Sein', 'name_mm' => 'ကျစိမ့်', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Kaw Pyan Sein', 'name_mm' => 'ကော်ပြန့်စိမ်း', 'price' => 1700, 'category' => 'coffee'],
            ['name' => 'Makwhut', 'name_mm' => 'မခွပ်', 'price' => 1800, 'category' => 'coffee'],
            ['name' => 'Fan Cho', 'name_mm' => 'ဖန်ချို', 'price' => 1800, 'category' => 'coffee'],

            // Juice & Drinks
            ['name' => 'Fresh Milk', 'name_mm' => 'နွားနို့', 'price' => 2000, 'category' => 'juice'],
            ['name' => 'Iced Milk', 'name_mm' => 'နွားနို့ အေး', 'price' => 2700, 'category' => 'juice'],
            ['name' => 'Milk + Egg', 'name_mm' => 'နို့ကြက်ဥ', 'price' => 3000, 'category' => 'juice'],
            ['name' => 'Milk + Egg', 'name_mm' => 'နွားနို့ + ကြက်ဉ', 'price' => 2500, 'category' => 'juice'],
            ['name' => 'Sundae', 'name_mm' => 'ဆန်းဒေး', 'price' => 1000, 'category' => 'juice'],
            ['name' => 'Vitamin Drink', 'name_mm' => 'Vitamin drink', 'price' => 1500, 'category' => 'juice'],
            ['name' => 'Vitamin C', 'name_mm' => 'Vitamin C', 'price' => 1600, 'category' => 'juice'],
            ['name' => 'Drinking Water', 'name_mm' => 'ရေသန့်', 'price' => 1000, 'category' => 'beverages'],
            ['name' => 'Super', 'name_mm' => 'စူပါ', 'price' => 1000, 'category' => 'beverages'],
            ['name' => 'Next', 'name_mm' => 'နက်စ်', 'price' => 1000, 'category' => 'beverages'],

            // Cigarettes
            ['name' => 'Shark', 'name_mm' => 'Shark', 'price' => 2800, 'category' => 'cigarettes'],
            ['name' => 'Shark', 'name_mm' => 'Shark', 'price' => 2800, 'category' => 'cigarettes'],
            ['name' => 'Royal D', 'name_mm' => 'Royal D', 'price' => 1800, 'category' => 'cigarettes'],
            ['name' => 'Mevius', 'name_mm' => 'Mevius', 'price' => 700, 'category' => 'cigarettes'],
            ['name' => 'Winston', 'name_mm' => 'Winston', 'price' => 500, 'category' => 'cigarettes'],
            ['name' => 'Blue Mountain', 'name_mm' => 'Blue mountain', 'price' => 1600, 'category' => 'cigarettes'],
            ['name' => 'Premier', 'name_mm' => 'ပရီးမီးယား', 'price' => 1000, 'category' => 'cigarettes'],
            ['name' => 'String', 'name_mm' => 'String', 'price' => 1800, 'category' => 'cigarettes'],
            ['name' => 'Honey Gold', 'name_mm' => 'Honey Gold', 'price' => 1700, 'category' => 'cigarettes'],
            ['name' => 'Speed', 'name_mm' => 'Speed', 'price' => 1800, 'category' => 'cigarettes'],
        ];

        // Insert items
        foreach ($items as $itemData) {
            $category = $categories[$itemData['category']];
            
            Item::create([
                'category_id' => $category->id,
                'name' => $itemData['name'],
                'name_mm' => $itemData['name_mm'],
                'description' => '',
                'price' => $itemData['price'],
                'is_available' => true,
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }

        $this->command->info('✅ Successfully seeded ' . count($items) . ' menu items!');
        $this->command->info('📊 Categories: ' . count($categories));
    }
}
