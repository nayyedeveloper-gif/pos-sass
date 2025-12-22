<!DOCTYPE html>
<html lang="my">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'Pyidaungsu', 'Padauk', 'Myanmar Text', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            border: none;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            background-color: #d9edf7;
            padding: 10px;
            border: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="2" class="header" style="text-align: center; border: none; font-size: 24px; height: 50px;">
                {{ \App\Models\Setting::get('business_name', 'My Business') }} - Sales Report
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; border: none;">
                Period: {{ $startDate }} to {{ $endDate }}
            </td>
        </tr>
    </table>

    <div class="section-title">SALES SUMMARY / အရောင်းအနှစ်ချုပ်</div>
    <table>
        <tr>
            <th style="width: 300px;">Description</th>
            <th style="width: 200px;">Amount</th>
        </tr>
        <tr>
            <td>Total Orders / စုစုပေါင်း အော်ဒါ</td>
            <td>{{ $totalOrders }}</td>
        </tr>
        <tr>
            <td>Total Sales / စုစုပေါင်း ရောင်းရငွေ</td>
            <td>{{ number_format($totalSales, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total Tax / စုစုပေါင်း အခွန်</td>
            <td>{{ number_format($totalTax, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total Service Charge / ဝန်ဆောင်ခ</td>
            <td>{{ number_format($totalServiceCharge, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total Discount / လျှော့ဈေး</td>
            <td>{{ number_format($totalDiscount, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total Expenses / စရိတ်များ</td>
            <td>{{ number_format($totalExpenses, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total FOC Items / စုစုပေါင်း FOC အရေအတွက်</td>
            <td>{{ number_format($totalFocCount, 0) }}</td>
        </tr>
        <tr>
            <td>Total FOC Value / စုစုပေါင်း FOC တန်ဖိုး</td>
            <td>{{ number_format($totalFocValue, 0) }} Ks</td>
        </tr>
        <tr class="total-row">
            <td>Net Profit / အသားတင်အမြတ်</td>
            <td>{{ number_format($netProfit, 0) }} Ks</td>
        </tr>
    </table>

    <div class="section-title">TOP SELLING ITEMS / အရောင်းရဆုံး ပစ္စည်းများ</div>
    <table>
        <thead>
            <tr>
                <th>Item Name / ပစ္စည်းအမည်</th>
                <th class="text-center">Sold Qty / ရောင်းရသည့်အရေအတွက်</th>
                <th class="text-center">FOC Qty</th>
                <th class="text-right">Total Sales / စုစုပေါင်း</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSellingItems as $item)
            <tr>
                <td>{{ $item->name_mm ?? $item->name }}</td>
                <td class="text-center">{{ $item->total_quantity - $item->total_foc_quantity }}</td>
                <td class="text-center">{{ $item->total_foc_quantity }}</td>
                <td class="text-right">{{ number_format($item->total_sales, 0) }} Ks</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">SALES BY PAYMENT METHOD / ငွေပေးချေမှုပုံစံအလိုက် အရောင်း</div>
    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="text-center">Orders</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesByPaymentMethod as $method)
            <tr>
                <td>
                    @if($method->payment_method == 'cash') ငွေသား (Cash)
                    @elseif($method->payment_method == 'card') ကတ် (Card)
                    @elseif($method->payment_method == 'mobile') မိုဘိုင်း (Mobile)
                    @else {{ ucfirst($method->payment_method) }}
                    @endif
                </td>
                <td class="text-center">{{ $method->count }}</td>
                <td class="text-right">{{ number_format($method->total_sales, 0) }} Ks</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">ORDERS DETAIL / အော်ဒါ အသေးစိတ်</div>
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Time</th>
                <th>Type</th>
                <th>Table</th>
                <th>Waiter</th>
                <th>Cashier</th>
                <th>Payment</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>{{ $order->created_at->format('h:i A') }}</td>
                <td>
                    {{ $order->order_type === 'dine_in' ? 'ဆိုင်တွင်း' : 'ပါဆယ်' }}
                </td>
                <td>{{ $order->table ? ($order->table->name_mm ?? $order->table->name) : '-' }}</td>
                <td>{{ $order->waiter->name ?? '-' }}</td>
                <td>{{ $order->cashier->name ?? '-' }}</td>
                <td>{{ ucfirst($order->payment_method) }}</td>
                <td class="text-right">{{ number_format($order->subtotal, 0) }}</td>
                <td class="text-right">{{ number_format($order->discount_amount, 0) }}</td>
                <td class="text-right">{{ number_format($order->total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
