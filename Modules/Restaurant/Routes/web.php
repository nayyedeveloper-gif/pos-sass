<?php

use Illuminate\Support\Facades\Route;
use Modules\Restaurant\Livewire\TableManagement;
use Modules\Restaurant\Livewire\KitchenDisplay;
use Modules\Restaurant\Livewire\ReservationManagement;
use Modules\Restaurant\Livewire\WaiterDashboard;

/*
|--------------------------------------------------------------------------
| Restaurant Module Web Routes
|--------------------------------------------------------------------------
*/

// Admin Routes
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tables', TableManagement::class)->name('tables.index');
    Route::get('/kitchen', KitchenDisplay::class)->name('kitchen.index');
    Route::get('/reservations', ReservationManagement::class)->name('reservations.index');
});

// Waiter Routes
Route::middleware(['role:waiter'])->prefix('waiter')->name('waiter.')->group(function () {
    Route::get('/dashboard', WaiterDashboard::class)->name('dashboard');
    Route::get('/tables', TableManagement::class)->name('tables.index');
});

// Kitchen Routes
Route::middleware(['role:kitchen'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/display', KitchenDisplay::class)->name('display');
});
