<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function status()
    {
        $currentShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        return response()->json([
            'has_open_shift' => (bool) $currentShift,
            'shift' => $currentShift
        ]);
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0'
        ]);

        $existingShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($existingShift) {
            return response()->json(['message' => 'You already have an open shift'], 400);
        }

        $shift = Shift::create([
            'user_id' => Auth::id(),
            'started_at' => now(),
            'opening_amount' => $request->opening_amount,
            'status' => 'open'
        ]);

        return response()->json([
            'message' => 'Shift opened successfully',
            'shift' => $shift
        ]);
    }

    public function details()
    {
        $shift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->firstOrFail();

        // Calculate total sales for this shift
        // Assuming orders have a 'cashier_id' and 'created_at' or 'completed_at'
        // We'll filter orders completed during this shift by this cashier
        $totalSales = Order::where('cashier_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $shift->started_at)
            ->sum('total');
            
        $cashSales = Order::where('cashier_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $shift->started_at)
            ->where('payment_method', 'cash')
            ->sum('total');

        $expectedAmount = $shift->opening_amount + $cashSales;

        return response()->json([
            'shift' => $shift,
            'total_sales' => $totalSales,
            'cash_sales' => $cashSales,
            'expected_amount' => $expectedAmount
        ]);
    }

    public function close(Request $request)
    {
        $request->validate([
            'closing_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $shift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->firstOrFail();

        // Recalculate expected amount to be safe
        $cashSales = Order::where('cashier_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $shift->started_at)
            ->where('payment_method', 'cash')
            ->sum('total');

        $expectedAmount = $shift->opening_amount + $cashSales;
        $difference = $request->closing_amount - $expectedAmount;

        $shift->update([
            'ended_at' => now(),
            'closing_amount' => $request->closing_amount,
            'expected_amount' => $expectedAmount,
            'difference' => $difference,
            'notes' => $request->notes,
            'status' => 'closed'
        ]);

        return response()->json([
            'message' => 'Shift closed successfully',
            'shift' => $shift
        ]);
    }
}
