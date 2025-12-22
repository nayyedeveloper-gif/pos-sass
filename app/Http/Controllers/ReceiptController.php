<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display thermal receipt for printing
     */
    public function thermal(Order $order)
    {
        $order->load(['items.item', 'table', 'waiter', 'customer']);
        
        $settings = [
            'business_name' => Setting::get('business_name', config('app.name')),
            'business_name_mm' => Setting::get('business_name_mm'),
            'business_address' => Setting::get('business_address'),
            'business_address_mm' => Setting::get('business_address_mm'),
            'business_phone' => Setting::get('business_phone'),
            'logo' => Setting::get('app_logo'),
            'receipt_footer' => Setting::get('receipt_footer'),
        ];
        
        return view('receipts.thermal', compact('order', 'settings'));
    }
    
    /**
     * Display kitchen order for printing
     */
    public function kitchen(Order $order)
    {
        $order->load(['items.item.category', 'table', 'waiter']);
        
        // Filter kitchen items only
        $items = $order->items->filter(function ($item) {
            return $item->item->category->printer_type === 'kitchen';
        });
        
        return view('receipts.kitchen', compact('order', 'items'));
    }
    
    /**
     * Display bar order for printing
     */
    public function bar(Order $order)
    {
        $order->load(['items.item.category', 'table', 'waiter']);
        
        // Filter bar items only
        $items = $order->items->filter(function ($item) {
            return $item->item->category->printer_type === 'bar';
        });
        
        return view('receipts.kitchen', compact('order', 'items'))
            ->with('title', 'BAR ORDER');
    }
}
