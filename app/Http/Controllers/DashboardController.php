<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect based on user role
        // Platform Level
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.super-dashboard');
        }
        
        // Tenant Level - Owner/Admin (full access)
        if ($user->hasRole(['owner', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }

        // Tenant Level - Manager
        if ($user->hasRole('manager')) {
            return redirect()->route('admin.dashboard');
        }

        // Staff Level - Cashier
        if ($user->hasRole('cashier')) {
            return redirect()->route('cashier.pos');
        }

        // Restaurant/F&B Roles
        if ($user->hasRole('waiter')) {
            return redirect()->route('waiter.tables.index');
        }

        if ($user->hasRole('kitchen')) {
            return redirect()->route('kitchen.orders.index');
        }

        if ($user->hasRole('bar')) {
            return redirect()->route('bar.orders.index');
        }

        if ($user->hasRole('barista')) {
            return redirect()->route('kitchen.orders.index');
        }

        // Retail/Sales Roles
        if ($user->hasRole('sales')) {
            return redirect()->route('cashier.pos');
        }

        // Pharmacy Roles
        if ($user->hasRole('pharmacist')) {
            return redirect()->route('cashier.pos');
        }

        // Salon Roles
        if ($user->hasRole('stylist')) {
            return redirect()->route('cashier.pos');
        }

        // Inventory Staff
        if ($user->hasRole('inventory')) {
            return redirect()->route('admin.inventory.stock-items');
        }

        // General Staff
        if ($user->hasRole('staff')) {
            return redirect()->route('cashier.pos');
        }

        // Default fallback
        return view('dashboard');
    }
}
