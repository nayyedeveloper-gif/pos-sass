<?php

return [
    'name' => 'Restaurant',
    'description' => 'Restaurant module for F&B businesses including tables, kitchen, and reservations',
    
    // Business types that use this module
    'business_types' => ['restaurant', 'cafe', 'fast_food', 'bar'],
    
    // Features specific to Restaurant module
    'features' => [
        'tables',
        'kitchen_display',
        'reservations',
        'waiters',
        'dine_in',
        'takeaway',
        'delivery',
        'order_types',
        'table_management',
        'split_bill',
        'merge_tables',
        'course_management',
    ],
    
    // Roles specific to Restaurant
    'roles' => [
        'waiter' => [
            'name' => 'Waiter',
            'name_mm' => 'စားပွဲထိုး',
            'permissions' => ['view_tables', 'create_orders', 'view_orders'],
        ],
        'kitchen' => [
            'name' => 'Kitchen Staff',
            'name_mm' => 'မီးဖိုချောင်',
            'permissions' => ['view_kitchen_orders', 'update_order_status'],
        ],
        'bar' => [
            'name' => 'Bar Staff',
            'name_mm' => 'ဘား',
            'permissions' => ['view_bar_orders', 'update_order_status'],
        ],
    ],
    
    // Order types available
    'order_types' => [
        'dine_in' => ['name' => 'Dine In', 'name_mm' => 'စားပွဲ', 'icon' => 'utensils'],
        'takeaway' => ['name' => 'Takeaway', 'name_mm' => 'ထုပ်', 'icon' => 'shopping-bag'],
        'delivery' => ['name' => 'Delivery', 'name_mm' => 'ပို့ဆောင်', 'icon' => 'truck'],
    ],
];
