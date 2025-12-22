<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Expense;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportsManagement extends Component
{
    public $reportType = 'daily';
    public $startDate;
    public $endDate;
    
    public $totalSales = 0;
    public $totalOrders = 0;
    public $averageOrderValue = 0;
    public $totalTax = 0;
    public $totalDiscount = 0;
    public $totalServiceCharge = 0;
    public $totalExpenses = 0;
    public $grossProfit = 0;
    public $netProfit = 0;
    public $totalFocCount = 0;
    public $totalFocValue = 0;
    
    public $topSellingItems = [];
    public $salesByCategory = [];
    public $salesByPaymentMethod = [];
    public $salesByOrderType = [];
    public $expensesByCategory = [];
    public $hourlyBreakdown = [];

    public function mount()
    {
        $this->startDate = now()->startOfDay()->format('Y-m-d');
        $this->endDate = now()->endOfDay()->format('Y-m-d');
        $this->generateReport();
    }

    public function updatedReportType()
    {
        // Auto-set date range based on report type
        switch ($this->reportType) {
            case 'daily':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');
                break;
            case 'weekly':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'monthly':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'yearly':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
        }
        
        $this->generateReport();
    }

    public function generateReport()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // Get orders in date range
        $orders = Order::whereBetween('created_at', [
            $this->startDate . ' 00:00:00',
            $this->endDate . ' 23:59:59'
        ])->where('status', 'completed');

        // Calculate totals
        $this->totalSales = $orders->sum('total');
        $this->totalOrders = $orders->count();
        $this->averageOrderValue = $this->totalOrders > 0 ? $this->totalSales / $this->totalOrders : 0;
        $this->totalTax = $orders->sum('tax_amount');
        $this->totalDiscount = $orders->sum('discount_amount');
        $this->totalServiceCharge = $orders->sum('service_charge');

        // Calculate expenses
        $this->totalExpenses = Expense::whereBetween('expense_date', [
            $this->startDate,
            $this->endDate
        ])->sum('amount');

        // Calculate FOC totals
        $focData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->where('orders.status', 'completed')
            ->select(
                DB::raw('SUM(order_items.foc_quantity) as total_count'),
                DB::raw('SUM(order_items.foc_quantity * order_items.price) as total_value')
            )
            ->first();
            
        $this->totalFocCount = $focData->total_count ?? 0;
        $this->totalFocValue = $focData->total_value ?? 0;

        // Calculate profit
        $this->grossProfit = $this->totalSales - $this->totalDiscount;
        $this->netProfit = $this->grossProfit - $this->totalExpenses;

        // Top Selling Items
        $this->topSellingItems = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->where('orders.status', 'completed')
            ->select(
                'items.name',
                'items.name_mm',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.foc_quantity) as total_foc_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->groupBy('items.id', 'items.name', 'items.name_mm')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        // Sales by Category
        $this->salesByCategory = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->where('orders.status', 'completed')
            ->select(
                'categories.name',
                'categories.name_mm',
                DB::raw('SUM(order_items.subtotal) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.name_mm')
            ->orderByDesc('total_sales')
            ->get();

        // Sales by Order Type
        $this->salesByOrderType = Order::whereBetween('created_at', [
            $this->startDate . ' 00:00:00',
            $this->endDate . ' 23:59:59'
        ])
            ->where('status', 'completed')
            ->select(
                'order_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total_sales')
            )
            ->groupBy('order_type')
            ->get();

        // Expenses by Category
        $this->expensesByCategory = Expense::whereBetween('expense_date', [
            $this->startDate,
            $this->endDate
        ])
            ->select(
                'category',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // Hourly Breakdown (for daily reports)
        if ($this->reportType === 'daily') {
            $this->hourlyBreakdown = Order::whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
                ->where('status', 'completed')
                ->select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total) as total_sales')
                )
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();
        }

        session()->flash('message', 'အစီရင်ခံစာကို ထုတ်ယူပြီးပါပြီ။');
    }

    public function exportReport()
    {
        $orders = Order::with(['orderItems.item', 'table', 'waiter', 'cashier'])
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'sales_report_' . $this->startDate . '_to_' . $this->endDate . '.xls';
        
        return response()->streamDownload(function() use ($orders) {
            echo view('admin.reports.export', [
                'orders' => $orders,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'totalSales' => $this->totalSales,
                'totalOrders' => $this->totalOrders,
                'averageOrderValue' => $this->averageOrderValue,
                'totalTax' => $this->totalTax,
                'totalDiscount' => $this->totalDiscount,
                'totalServiceCharge' => $this->totalServiceCharge,
                'totalExpenses' => $this->totalExpenses,
                'grossProfit' => $this->grossProfit,
                'netProfit' => $this->netProfit,
                'totalFocCount' => $this->totalFocCount,
                'totalFocValue' => $this->totalFocValue,
                'topSellingItems' => $this->topSellingItems,
                'salesByPaymentMethod' => $this->salesByPaymentMethod,
                'salesByOrderType' => $this->salesByOrderType,
            ])->render();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.admin.reports-management');
    }
}
