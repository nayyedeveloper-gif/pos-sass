<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PrinterService;
use Illuminate\Http\Request;

class PrintPreviewController extends Controller
{
    public function kitchen($orderId)
    {
        $order = Order::with(['table', 'items.item.category', 'waiter'])->findOrFail($orderId);
        
        $printerService = new PrinterService();
        $preview = $printerService->previewKitchenOrder($order);
        
        return view('admin.print-preview', [
            'title' => 'Kitchen Print Preview',
            'content' => $preview,
            'order' => $order
        ]);
    }
    
    public function Bar($orderId)
    {
        $order = Order::with(['table', 'items.item.category', 'waiter'])->findOrFail($orderId);
        
        $printerService = new PrinterService();
        $preview = $printerService->previewBarOrder($order);
        
        return view('admin.print-preview', [
            'title' => 'Bar Print Preview',
            'content' => $preview,
            'order' => $order
        ]);
    }
}

