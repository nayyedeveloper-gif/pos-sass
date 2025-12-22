<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    
    public $purchaseOrderId;
    public $supplier_id;
    public $order_date;
    public $expected_delivery_date;
    public $total_amount = 0;
    public $notes;
    public $status = 'pending';

    protected $rules = [
        'supplier_id' => 'required|exists:suppliers,id',
        'order_date' => 'required|date',
        'expected_delivery_date' => 'nullable|date',
        'total_amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'status' => 'required|in:pending,received,cancelled',
    ];

    public function mount()
    {
        $this->order_date = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->purchaseOrderId = null;
        $this->supplier_id = null;
        $this->order_date = date('Y-m-d');
        $this->expected_delivery_date = null;
        $this->total_amount = 0;
        $this->notes = '';
        $this->status = 'pending';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $po = PurchaseOrder::findOrFail($this->purchaseOrderId);
                $po->update([
                    'supplier_id' => $this->supplier_id,
                    'order_date' => $this->order_date,
                    'expected_delivery_date' => $this->expected_delivery_date,
                    'total_amount' => $this->total_amount,
                    'notes' => $this->notes,
                    'status' => $this->status,
                ]);

                session()->flash('message', 'ဝယ်ယူမှုမှတ်တမ်းကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
            } else {
                PurchaseOrder::create([
                    'supplier_id' => $this->supplier_id,
                    'user_id' => auth()->id(),
                    'order_date' => $this->order_date,
                    'expected_delivery_date' => $this->expected_delivery_date,
                    'total_amount' => $this->total_amount,
                    'notes' => $this->notes,
                    'status' => $this->status,
                ]);

                session()->flash('message', 'ဝယ်ယူမှုမှတ်တမ်းအသစ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'တစ်ခုခု မှားယွင်းနေပါသည်: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        $this->purchaseOrderId = $po->id;
        $this->supplier_id = $po->supplier_id;
        $this->order_date = $po->order_date->format('Y-m-d');
        $this->expected_delivery_date = $po->expected_delivery_date?->format('Y-m-d');
        $this->total_amount = $po->total_amount;
        $this->notes = $po->notes;
        $this->status = $po->status;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $po = PurchaseOrder::findOrFail($id);
            $po->delete();
            
            session()->flash('message', 'ဝယ်ယူမှုမှတ်တမ်းကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            session()->flash('error', 'ဖျက်၍မရပါ: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('po_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('supplier', function ($sq) {
                          $sq->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('name_mm', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        $suppliers = Supplier::active()->get();

        return view('livewire.admin.inventory.purchase-order-management', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => $suppliers,
        ]);
    }
}
