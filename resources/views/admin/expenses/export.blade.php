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
            background-color: #ffebee;
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
                {{ \App\Models\Setting::get('business_name', 'My Business') }} - Expenses Report
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; border: none;">
                Date: {{ now()->format('d M Y, h:i A') }}
            </td>
        </tr>
    </table>

    <div class="section-title">EXPENSES SUMMARY / အသုံးစရိတ် အနှစ်ချုပ်</div>
    <table>
        <tr>
            <th style="width: 300px;">Description</th>
            <th style="width: 200px;">Amount</th>
        </tr>
        <tr>
            <td>Total Expenses / စုစုပေါင်း အသုံးစရိတ်</td>
            <td>{{ number_format($totalAmount, 0) }} Ks</td>
        </tr>
        <tr>
            <td>Total Transactions / အရေအတွက်</td>
            <td>{{ $expenses->count() }}</td>
        </tr>
    </table>

    <div class="section-title">EXPENSES BY CATEGORY / အမျိုးအစားအလိုက် အသုံးစရိတ်များ</div>
    <table>
        <thead>
            <tr>
                <th>Category / အမျိုးအစား</th>
                <th class="text-right">Total Amount / စုစုပေါင်း</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expensesByCategory as $item)
            <tr>
                <td>{{ $categories[$item->category] ?? $item->category }}</td>
                <td class="text-right">{{ number_format($item->total_amount, 0) }} Ks</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">EXPENSES DETAIL / အသေးစိတ် စာရင်း</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Payment Method</th>
                <th>Receipt No.</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                <td>{{ $categories[$expense->category] ?? $expense->category }}</td>
                <td>{{ $expense->description }}</td>
                <td>{{ ucfirst($expense->payment_method ?? 'Cash') }}</td>
                <td>{{ $expense->receipt_number ?? '-' }}</td>
                <td class="text-right">{{ number_format($expense->amount, 0) }} Ks</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
