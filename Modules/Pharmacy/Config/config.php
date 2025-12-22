<?php

return [
    'name' => 'Pharmacy',
    'description' => 'Pharmacy module for drug stores with prescription and expiry management',
    
    // Business types that use this module
    'business_types' => ['pharmacy', 'drug_store', 'clinic'],
    
    // Features specific to Pharmacy module
    'features' => [
        'prescriptions',
        'drug_database',
        'expiry_tracking',
        'batch_management',
        'controlled_substances',
        'drug_interactions',
        'patient_records',
        'insurance_claims',
        'generic_alternatives',
    ],
    
    // Roles specific to Pharmacy
    'roles' => [
        'pharmacist' => [
            'name' => 'Pharmacist',
            'name_mm' => 'ဆေးဝါးကျွမ်းကျင်',
            'permissions' => ['manage_prescriptions', 'dispense_drugs', 'view_patient_records'],
        ],
        'pharmacy_tech' => [
            'name' => 'Pharmacy Technician',
            'name_mm' => 'ဆေးဆိုင်ဝန်ထမ်း',
            'permissions' => ['view_prescriptions', 'manage_inventory', 'create_orders'],
        ],
    ],
    
    // Drug categories
    'drug_categories' => [
        'otc' => ['name' => 'Over The Counter', 'name_mm' => 'ဆရာဝန်ညွှန်ကြားချက်မလို', 'requires_prescription' => false],
        'prescription' => ['name' => 'Prescription Only', 'name_mm' => 'ဆရာဝန်ညွှန်ကြားချက်လို', 'requires_prescription' => true],
        'controlled' => ['name' => 'Controlled Substance', 'name_mm' => 'ထိန်းချုပ်ဆေးဝါး', 'requires_prescription' => true],
    ],
    
    // Expiry alert settings
    'expiry_alerts' => [
        'warning_days' => 90,  // Alert 90 days before expiry
        'critical_days' => 30, // Critical alert 30 days before expiry
    ],
];
