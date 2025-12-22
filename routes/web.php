<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QrMenuController;
use App\Http\Controllers\LicenseController;

// License Activation Routes
Route::get('/activate', [LicenseController::class, 'showActivate'])->name('license.activate');
Route::post('/activate', [LicenseController::class, 'activate'])->name('license.activate.post');

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/manifest.json', function () {
    $appName = \App\Models\Setting::get('app_name', 'POS System');
    $shortName = \Illuminate\Support\Str::limit($appName, 12);
    
    // Use a default icon set or generate based on uploaded logo if possible
    // For now, we'll serve the static icons but with dynamic text
    
    return response()->json([
        "name" => $appName,
        "short_name" => $shortName,
        "start_url" => "/?source=pwa",
        "scope" => "/",
        "display" => "standalone",
        "background_color" => "#ffffff",
        "theme_color" => "#10b981",
        "orientation" => "portrait",
        "icons" => [
            [
                "src" => "/images/icon-192x192.png",
                "sizes" => "192x192",
                "type" => "image/png",
                "purpose" => "any maskable"
            ],
            [
                "src" => "/images/icon-512x512.png",
                "sizes" => "512x512",
                "type" => "image/png",
                "purpose" => "any maskable"
            ]
        ]
    ]);
});

// Public Menu (No authentication required)
Route::get('/menu', App\Livewire\Public\Menu::class)->name('public.menu');

