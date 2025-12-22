<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Printer;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Cloud Printer Service
 * 
 * This service sends print jobs to a local print agent server
 * when the application is running on a VPS/Cloud server.
 * 
 * The print agent must be running on the same network as the printers.
 */
class CloudPrinterService
{
    private string $printAgentUrl;
    private int $timeout = 10; // seconds

    public function __construct()
    {
        $this->printAgentUrl = config('printing.agent_url', env('PRINT_AGENT_URL', ''));
    }

    /**
     * Check if cloud printing is enabled
     */
    public function isEnabled(): bool
    {
        return !empty($this->printAgentUrl);
    }

    /**
     * Check print agent health
     */
    public function checkHealth(): array
    {
        if (!$this->isEnabled()) {
            return [
                'status' => 'disabled',
                'message' => 'Cloud printing is not configured'
            ];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->get($this->printAgentUrl . '/health');

            if ($response->successful()) {
                return [
                    'status' => 'ok',
                    'message' => 'Print agent is running',
                    'data' => $response->json()
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Print agent returned error: ' . $response->status()
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cannot connect to print agent: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Print receipt via cloud print agent
     */
    public function printReceipt(Order $order): bool
    {
        if (!$this->isEnabled()) {
            Log::warning('Cloud printing is not enabled');
            return false;
        }

        $printer = Printer::active()->receipt()->first();
        
        if (!$printer) {
            Log::warning('Receipt printer not configured');
            return false;
        }

        $orderData = $this->prepareReceiptData($order);

        try {
            $payload = [
                'orderData' => $orderData
            ];
            
            // Add printer connection details based on type
            if ($printer->connection_type === 'usb') {
                $payload['printerType'] = 'usb';
            } else {
                $payload['printerType'] = 'network';
                $payload['printerIp'] = $printer->ip_address;
                $payload['printerPort'] = $printer->port;
            }
            
            $response = Http::timeout($this->timeout)
                ->post($this->printAgentUrl . '/print/receipt', $payload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Receipt printed successfully via cloud agent', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                return $result['success'] ?? false;
            }

            $error = $response->json();
            Log::error('Cloud print failed', [
                'order_id' => $order->id,
                'error' => $error['error'] ?? 'Unknown error',
                'status' => $response->status()
            ]);
            return false;

        } catch (Exception $e) {
            Log::error('Cloud print exception', [
                'order_id' => $order->id,
                'exception' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Print kitchen order via cloud print agent
     */
    public function printKitchenOrder(Order $order, ?array $items = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $printer = Printer::active()->kitchen()->first();
        
        if (!$printer) {
            Log::warning('Kitchen printer not configured');
            return false;
        }

        // Get kitchen items
        if ($items !== null) {
            // When items array is passed from CreateOrder, don't filter by is_printed
            // because these are specifically the items to print for this update
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item->category->printer_type === 'kitchen';
            });
        } else {
            $orderItems = $order->items()
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'kitchen');
                })
                ->unprinted()
                ->get();
        }

        if ($orderItems->isEmpty()) {
            return true; // No items to print
        }

        $orderData = $this->prepareKitchenData($order, $orderItems);

        try {
            $payload = [
                'orderData' => $orderData
            ];
            
            if ($printer->connection_type === 'usb') {
                $payload['printerType'] = 'usb';
            } else {
                $payload['printerType'] = 'network';
                $payload['printerIp'] = $printer->ip_address;
                $payload['printerPort'] = $printer->port;
            }
            
            $response = Http::timeout($this->timeout)
                ->post($this->printAgentUrl . '/print/kitchen', $payload);

            if ($response->successful()) {
                // Mark items as printed
                foreach ($orderItems as $item) {
                    $item->update([
                        'is_printed' => true,
                        'printed_at' => now()
                    ]);
                }

                Log::info('Kitchen order printed via cloud agent', [
                    'order_id' => $order->id,
                    'items_count' => $orderItems->count()
                ]);
                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error('Cloud kitchen print failed', [
                'order_id' => $order->id,
                'exception' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Print bar order via cloud print agent
     */
    public function printBarOrder(Order $order, ?array $items = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $printer = Printer::active()->bar()->first();
        
        if (!$printer) {
            Log::warning('Bar printer not configured');
            return false;
        }

        // Get bar items
        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item->category->printer_type === 'bar';
            });
        } else {
            $orderItems = $order->items()
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'bar');
                })
                ->unprinted()
                ->get();
        }

        if ($orderItems->isEmpty()) {
            return true;
        }

        $orderData = $this->prepareKitchenData($order, $orderItems, 'Bar');

        try {
            $payload = [
                'orderData' => $orderData
            ];
            
            if ($printer->connection_type === 'usb') {
                $payload['printerType'] = 'usb';
            } else {
                $payload['printerType'] = 'network';
                $payload['printerIp'] = $printer->ip_address;
                $payload['printerPort'] = $printer->port;
            }
            
            $response = Http::timeout($this->timeout)
                ->post($this->printAgentUrl . '/print/kitchen', $payload);

            if ($response->successful()) {
                foreach ($orderItems as $item) {
                    $item->update([
                        'is_printed' => true,
                        'printed_at' => now()
                    ]);
                }
                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error('Cloud bar print failed', [
                'order_id' => $order->id,
                'exception' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Prepare receipt data for printing
     * 
     * @param Order $order Order with table relationship loaded
     */
    private function prepareReceiptData(Order $order): array
    {
        $items = [];
        /** @var \App\Models\OrderItem $item */
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->item->name_mm ?: $item->item->name,
                'nameEn' => $item->item->name,
                'quantity' => $item->quantity,
                'amount' => $item->subtotal,
                'isFoc' => $item->is_foc,
                'notes' => $item->notes
            ];
        }

        // Get table name via relationship
        $orderTable = $order->table()->first();
        $tableName = $orderTable ? ($orderTable->name_mm ?: $orderTable->name) : 'Takeaway';

        return [
            'businessName' => Setting::get('business_name', config('app.name')),
            'businessAddress' => Setting::get('business_address', ''),
            'businessPhone' => Setting::get('business_phone', ''),
            'orderNumber' => $order->order_number,
            'date' => now()->format('d/m/Y g:i A'),
            'table' => $tableName,
            'waiter' => $order->waiter ? $order->waiter->name : null,
            'items' => $items,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax_amount,
            'taxPercentage' => $order->tax_percentage,
            'discount' => $order->discount_amount,
            'discountPercentage' => $order->discount_percentage,
            'serviceCharge' => $order->service_charge,
            'total' => $order->total,
            'paidAmount' => $order->paid_amount ?? 0,
            'changeAmount' => $order->change_amount ?? 0
        ];
    }

    /**
     * Prepare kitchen/bar data for printing
     * 
     * @param Order $order Order with table relationship loaded
     * @param \Illuminate\Support\Collection|array $items
     * @param string $type
     */
    private function prepareKitchenData(Order $order, $items, string $type = 'Kitchen'): array
    {
        $orderItems = [];
        /** @var \App\Models\OrderItem $item */
        foreach ($items as $item) {
            $orderItems[] = [
                'name' => $item->item->name_mm ?: $item->item->name,
                'nameEn' => $item->item->name,
                'quantity' => $item->quantity,
                'notes' => $item->notes,
                'isFoc' => $item->is_foc
            ];
        }

        // Get table name via relationship
        $orderTable = $order->table()->first();
        $tableName = $orderTable ? ($orderTable->name_mm ?: $orderTable->name) : 'Takeaway';

        return [
            'type' => $type,
            'orderNumber' => $order->order_number,
            'table' => $tableName,
            'time' => now()->format('g:i A'),
            'items' => $orderItems
        ];
    }
}
