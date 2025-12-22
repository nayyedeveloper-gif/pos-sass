# POS SaaS Modular Architecture

## Overview

This POS system uses a modular architecture to support different business types with their specific features and workflows.

## Module Structure

```
Modules/
├── Core/                    # Shared functionality for all business types
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   ├── Routes/
│   ├── Services/
│   ├── Livewire/
│   ├── Database/Migrations/
│   └── Providers/
│       └── CoreServiceProvider.php
│
├── Restaurant/              # Restaurant, Cafe, Fast Food, Bar
│   ├── Config/config.php
│   ├── Models/
│   │   ├── Table.php
│   │   └── Reservation.php
│   ├── Livewire/
│   │   └── TableManagement.php
│   ├── Views/livewire/
│   │   └── table-management.blade.php
│   ├── Routes/web.php
│   └── Providers/
│       └── RestaurantServiceProvider.php
│
├── Retail/                  # Retail Store, Grocery, Mini Market
│   ├── Config/config.php
│   ├── Livewire/
│   │   └── RetailPOS.php
│   ├── Views/livewire/
│   │   └── retail-pos.blade.php
│   ├── Routes/web.php
│   └── Providers/
│       └── RetailServiceProvider.php
│
└── Pharmacy/                # Pharmacy, Drug Store, Clinic
    ├── Config/config.php
    ├── Models/
    │   └── Drug.php
    ├── Livewire/
    │   └── PharmacyPOS.php
    ├── Views/livewire/
    │   └── pharmacy-pos.blade.php
    ├── Routes/web.php
    └── Providers/
        └── PharmacyServiceProvider.php
```

## Business Type to Module Mapping

| Business Type | Module | Features |
|--------------|--------|----------|
| restaurant | Restaurant | Tables, Kitchen, Reservations, Waiters, Dine-in |
| cafe | Restaurant | Tables, Kitchen, Reservations |
| fast_food | Restaurant | Kitchen, Takeaway, Delivery |
| bar | Restaurant | Tables, Bar orders |
| retail | Retail | Barcode scanning, Inventory, Simple POS |
| grocery | Retail | Barcode scanning, Inventory |
| mini_market | Retail | Barcode scanning, Inventory |
| pharmacy | Pharmacy | Prescriptions, Drug database, Expiry tracking |
| drug_store | Pharmacy | Prescriptions, Expiry tracking |
| clinic | Pharmacy | Patient records, Prescriptions |

## Helper Functions

```php
// Check if a module is active for current tenant
is_module_active('Restaurant')  // true/false

// Get all active modules for current tenant
get_active_modules()  // ['Core', 'Restaurant']

// Get module path
module_path('Restaurant', 'Config/config.php')

// Check if module exists
module_exists('Restaurant')

// Get current tenant
current_tenant()
```

## Blade Directives

```blade
{{-- Show content only for Restaurant module --}}
@module('Restaurant')
    <a href="{{ route('admin.tables.index') }}">Tables</a>
@endmodule

{{-- Show content only for specific feature --}}
@feature('tables')
    <a href="{{ route('admin.tables.index') }}">Tables</a>
@endfeature

{{-- Module-specific directives --}}
@restaurant
    Restaurant-specific content
@endrestaurant

@retail
    Retail-specific content
@endretail

@pharmacy
    Pharmacy-specific content
@endpharmacy
```

## Module Features

### Restaurant Module
- **Tables**: Table management, status tracking, floor plans
- **Kitchen Display**: Real-time order display for kitchen staff
- **Reservations**: Table booking and management
- **Waiters**: Waiter assignment and order taking
- **Order Types**: Dine-in, Takeaway, Delivery

### Retail Module
- **Barcode Scanning**: Quick item lookup via barcode
- **Inventory Management**: Stock tracking and alerts
- **Simple POS**: Streamlined checkout process
- **Price Labels**: Print price labels for products
- **Promotions**: Discount and promotion management

### Pharmacy Module
- **Prescriptions**: Prescription tracking and validation
- **Drug Database**: Comprehensive drug information
- **Expiry Tracking**: Automatic expiry alerts
- **Batch Management**: Track drug batches
- **Controlled Substances**: Special handling for controlled drugs

## Creating a New Module

1. Create module directory structure:
```bash
mkdir -p Modules/NewModule/{Config,Controllers,Models,Views,Routes,Services,Livewire,Database/Migrations,Providers}
```

2. Create ServiceProvider:
```php
// Modules/NewModule/Providers/NewModuleServiceProvider.php
namespace Modules\NewModule\Providers;

use Illuminate\Support\ServiceProvider;

class NewModuleServiceProvider extends ServiceProvider
{
    // ... implement boot() and register()
}
```

3. Add to ModuleServiceProvider:
```php
// app/Providers/ModuleServiceProvider.php
protected array $modules = [
    // ...
    'NewModule' => \Modules\NewModule\Providers\NewModuleServiceProvider::class,
];

protected array $businessTypeModules = [
    // ...
    'new_business_type' => ['Core', 'NewModule'],
];
```

4. Create config file:
```php
// Modules/NewModule/Config/config.php
return [
    'name' => 'NewModule',
    'description' => 'Description of the module',
    'business_types' => ['new_business_type'],
    'features' => ['feature1', 'feature2'],
];
```

## Demo Tenants

| Subdomain | Business Type | Module | Admin Email |
|-----------|--------------|--------|-------------|
| demo-restaurant | restaurant | Restaurant | admin@demo-restaurant.com |
| demo-retail | retail | Retail | admin@demo-retail.com |
| demo-pharmacy | pharmacy | Pharmacy | admin@demo-pharmacy.com |
| demo-cafe | cafe | Restaurant | admin@demo-cafe.com |
| demo-grocery | grocery | Retail | admin@demo-grocery.com |

**Default Password**: `password`

## Architecture Benefits

1. **Separation of Concerns**: Each business type has its own module with specific logic
2. **Code Reusability**: Core module shared across all business types
3. **Easy Maintenance**: Changes to one module don't affect others
4. **Scalability**: Easy to add new business types by creating new modules
5. **Clean UI**: Users only see features relevant to their business type
