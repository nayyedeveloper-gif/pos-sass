<?php

namespace Modules\Restaurant\Livewire;

use Livewire\Component;
use Modules\Restaurant\Models\Table;

class TableManagement extends Component
{
    public $tables = [];
    public $showModal = false;
    public $editingTable = null;
    
    // Form fields
    public $name = '';
    public $name_mm = '';
    public $capacity = 4;
    public $floor = 'Ground';
    public $section = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:50',
        'name_mm' => 'required|string|max:50',
        'capacity' => 'required|integer|min:1|max:50',
        'floor' => 'nullable|string|max:50',
        'section' => 'nullable|string|max:50',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $this->tables = Table::forTenant(auth()->user()->tenant_id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function openModal($tableId = null)
    {
        if ($tableId) {
            $this->editingTable = Table::find($tableId);
            $this->name = $this->editingTable->name;
            $this->name_mm = $this->editingTable->name_mm;
            $this->capacity = $this->editingTable->capacity;
            $this->floor = $this->editingTable->floor;
            $this->section = $this->editingTable->section;
            $this->is_active = $this->editingTable->is_active;
        } else {
            $this->resetForm();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingTable = null;
        $this->name = '';
        $this->name_mm = '';
        $this->capacity = 4;
        $this->floor = 'Ground';
        $this->section = '';
        $this->is_active = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $this->name,
            'name_mm' => $this->name_mm,
            'capacity' => $this->capacity,
            'floor' => $this->floor,
            'section' => $this->section,
            'is_active' => $this->is_active,
        ];

        if ($this->editingTable) {
            $this->editingTable->update($data);
            session()->flash('success', 'Table updated successfully!');
        } else {
            $data['status'] = Table::STATUS_AVAILABLE;
            Table::create($data);
            session()->flash('success', 'Table created successfully!');
        }

        $this->closeModal();
        $this->loadTables();
    }

    public function delete($tableId)
    {
        $table = Table::find($tableId);
        if ($table && $table->tenant_id === auth()->user()->tenant_id) {
            $table->delete();
            session()->flash('success', 'Table deleted successfully!');
            $this->loadTables();
        }
    }

    public function toggleStatus($tableId)
    {
        $table = Table::find($tableId);
        if ($table && $table->tenant_id === auth()->user()->tenant_id) {
            if ($table->status === Table::STATUS_AVAILABLE) {
                $table->markAsOccupied();
            } else {
                $table->markAsAvailable();
            }
            $this->loadTables();
        }
    }

    public function render()
    {
        return view('restaurant::livewire.table-management')
            ->layout('layouts.app');
    }
}
