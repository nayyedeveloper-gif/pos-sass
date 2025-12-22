<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kitchen Order #{{ $order->order_number }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 14px;
            line-height: 1.5;
            width: 80mm;
            padding: 4mm;
            background: white;
            color: #000;
        }
        
        .myanmar-text {
            font-family: 'Pyidaungsu', 'Myanmar Text', 'Noto Sans Myanmar', sans-serif;
        }
        
        .kitchen-header {
            text-align: center;
            padding: 8px 0;
            border-bottom: 3px double #000;
            margin-bottom: 8px;
        }
        
        .kitchen-title {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        .order-type {
            font-size: 18px;
            font-weight: bold;
            padding: 4px 12px;
            border: 2px solid #000;
            display: inline-block;
            margin-top: 8px;
        }
        
        .order-type.dine-in {
            background: #fff;
        }
        
        .order-type.takeaway {
            background: #000;
            color: #fff;
        }
        
        .order-info {
            padding: 8px 0;
            border-bottom: 2px solid #000;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .info-row.large {
            font-size: 20px;
            font-weight: bold;
        }
        
        .table-name {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 3px solid #000;
            margin: 8px 0;
        }
        
        .items-section {
            padding: 8px 0;
        }
        
        .item-row {
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .item-qty {
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
            width: 40px;
            text-align: center;
            border: 2px solid #000;
            margin-right: 8px;
        }
        
        .item-name {
            font-size: 18px;
            font-weight: bold;
        }
        
        .item-name-mm {
            font-size: 16px;
            margin-left: 48px;
        }
        
        .item-note {
            font-size: 14px;
            margin-left: 48px;
            padding: 4px 8px;
            background: #f0f0f0;
            border-left: 3px solid #000;
            margin-top: 4px;
        }
        
        .item-foc {
            font-size: 16px;
            font-weight: bold;
            margin-left: 48px;
            color: #c00;
            border: 1px solid #c00;
            padding: 2px 8px;
            display: inline-block;
            margin-top: 4px;
        }
        
        .kitchen-footer {
            text-align: center;
            padding-top: 12px;
            border-top: 3px double #000;
            margin-top: 12px;
        }
        
        .print-time {
            font-size: 12px;
            color: #666;
        }
        
        .urgent {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            background: #000;
            color: #fff;
            margin: 8px 0;
        }
        
        @media print {
            body {
                width: 80mm;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="kitchen-header">
        <div class="kitchen-title myanmar-text">မီးဖိုချောင်</div>
        <div class="kitchen-title">KITCHEN</div>
        
        @if($order->table)
            <div class="order-type dine-in">DINE IN</div>
        @else
            <div class="order-type takeaway">TAKEAWAY</div>
        @endif
    </div>

    {{-- Table Name (Large) --}}
    @if($order->table)
        <div class="table-name myanmar-text">{{ $order->table->name_mm ?? $order->table->name }}</div>
    @else
        <div class="table-name myanmar-text">ပါဆယ်ယူမည်</div>
    @endif

    {{-- Order Info --}}
    <div class="order-info">
        <div class="info-row large">
            <span>Order #:</span>
            <span>{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span>Time:</span>
            <span>{{ $order->created_at->format('h:i A') }}</span>
        </div>
        @if($order->waiter)
            <div class="info-row">
                <span>Waiter:</span>
                <span>{{ $order->waiter->name }}</span>
            </div>
        @endif
    </div>

    {{-- Urgent Note --}}
    @if($order->notes && str_contains(strtolower($order->notes), 'urgent'))
        <div class="urgent">⚠ URGENT ⚠</div>
    @endif

    {{-- Items --}}
    <div class="items-section">
        @foreach($items ?? $order->items as $item)
            <div class="item-row">
                <span class="item-qty">{{ $item->quantity }}</span>
                <span class="item-name">{{ $item->item->name }}</span>
                
                @if($item->item->name_mm && $item->item->name_mm !== $item->item->name)
                    <div class="item-name-mm myanmar-text">{{ $item->item->name_mm }}</div>
                @endif
                
                @if($item->notes)
                    <div class="item-note">📝 {{ $item->notes }}</div>
                @endif
                
                @if($item->is_foc || ($item->foc_quantity ?? 0) > 0)
                    <div class="item-foc">★ FOC ★</div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Order Notes --}}
    @if($order->notes)
        <div class="item-note" style="margin-left: 0; margin-top: 8px;">
            <strong>Order Note:</strong> {{ $order->notes }}
        </div>
    @endif

    {{-- Footer --}}
    <div class="kitchen-footer">
        <div class="print-time">Printed: {{ now()->format('d/m/Y h:i:s A') }}</div>
    </div>

    {{-- Print Button --}}
    <div class="no-print" style="text-align: center; margin-top: 20px; padding: 10px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #ef4444; color: white; border: none; border-radius: 8px;">
            Print Kitchen Order
        </button>
    </div>
</body>
</html>
