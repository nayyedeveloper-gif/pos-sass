<?php

use Illuminate\Support\Facades\Route;
use Modules\Pharmacy\Livewire\PharmacyPOS;
use Modules\Pharmacy\Livewire\DrugManagement;
use Modules\Pharmacy\Livewire\ExpiryAlerts;
use Modules\Pharmacy\Livewire\PrescriptionManagement;

/*
|--------------------------------------------------------------------------
| Pharmacy Module Web Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/drugs', DrugManagement::class)->name('drugs.index');
    Route::get('/expiry-alerts', ExpiryAlerts::class)->name('expiry-alerts.index');
    Route::get('/prescriptions', PrescriptionManagement::class)->name('prescriptions.index');
});

// Cashier/Pharmacist Routes
Route::middleware(['role:cashier|pharmacist'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/pos', PharmacyPOS::class)->name('pos');
});
