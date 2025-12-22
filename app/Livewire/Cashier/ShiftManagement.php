<?php

namespace App\Livewire\Cashier;

use App\Models\Shift;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShiftManagement extends Component
{
    public $hasOpenShift = false;
    public $currentShift;
    public $openingAmount;
    public $closingAmount;
    public $notes;
    
    // Computed properties
    public $totalSales = 0;
    public $cashSales = 0;
    public $expectedAmount = 0;
    public $difference = 0;

    protected $rules = [
        'openingAmount' => 'required|numeric|min:0',
        'closingAmount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->checkShiftStatus();
    }

    public function checkShiftStatus()
    {
        $this->currentShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        $this->hasOpenShift = (bool) $this->currentShift;

        if ($this->hasOpenShift) {
            $this->calculateStats();
        }
    }

    public function calculateStats()
    {
        if (!$this->currentShift) return;

        $this->totalSales = Order::where('cashier_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $this->currentShift->started_at)
            ->sum('total');
            
        $this->cashSales = Order::where('cashier_id', Auth::id())
            ->where('status', 'completed')
            ->where('updated_at', '>=', $this->currentShift->started_at)
            ->where('payment_method', 'cash')
            ->sum('total');

        $this->expectedAmount = $this->currentShift->opening_amount + $this->cashSales;
    }

    public function updatedClosingAmount()
    {
        if (is_numeric($this->closingAmount)) {
            $this->difference = $this->closingAmount - $this->expectedAmount;
        }
    }

    public function openShift()
    {
        $this->validate([
            'openingAmount' => 'required|numeric|min:0',
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'started_at' => now(),
            'opening_amount' => $this->openingAmount,
            'status' => 'open'
        ]);

        $this->reset(['openingAmount']);
        $this->checkShiftStatus();
        
        $this->dispatch('shift-opened');
    }

    public function closeShift()
    {
        $this->validate([
            'closingAmount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $this->calculateStats(); // Recalculate to be sure
        
        $difference = $this->closingAmount - $this->expectedAmount;

        $this->currentShift->update([
            'ended_at' => now(),
            'closing_amount' => $this->closingAmount,
            'expected_amount' => $this->expectedAmount,
            'difference' => $difference,
            'notes' => $this->notes,
            'status' => 'closed'
        ]);

        $this->reset(['closingAmount', 'notes', 'difference', 'totalSales', 'cashSales', 'expectedAmount']);
        $this->checkShiftStatus();
        
        $this->dispatch('shift-closed');
    }

    public function render()
    {
        return view('livewire.cashier.shift-management');
    }
}
