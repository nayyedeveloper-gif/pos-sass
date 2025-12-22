<?php

namespace App\Livewire\Waiter;

use App\Models\Table;
use App\Models\TableSection;
use Livewire\Component;

class TablesList extends Component
{
    public $tables;
    public $sections = [];
    public $selectedSection = null;
    public $search = '';
    public $tableViewMode = 'grid'; // grid or layout
    public $layoutElements = [];

    public function mount()
    {
        $this->loadSections();
        $this->loadTables();
    }

    public function loadSections()
    {
        $this->sections = TableSection::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('floor')
            ->orderBy('sort_order')
            ->get();
            
        if ($this->sections->isNotEmpty() && !$this->selectedSection) {
            $this->selectedSection = $this->sections->first()->id;
        }
    }

    public function loadTables()
    {
        $query = Table::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->withCount(['orders as active_orders_count' => function ($query) {
                $query->whereIn('status', ['pending', 'preparing']);
            }])
            ->with(['orders' => function ($query) {
                $query->whereIn('status', ['pending', 'preparing'])
                      ->latest()
                      ->limit(1);
            }]);
            
        if ($this->selectedSection) {
            $query->where('section_id', $this->selectedSection);
        }
            
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('name_mm', 'like', '%' . $this->search . '%');
            });
        }
            
        $this->tables = $query->orderBy('sort_order')->orderBy('name')->get();
        
        // Load layout elements
        if ($this->selectedSection) {
            $this->layoutElements = \App\Models\TableLayoutElement::where('section_id', $this->selectedSection)->get();
        }
    }

    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
        $this->loadTables();
    }

    public function updatedSearch()
    {
        $this->loadTables();
    }

    public function selectTable($tableId)
    {
        return redirect()->route('waiter.orders.create', ['table' => $tableId]);
    }

    public function createTakeaway()
    {
        return redirect()->route('waiter.orders.create', ['type' => 'takeaway']);
    }

    public function render()
    {
        return view('livewire.waiter.tables-list');
    }
}
