<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\CustomerLoyaltyTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class LoyaltyManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'loyalty_points';
    public $sortDirection = 'desc';
    
    // Modal State
    public $showAdjustModal = false;
    public $showHistoryModal = false;
    public $selectedCustomer = null;
    public $adjustmentType = 'add';
    public $adjustmentPoints = 0;
    public $adjustmentReason = '';
    
    // Transaction History
    public $transactions = [];

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        // Stats
        $totalCustomers = Customer::count();
        $totalPoints = Customer::sum('loyalty_points');
        $totalRedeemed = CustomerLoyaltyTransaction::redeemed()->sum('points') * -1;
        $totalEarned = CustomerLoyaltyTransaction::earned()->sum('points');

        return view('livewire.admin.loyalty-management', [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'totalPoints' => $totalPoints,
            'totalRedeemed' => $totalRedeemed,
            'totalEarned' => $totalEarned,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function openAdjustModal($customerId)
    {
        $this->selectedCustomer = Customer::find($customerId);
        $this->adjustmentType = 'add';
        $this->adjustmentPoints = 0;
        $this->adjustmentReason = '';
        $this->showAdjustModal = true;
    }

    public function adjustPoints()
    {
        $this->validate([
            'adjustmentPoints' => 'required|integer|min:1',
            'adjustmentReason' => 'required|string|max:255',
        ]);

        if (!$this->selectedCustomer) return;

        if ($this->adjustmentType === 'add') {
            $this->selectedCustomer->earnPoints(
                $this->adjustmentPoints,
                null,
                'Manual adjustment: ' . $this->adjustmentReason
            );
            session()->flash('message', "{$this->adjustmentPoints} points added to {$this->selectedCustomer->name}");
        } else {
            if ($this->selectedCustomer->loyalty_points < $this->adjustmentPoints) {
                session()->flash('error', 'Customer does not have enough points');
                return;
            }
            $this->selectedCustomer->redeemPoints(
                $this->adjustmentPoints,
                null,
                'Manual deduction: ' . $this->adjustmentReason
            );
            session()->flash('message', "{$this->adjustmentPoints} points deducted from {$this->selectedCustomer->name}");
        }

        $this->showAdjustModal = false;
        $this->selectedCustomer = null;
    }

    public function viewHistory($customerId)
    {
        $this->selectedCustomer = Customer::find($customerId);
        $this->transactions = CustomerLoyaltyTransaction::where('customer_id', $customerId)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        $this->showHistoryModal = true;
    }

    public function closeModals()
    {
        $this->showAdjustModal = false;
        $this->showHistoryModal = false;
        $this->selectedCustomer = null;
        $this->transactions = [];
    }
}
