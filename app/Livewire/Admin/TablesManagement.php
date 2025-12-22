<?php

namespace App\Livewire\Admin;

use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;

class TablesManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    public $tableId;
    public $name;
    public $name_mm;
    public $capacity = 4;
    public $status = 'available';
    public $is_active = true;
    public $sort_order = 0;
    
    public $showModal = false;
    public $editMode = false;
    public $deleteConfirm = false;
    public $tableToDelete;

    protected $queryString = ['search', 'statusFilter'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'name_mm' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|in:available,occupied,reserved',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $table = Table::findOrFail($id);
        
        $this->tableId = $table->id;
        $this->name = $table->name;
        $this->name_mm = $table->name_mm;
        $this->capacity = $table->capacity;
        $this->status = $table->status;
        $this->is_active = $table->is_active;
        $this->sort_order = $table->sort_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'name_mm' => $this->name_mm,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editMode) {
            $table = Table::findOrFail($this->tableId);
            $table->update($data);
            session()->flash('message', 'စားပွဲကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Table::create($data);
            session()->flash('message', 'စားပွဲကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $table = Table::findOrFail($id);
        
        // Check if table has active orders
        if ($table->activeOrder) {
            session()->flash('error', 'ဤစားပွဲတွင် လက်ရှိ အော်ဒါ ရှိနေသောကြောင့် ဖျက်၍မရပါ။');
            return;
        }
        
        $this->tableToDelete = $id;
        $this->deleteConfirm = true;
    }

    public function delete()
    {
        $table = Table::findOrFail($this->tableToDelete);
        $table->delete();
        
        session()->flash('message', 'စားပွဲကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        $this->deleteConfirm = false;
        $this->tableToDelete = null;
    }

    public function toggleActive($id)
    {
        $table = Table::findOrFail($id);
        $table->update(['is_active' => !$table->is_active]);
        
        session()->flash('message', 'စားပွဲ အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
    }

    public function changeStatus($id, $status)
    {
        $table = Table::findOrFail($id);
        
        // Don't allow changing to occupied if there's no active order
        if ($status === 'occupied' && !$table->activeOrder) {
            session()->flash('error', 'အော်ဒါကောင်တာသော စားပွဲကို Occupied အဖြစ် ပြောင်း၍မရပါ။');
            return;
        }
        
        $table->update(['status' => $status]);
        session()->flash('message', 'စားပွဲ အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'tableId',
            'name',
            'name_mm',
            'capacity',
            'status',
            'is_active',
            'sort_order',
        ]);
        $this->capacity = 4;
        $this->status = 'available';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function viewTableOrders($tableId)
    {
        return redirect()->route('admin.orders.index', ['table' => $tableId]);
    }

    public function render()
    {
        $query = Table::withCount('orders')
            ->with(['activeOrder' => function($q) {
                $q->with('orderItems.item');
            }]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $tables = $query->ordered()->paginate(20);

        return view('livewire.admin.tables-management', [
            'tables' => $tables,
        ]);
    }
}
