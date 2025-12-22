<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\StockItem;
use Livewire\Component;
use Livewire\WithPagination;

class StockItemManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    
    public $stockItemId;
    public $name;
    public $name_mm;
    public $sku;
    public $unit;
    public $current_stock = 0;
    public $minimum_stock = 0;
    public $unit_cost = 0;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'name_mm' => 'nullable|string|max:255',
        'sku' => 'nullable|string|max:255|unique:stock_items,sku',
        'unit' => 'required|string|max:50',
        'current_stock' => 'required|numeric|min:0',
        'minimum_stock' => 'required|numeric|min:0',
        'unit_cost' => 'required|numeric|min:0',
        'is_active' => 'boolean',
    ];

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
        $this->stockItemId = null;
        $this->name = '';
        $this->name_mm = '';
        $this->sku = '';
        $this->unit = '';
        $this->current_stock = 0;
        $this->minimum_stock = 0;
        $this->unit_cost = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['sku'] = 'nullable|string|max:255|unique:stock_items,sku,' . $this->stockItemId;
        }
        
        $this->validate();

        try {
            if ($this->editMode) {
                $stockItem = StockItem::findOrFail($this->stockItemId);
                $stockItem->update([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'sku' => $this->sku,
                    'unit' => $this->unit,
                    'current_stock' => $this->current_stock,
                    'minimum_stock' => $this->minimum_stock,
                    'unit_cost' => $this->unit_cost,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ကုန်ပစ္စည်းကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
            } else {
                StockItem::create([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'sku' => $this->sku,
                    'unit' => $this->unit,
                    'current_stock' => $this->current_stock,
                    'minimum_stock' => $this->minimum_stock,
                    'unit_cost' => $this->unit_cost,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ကုန်ပစ္စည်းအသစ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'တစ်ခုခု မှားယွင်းနေပါသည်: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $stockItem = StockItem::findOrFail($id);
        
        $this->stockItemId = $stockItem->id;
        $this->name = $stockItem->name;
        $this->name_mm = $stockItem->name_mm;
        $this->sku = $stockItem->sku;
        $this->unit = $stockItem->unit;
        $this->current_stock = $stockItem->current_stock;
        $this->minimum_stock = $stockItem->minimum_stock;
        $this->unit_cost = $stockItem->unit_cost;
        $this->is_active = $stockItem->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $stockItem = StockItem::findOrFail($id);
            $stockItem->delete();
            
            session()->flash('message', 'ကုန်ပစ္စည်းကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            session()->flash('error', 'ဖျက်၍မရပါ: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $stockItems = StockItem::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('name_mm', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.inventory.stock-item-management', [
            'stockItems' => $stockItems,
        ]);
    }
}
