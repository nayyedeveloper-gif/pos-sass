<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Setting;
use App\Services\LicenseService;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboard extends Component
{
    public $totalUsers = 0;
    public $totalRoles = 0;
    public $totalPermissions = 0;
    public $activeUsers = 0;
    public $currentLicense = null;
    public $systemInfo = [];
    public $recentUsers = [];
    public $usersByRole = [];
    
    // Tenant Stats
    public $totalTenants = 0;
    public $activeTenants = 0;
    public $trialTenants = 0;
    
    // Platform Revenue Stats (All Tenants)
    public $todayRevenue = 0;
    public $weeklyRevenue = 0;
    public $monthlyRevenue = 0;
    public $yearlyRevenue = 0;
    public $totalLifetimeRevenue = 0;
    public $weeklyGrowth = 0;
    public $monthlyGrowth = 0;
    
    // Revenue by Tenant
    public $topTenantsByRevenue = [];
    public $dailyRevenueData = [];
    public $monthlyRevenueData = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // User stats
        $this->totalUsers = User::count();
        $this->activeUsers = User::where('is_active', true)->count();
        
        // Roles & Permissions
        $this->totalRoles = Role::count();
        $this->totalPermissions = Permission::count();
        
        // License info
        $licenseService = new LicenseService();
        $this->currentLicense = $licenseService->getCurrentLicense();
        
        // System info
        $this->systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_name' => Setting::get('app_name', config('app.name')),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'timezone' => config('app.timezone'),
        ];
        
        // Recent users
        $this->recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();
            
        // Users by role
        $this->usersByRole = Role::withCount('users')
            ->orderByDesc('users_count')
            ->get()
            ->map(function ($role) {
                return [
                    'name' => $role->name,
                    'count' => $role->users_count,
                ];
            })
            ->toArray();
            
        // Load tenant and revenue data
        $this->loadTenantData();
        $this->loadRevenueData();
    }
    
    public function loadTenantData()
    {
        $this->totalTenants = Tenant::count();
        $this->activeTenants = Tenant::where('status', 'active')->count();
        $this->trialTenants = Tenant::where('status', 'trial')->count();
    }
    
    public function loadRevenueData()
    {
        // Today's revenue (all tenants) - bypass tenant scope
        $this->todayRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');
        
        // This week's revenue
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $this->weeklyRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('total');
        
        // Last week's revenue for comparison
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $lastWeekRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->sum('total');
        
        // Calculate weekly growth
        if ($lastWeekRevenue > 0) {
            $this->weeklyGrowth = round((($this->weeklyRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 1);
        } else {
            $this->weeklyGrowth = $this->weeklyRevenue > 0 ? 100 : 0;
        }
        
        // This month's revenue
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $this->monthlyRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total');
        
        // Last month's revenue for comparison
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $lastMonthRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');
        
        // Calculate monthly growth
        if ($lastMonthRevenue > 0) {
            $this->monthlyGrowth = round((($this->monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
        } else {
            $this->monthlyGrowth = $this->monthlyRevenue > 0 ? 100 : 0;
        }
        
        // This year's revenue
        $startOfYear = Carbon::now()->startOfYear();
        $this->yearlyRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->where('created_at', '>=', $startOfYear)
            ->sum('total');
        
        // Total lifetime revenue
        $this->totalLifetimeRevenue = Order::withoutGlobalScope('tenant')
            ->where('status', 'completed')
            ->sum('total');
        
        // Top tenants by revenue (this month)
        $this->topTenantsByRevenue = Order::withoutGlobalScope('tenant')
            ->select('tenant_id', DB::raw('SUM(total) as total_revenue'), DB::raw('COUNT(*) as order_count'))
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNotNull('tenant_id')
            ->groupBy('tenant_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $tenant = Tenant::find($item->tenant_id);
                return [
                    'tenant_name' => $tenant ? $tenant->name : 'Unknown',
                    'business_type' => $tenant ? ($tenant->business_type ?? 'general') : 'general',
                    'revenue' => $item->total_revenue,
                    'orders' => $item->order_count,
                ];
            })
            ->toArray();
        
        // Daily revenue data for last 7 days (for chart)
        $this->dailyRevenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::withoutGlobalScope('tenant')
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total');
            $this->dailyRevenueData[] = [
                'date' => $date->format('M d'),
                'day' => $date->format('D'),
                'revenue' => (float) $revenue,
            ];
        }
        
        // Monthly revenue data for last 6 months (for chart)
        $this->monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Order::withoutGlobalScope('tenant')
                ->where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $this->monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'short' => $month->format('M'),
                'revenue' => (float) $revenue,
            ];
        }
    }

    public function refresh()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboard-refreshed');
    }

    public function render()
    {
        return view('livewire.admin.super-admin-dashboard');
    }
}
