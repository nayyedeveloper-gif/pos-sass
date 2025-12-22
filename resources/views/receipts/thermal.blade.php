<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_number }}</title>
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
            font-size: 12px;
            line-height: 1.4;
            width: 80mm;
            padding: 4mm;
            background: white;
            color: #000;
        }
        
        .myanmar-text {
            font-family: 'Pyidaungsu', 'Myanmar Text', 'Noto Sans Myanmar', sans-serif;
        }
        
        .receipt-header {
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
            margin-bottom: 8px;
        }
        
        .business-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .business-info {
            font-size: 11px;
            color: #333;
        }
        
        .order-info {
            padding: 8px 0;
            border-bottom: 1px dashed #000;
        }
        
        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .order-info-label {
            color: #666;
        }
        
        .order-info-value {
            font-weight: bold;
        }
        
        .items-header {
            display: flex;
            justify-content: space-between;
            padding: 8px 0 4px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        
        .items-list {
            padding: 8px 0;
        }
        
        .item-row {
            margin-bottom: 6px;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-name-mm {
            font-size: 11px;
            color: #444;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            padding-left: 8px;
        }
        
        .item-qty {
            color: #666;
        }
        
        .item-price {
            font-weight: bold;
        }
        
        .item-note {
            font-size: 10px;
            color: #666;
            padding-left: 8px;
            font-style: italic;
        }
        
        .item-foc {
            font-size: 10px;
            color: #c00;
            padding-left: 8px;
            font-weight: bold;
        }
        
        .totals {
            border-top: 1px dashed #000;
            padding-top: 8px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .payment-info {
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .receipt-footer {
            text-align: center;
            padding-top: 12px;
            border-top: 1px dashed #000;
            margin-top: 12px;
        }
        
        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .footer-note {
            font-size: 10px;
            color: #666;
        }
        
        .qr-code {
            text-align: center;
            margin: 8px 0;
        }
        
        .divider {
            border-top: 1px dashed #000;
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
    <div class="receipt-header">
        @if($settings['logo'] ?? false)
            <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" style="max-width: 60mm; max-height: 20mm; margin-bottom: 4px;">
        @endif
        <div class="business-name myanmar-text">{{ $settings['business_name_mm'] ?? $settings['business_name'] ?? config('app.name') }}</div>
        @if($settings['business_address_mm'] ?? $settings['business_address'] ?? false)
            <div class="business-info myanmar-text">{{ $settings['business_address_mm'] ?? $settings['business_address'] }}</div>
        @endif
        @if($settings['business_phone'] ?? false)
            <div class="business-info">Tel: {{ $settings['business_phone'] }}</div>
        @endif
    </div>

    {{-- Order Info --}}
    <div class="order-info">
        <div class="order-info-row">
            <span class="order-info-label">Order #:</span>
            <span class="order-info-value">{{ $order->order_number }}</span>
        </div>
        <div class="order-info-row">
            <span class="order-info-label">Date:</span>
            <span class="order-info-value">{{ $order->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="order-info-row">
            <span class="order-info-label">Time:</span>
            <span class="order-info-value">{{ $order->created_at->format('h:i A') }}</span>
        </div>
        @if($order->table)
            <div class="order-info-row">
                <span class="order-info-label myanmar-text">စားပွဲ:</span>
                <span class="order-info-value myanmar-text">{{ $order->table->name_mm ?? $order->table->name }}</span>
            </div>
        @else
            <div class="order-info-row">
                <span class="order-info-label myanmar-text">အမျိုးအစား:</span>
                <span class="order-info-value myanmar-text">ပါဆယ်ယူမည်</span>
            </div>
        @endif
        @if($order->waiter)
            <div class="order-info-row">
                <span class="order-info-label">Cashier:</span>
                <span class="order-info-value">{{ $order->waiter->name }}</span>
            </div>
        @endif
        @if($order->customer)
            <div class="order-info-row">
                <span class="order-info-label myanmar-text">ဖောက်သည်:</span>
                <span class="order-info-value">{{ $order->customer->name }}</span>
            </div>
        @endif
    </div>

    {{-- Items Header --}}
    <div class="items-header">
        <span myanmar-text>ပစ္စည်း</span>
        <span>Amount</span>
    </div>

    {{-- Items List --}}
    <div class="items-list">
        @foreach($order->items as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->item->name }}</div>
                @if($item->item->name_mm && $item->item->name_mm !== $item->item->name)
                    <div class="item-name-mm myanmar-text">{{ $item->item->name_mm }}</div>
                @endif
                <div class="item-details">
                    <span class="item-qty">{{ $item->quantity }} x {{ number_format($item->price) }}</span>
                    <span class="item-price">{{ number_format($item->subtotal) }}</span>
                </div>
                @if($item->notes)
                    <div class="item-note">* {{ $item->notes }}</div>
                @endif
                @if($item->is_foc || ($item->foc_quantity ?? 0) > 0)
                    <div class="item-foc">** FOC {{ $item->foc_quantity ?? $item->quantity }} **</div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Totals --}}
    <div class="totals">
        <div class="total-row">
            <span class="myanmar-text">စုစုပေါင်း:</span>
            <span>{{ number_format($order->subtotal) }} Ks</span>
        </div>
        
        @if(($order->tax_amount ?? 0) > 0)
            <div class="total-row">
                <span>Tax ({{ $order->tax_percentage ?? 0 }}%):</span>
                <span>{{ number_format($order->tax_amount) }} Ks</span>
            </div>
        @endif
        
        @if(($order->service_charge ?? 0) > 0)
            <div class="total-row">
                <span class="myanmar-text">ဝန်ဆောင်ခ:</span>
                <span>{{ number_format($order->service_charge) }} Ks</span>
            </div>
        @endif
        
        @if(($order->discount_amount ?? 0) > 0)
            <div class="total-row">
                <span class="myanmar-text">လျှော့စျေး ({{ $order->discount_percentage ?? 0 }}%):</span>
                <span>-{{ number_format($order->discount_amount) }} Ks</span>
            </div>
        @endif
        
        <div class="total-row grand-total">
            <span class="myanmar-text">ပေးရန်:</span>
            <span>{{ number_format($order->total) }} Ks</span>
        </div>
    </div>

    {{-- Payment Info --}}
    @if($order->status === 'completed' && ($order->paid_amount ?? 0) > 0)
        <div class="payment-info">
            <div class="total-row">
                <span class="myanmar-text">ပေးငွေ:</span>
                <span>{{ number_format($order->paid_amount) }} Ks</span>
            </div>
            @if(($order->change_amount ?? 0) > 0)
                <div class="total-row">
                    <span class="myanmar-text">ပြန်အမ်းငွေ:</span>
                    <span>{{ number_format($order->change_amount) }} Ks</span>
                </div>
            @endif
            <div class="total-row">
                <span class="myanmar-text">ငွေပေးချေမှု:</span>
                <span>{{ ucfirst($order->payment_method ?? 'Cash') }}</span>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="receipt-footer">
        <div class="thank-you myanmar-text">ကျေးဇူးတင်ပါသည်!</div>
        <div class="footer-note">Thank You For Your Visit!</div>
        @if($settings['receipt_footer'] ?? false)
            <div class="footer-note myanmar-text">{{ $settings['receipt_footer'] }}</div>
        @endif
    </div>

    {{-- Print Button (hidden on print) --}}
    <div class="no-print" style="text-align: center; margin-top: 20px; padding: 10px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #f97316; color: white; border: none; border-radius: 8px;">
            Print Receipt
        </button>
    </div>
</body>
</html>
