<?php

use Illuminate\Support\Facades\Route;
use Modules\Retail\Livewire\RetailPOS;
use Modules\Retail\Livewire\InventoryManagement;
use Modules\Retail\Livewire\StockAlerts;
use Modules\Retail\Livewire\SupplierManagement;

/*
|--------------------------------------------------------------------------
| Retail Module Web Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/inventory', InventoryManagement::class)->name('inventory.index');
    Route::get('/stock-alerts', StockAlerts::class)->name('stock-alerts.index');
    Route::get('/suppliers', SupplierManagement::class)->name('suppliers.index');
});

// Cashier Routes
Route::middleware(['role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/pos', RetailPOS::class)->name('pos');
});
