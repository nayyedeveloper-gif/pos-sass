<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    /**
     * Get orders for sync
     */
    public function getOrders(Request $request): JsonResponse
    {
        try {
            $lastSync = $request->query('last_sync');
            $query = Order::with(['table', 'waiter', 'cashier', 'orderItems.item']);

            if ($lastSync) {
                $query->where('updated_at', '>', $lastSync);
            }

            $orders = $query->orderBy('updated_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'last_sync' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Sync getOrders failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders'
            ], 500);
        }
    }

    /**
     * Sync orders from local to cloud
     */
    public function syncOrders(Request $request): JsonResponse
    {
        try {
            $orders = $request->input('orders', []);

            \Illuminate\Support\Facades\DB::transaction(function () use ($orders) {
                foreach ($orders as $orderData) {
                    // Extract items
                    $items = $orderData['order_items'] ?? [];
                    unset($orderData['order_items']); // Remove from order data
                    unset($orderData['table']); // Remove relationships
                    unset($orderData['waiter']);
                    unset($orderData['cashier']);

                    // Create/Update Order
                    $order = Order::updateOrCreate(
                        ['id' => $orderData['id']],
                        $orderData
                    );

                    // Create/Update Items
                    foreach ($items as $itemData) {
                        unset($itemData['item']); // Remove relationship
                        $order->orderItems()->updateOrCreate(
                            ['id' => $itemData['id']],
                            $itemData
                        );
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Orders synced successfully',
                'synced_count' => count($orders)
            ]);
        } catch (\Exception $e) {
            Log::error('Sync syncOrders failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get items for sync
     */
    public function getItems(Request $request): JsonResponse
    {
        try {
            $lastSync = $request->query('last_sync');
            $query = Item::query();

            if ($lastSync) {
                $query->where('updated_at', '>', $lastSync);
            }

            $items = $query->orderBy('updated_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $items,
                'last_sync' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Sync getItems failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch items'
            ], 500);
        }
    }

    /**
     * Sync items from local to cloud
     */
    public function syncItems(Request $request): JsonResponse
    {
        try {
            $items = $request->input('items', []);

            foreach ($items as $itemData) {
                Item::updateOrCreate(
                    ['id' => $itemData['id']],
                    $itemData
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Items synced successfully',
                'synced_count' => count($items)
            ]);
        } catch (\Exception $e) {
            Log::error('Sync syncItems failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync items'
            ], 500);
        }
    }

    /**
     * Get settings for sync
     */
    public function getSettings(Request $request): JsonResponse
    {
        try {
            $settings = Setting::all();

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Sync getSettings failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings'
            ], 500);
        }
    }

    /**
     * Sync settings from local to cloud
     */
    public function syncSettings(Request $request): JsonResponse
    {
        try {
            $settings = $request->input('settings', []);

            foreach ($settings as $settingData) {
                Setting::updateOrCreate(
                    ['key' => $settingData['key']],
                    $settingData
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings synced successfully',
                'synced_count' => count($settings)
            ]);
        } catch (\Exception $e) {
            Log::error('Sync syncSettings failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync settings'
            ], 500);
        }
    }
}
