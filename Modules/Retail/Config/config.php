<?php

return [
    'name' => 'Retail',
    'description' => 'Retail module for general stores, grocery, mini markets',
    
    // Business types that use this module
    'business_types' => ['retail', 'grocery', 'mini_market', 'convenience_store'],
    
    // Features specific to Retail module
    'features' => [
        'barcode_scanning',
        'inventory_management',
        'stock_alerts',
        'purchase_orders',
        'suppliers',
        'price_labels',
        'batch_pricing',
        'promotions',
        'member_discounts',
    ],
    
    // Roles specific to Retail
    'roles' => [
        'sales' => [
            'name' => 'Sales Staff',
            'name_mm' => 'အရောင်းဝန်ထမ်း',
            'permissions' => ['view_items', 'create_orders', 'view_orders'],
        ],
        'inventory' => [
            'name' => 'Inventory Staff',
            'name_mm' => 'ကုန်ပစ္စည်းဝန်ထမ်း',
            'permissions' => ['manage_inventory', 'view_stock', 'create_purchase_orders'],
        ],
    ],
    
    // POS Settings
    'pos' => [
        'show_barcode_input' => true,
        'auto_focus_barcode' => true,
        'show_stock_quantity' => true,
        'allow_negative_stock' => false,
        'require_customer' => false,
    ],
];