// Digital Signage Display
Route::get('/display/signage', App\Livewire\Display\MenuBoard::class)->name('display.signage');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::match(['get', 'post'], '/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    
    // Admin routes
    Route::middleware(['role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
        // Super Admin Dashboard (SaaS)
        Route::get('/super-dashboard', function () {
            return view('admin.super-dashboard');
        })->name('super-dashboard');
        
        // Super Admin Settings (SaaS)
        Route::get('/super-settings', function () {
            return view('admin.super-settings');
        })->name('super-settings');
        
        // Tenant Management (Super Admin Only)
        Route::get('/tenants', function () {
            return view('admin.tenants.index');
        })->name('tenants.index')->middleware('role:super-admin');
        
        // Regular Admin Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::get('/categories', function () {
            return view('admin.categories.index');
        })->name('categories.index');
        
        Route::get('/items', function () {
            return view('admin.items.index');
        })->name('items.index');
        
        Route::get('/tables', function () {
            return view('admin.tables.index');
        })->name('tables.index');
        
        Route::get('/tables/layout', function () {
            return view('admin.tables.layout');
        })->name('tables.layout');
        
        Route::get('/roles-permissions', function () {
            return view('admin.roles-permissions.index');
        })->name('roles-permissions.index');
        
        Route::get('/printers', function () {
            return view('admin.printers.index');
        })->name('printers.index');
        
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings.index');
        
        Route::get('/error-logs', function () {
            return view('admin.error-logs.index');
        })->name('error-logs.index');
        
        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('reports.index');
        
        Route::get('/qr-menu', function () {
            return view('admin.qr-menu');
        })->name('qr-menu.index');
        
        Route::get('/orders', function () {
            return view('admin.orders.index');
        })->name('orders.index');
        
        // Print Preview routes
        Route::get('/print-preview/kitchen/{order}', [\App\Http\Controllers\PrintPreviewController::class, 'kitchen'])->name('print-preview.kitchen');
        Route::get('/print-preview/Bar/{order}', [\App\Http\Controllers\PrintPreviewController::class, 'Bar'])->name('print-preview.Bar');
        
        // Receipt routes
        Route::get('/receipt/{order}', [\App\Http\Controllers\ReceiptController::class, 'thermal'])->name('receipt.thermal');
        Route::get('/receipt/{order}/kitchen', [\App\Http\Controllers\ReceiptController::class, 'kitchen'])->name('receipt.kitchen');
        Route::get('/receipt/{order}/bar', [\App\Http\Controllers\ReceiptController::class, 'bar'])->name('receipt.bar');
        
        Route::get('/expenses', function () {
            return view('admin.expenses.index');
        })->name('expenses.index');
        
        Route::get('/signage-media', function () {
            return view('admin.signage-media.index');
        })->name('signage-media.index');
        
        Route::get('/customers', function () {
            return view('admin.customers.index');
        })->name('customers.index');
        
        // Reservation Management
        Route::get('/reservations', function () {
            return view('admin.reservations.index');
        })->name('reservations.index');
        
        // Reservation Calendar
        Route::get('/reservations/calendar', \App\Livewire\Admin\ReservationCalendar::class)
            ->name('reservations.calendar');
        
        Route::get('/loyalty', function () {
            return view('admin.loyalty.index');
        })->name('loyalty.index');
        
        Route::get('/cards', function () {
            return view('admin.cards.index');
        })->name('cards.index');
        
        Route::get('/licenses', function () {
            return view('admin.licenses.index');
        })->name('licenses.index');
        
        Route::get('/payment-settings', function () {
            return view('admin.payment-settings.index');
        })->name('payment-settings.index');
        
        // Inventory routes
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/suppliers', function () {
                return view('admin.inventory.suppliers');
            })->name('suppliers');
            
            Route::get('/stock-items', function () {
                return view('admin.inventory.stock-items');
            })->name('stock-items');
            
            Route::get('/purchase-orders', function () {
                return view('admin.inventory.purchase-orders');
            })->name('purchase-orders');
        });
        
        // Reports routes (removed)
    });
    
    // Cashier routes
    Route::middleware(['role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
        // Dynamic POS based on business type
        Route::get('/pos', function () {
            $tenant = auth()->user()->tenant;
            $businessType = $tenant?->business_type ?? 'retail';
            
            // Map business type to POS view
            $posView = match($businessType) {
                'restaurant', 'cafe', 'fast_food', 'bar' => 'cashier.restaurant-pos',
                'pharmacy', 'drug_store', 'clinic' => 'cashier.pharmacy-pos',
                default => 'cashier.retail-pos',
            };
            
            return view($posView);
        })->name('pos');
        
        // Legacy POS route (for backward compatibility)
        Route::get('/pos/legacy', function () {
            return view('cashier.pos');
        })->name('pos.legacy');
        
        // Shift Management Routes
        Route::get('/shift/status', [App\Http\Controllers\ShiftController::class, 'status'])->name('shift.status');
        Route::post('/shift/open', [App\Http\Controllers\ShiftController::class, 'open'])->name('shift.open');
        Route::get('/shift/details', [App\Http\Controllers\ShiftController::class, 'details'])->name('shift.details');
        Route::post('/shift/close', [App\Http\Controllers\ShiftController::class, 'close'])->name('shift.close');
        
        Route::get('/tables', function () {
            return view('cashier.tables.index');
        })->name('tables.index');
        
        Route::get('/orders', function () {
            return view('cashier.orders.index');
        })->name('orders.index');
        
        Route::get('/orders/{order}', function ($order) {
            return view('cashier.orders.show', ['order' => $order]);
        })->name('orders.show');
    });
    
    // Waiter routes
    Route::middleware(['role:waiter'])->prefix('waiter')->name('waiter.')->group(function () {
        Route::get('/tables', function () {
            return view('waiter.tables.index');
        })->name('tables.index');
        
        Route::get('/orders', function () {
            return view('waiter.orders.index');
        })->name('orders.index');
        
        Route::get('/orders/create', function () {
            return view('waiter.orders.create');
        })->name('orders.create');
        
        Route::get('/orders/{order}', function ($order) {
            return view('waiter.orders.show', ['order' => $order]);
        })->name('orders.show');
    });

    // Kitchen routes
    Route::middleware(['role:kitchen|super-admin|admin'])->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/display', function () {
            return view('kitchen.display');
        })->name('display');
    });
    
});
