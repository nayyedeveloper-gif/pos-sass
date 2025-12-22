<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\PWAController;

// PWA Routes
Route::post('/pwa/installed', [PWAController::class, 'installed']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Sync routes
    Route::prefix('sync')->group(function () {
        Route::get('/orders', [SyncController::class, 'getOrders']);
        Route::post('/orders', [SyncController::class, 'syncOrders']);
        Route::get('/items', [SyncController::class, 'getItems']);
        Route::post('/items', [SyncController::class, 'syncItems']);
        Route::get('/settings', [SyncController::class, 'getSettings']);
        Route::post('/settings', [SyncController::class, 'syncSettings']);
    });
});

Route::get('/orders/{order}/receipt', function (Order $order) {
    $order->load(['table', 'waiter', 'cashier', 'orderItems.item']);
    
    return response()->json([
        'order_number' => $order->order_number,
        'table' => $order->table,
        'waiter' => $order->waiter,
        'cashier' => $order->cashier,
        'order_items' => $order->orderItems,
        'subtotal' => $order->subtotal,
        'tax_amount' => $order->tax_amount,
        'tax_percentage' => $order->tax_percentage,
        'service_charge' => $order->service_charge,
        'discount_amount' => $order->discount_amount,
        'discount_percentage' => $order->discount_percentage,
        'total' => $order->total,
        'payment_method' => $order->payment_method,
        'amount_received' => $order->amount_received,
        'change_amount' => $order->change_amount,
        'completed_at' => $order->completed_at,
        'shop_name' => \App\Models\Setting::get('business_name_mm', 'ကျွန်ုပ်၏လုပ်ငန်း'),
        'shop_name_en' => \App\Models\Setting::get('business_name', 'My Business'),
        'shop_address' => \App\Models\Setting::get('business_address', ''),
        'shop_phone' => \App\Models\Setting::get('business_phone', ''),
    ]);
});
