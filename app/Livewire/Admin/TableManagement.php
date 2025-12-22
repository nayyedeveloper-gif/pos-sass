<?php

namespace App\Livewire\Admin;

use App\Models\Table;
use App\Models\TableSection;
use App\Models\TableLayoutElement;
use Livewire\Component;

class TableManagement extends Component
{
    // Sections
    public $sections = [];
    public $selectedSection = null;
    public $selectedFloor = 1;
    
    // Tables
    public $tables = [];
    public $selectedTables = [];
    
    // Section Modal
    public $showSectionModal = false;
    public $sectionForm = [
        'id' => null,
        'name' => '',
        'name_mm' => '',
        'floor' => 1,
        'layout_size' => '1280x620',
    ];
    
    // Table Modal
    public $showTableModal = false;
    public $tableForm = [
        'id' => null,
        'name' => '',
        'name_mm' => '',
        'capacity' => 4,
        'shape' => 'square',
        'section_id' => null,
    ];
    
    // Merge Modal
    public $showMergeModal = false;
    public $mergeTables = [];
    
    // Layout Elements
    public $layoutElements = [];
    
    // View Mode
    public $viewMode = 'grid'; // grid or layout
    
    // Floors available
    public $floors = [-1, 1, 2, 3];

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
            ->with(['currentOrder', 'waiter', 'mergedTables']);
            
        if ($this->selectedSection) {
            $query->where('section_id', $this->selectedSection);
        } else {
            $query->where('floor', $this->selectedFloor);
        }
        
        $this->tables = $query->orderBy('sort_order')->orderBy('name')->get();
    }

    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
        $section = TableSection::find($sectionId);
        if ($section) {
            $this->selectedFloor = $section->floor;
        }
        $this->loadTables();
        $this->loadLayoutElements();
    }

    public function selectFloor($floor)
    {
        $this->selectedFloor = $floor;
        $this->selectedSection = null;
        $this->loadTables();
    }

    public function loadLayoutElements()
    {
        if ($this->selectedSection) {
            $this->layoutElements = TableLayoutElement::where('section_id', $this->selectedSection)->get();
        }
    }

    // Section CRUD
    public function openSectionModal($sectionId = null)
    {
        if ($sectionId) {
            $section = TableSection::find($sectionId);
            $this->sectionForm = [
                'id' => $section->id,
                'name' => $section->name,
                'name_mm' => $section->name_mm,
                'floor' => $section->floor,
                'layout_size' => $section->layout_size,
            ];
        } else {
            $this->sectionForm = [
                'id' => null,
                'name' => '',
                'name_mm' => '',
                'floor' => $this->selectedFloor,
                'layout_size' => '1280x620',
            ];
        }
        $this->showSectionModal = true;
    }

    public function saveSection()
    {
        $this->validate([
            'sectionForm.name' => 'required|string|max:255',
            'sectionForm.floor' => 'required|integer',
        ]);

        $data = [
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $this->sectionForm['name'],
            'name_mm' => $this->sectionForm['name_mm'],
            'floor' => $this->sectionForm['floor'],
            'layout_size' => $this->sectionForm['layout_size'],
        ];

        if ($this->sectionForm['id']) {
            TableSection::find($this->sectionForm['id'])->update($data);
        } else {
            TableSection::create($data);
        }

        $this->showSectionModal = false;
        $this->loadSections();
    }

    public function deleteSection($sectionId)
    {
        TableSection::find($sectionId)?->delete();
        $this->selectedSection = null;
        $this->loadSections();
        $this->loadTables();
    }

    // Table CRUD
    public function openTableModal($tableId = null)
    {
        if ($tableId) {
            $table = Table::find($tableId);
            $this->tableForm = [
                'id' => $table->id,
                'name' => $table->name,
                'name_mm' => $table->name_mm,
                'capacity' => $table->capacity,
                'shape' => $table->shape ?? 'square',
                'section_id' => $table->section_id,
            ];
        } else {
            $this->tableForm = [
                'id' => null,
                'name' => '',
                'name_mm' => '',
                'capacity' => 4,
                'shape' => 'square',
                'section_id' => $this->selectedSection,
            ];
        }
        $this->showTableModal = true;
    }

    public function saveTable()
    {
        $this->validate([
            'tableForm.name' => 'required|string|max:255',
            'tableForm.capacity' => 'required|integer|min:1',
        ]);

        $data = [
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $this->tableForm['name'],
            'name_mm' => $this->tableForm['name_mm'],
            'capacity' => $this->tableForm['capacity'],
            'shape' => $this->tableForm['shape'],
            'section_id' => $this->tableForm['section_id'] ?? $this->selectedSection,
            'floor' => $this->selectedFloor,
        ];

        if ($this->tableForm['id']) {
            Table::find($this->tableForm['id'])->update($data);
        } else {
            Table::create($data);
        }

        $this->showTableModal = false;
        $this->loadTables();
    }

    public function deleteTable($tableId)
    {
        Table::find($tableId)?->delete();
        $this->loadTables();
    }

    public function toggleTableStatus($tableId)
    {
        $table = Table::find($tableId);
        if ($table) {
            if ($table->status === 'available') {
                $table->update([
                    'status' => 'occupied',
                    'occupied_at' => now(),
                ]);
            } else {
                $table->update([
                    'status' => 'available',
                    'occupied_at' => null,
                    'guest_count' => null,
                    'current_order_id' => null,
                ]);
            }
            $this->loadTables();
        }
    }

    // Merge Tables
    public function toggleTableSelection($tableId)
    {
        if (in_array($tableId, $this->selectedTables)) {
            $this->selectedTables = array_diff($this->selectedTables, [$tableId]);
        } else {
            $this->selectedTables[] = $tableId;
        }
    }

    public function openMergeModal()
    {
        if (count($this->selectedTables) < 2) {
            session()->flash('error', 'Please select at least 2 tables to merge');
            return;
        }
        
        $this->mergeTables = Table::whereIn('id', $this->selectedTables)->get();
        $this->showMergeModal = true;
    }

    public function confirmMerge()
    {
        if (count($this->selectedTables) < 2) {
            return;
        }

        $parentId = $this->selectedTables[0];
        $childIds = array_slice($this->selectedTables, 1);
        
        // Update parent table
        $parentTable = Table::find($parentId);
        $totalCapacity = $parentTable->capacity;
        
        foreach ($childIds as $childId) {
            $childTable = Table::find($childId);
            $totalCapacity += $childTable->capacity;
            
            $childTable->update([
                'is_merged' => true,
                'merge_parent_id' => $parentId,
                'status' => 'occupied',
            ]);
        }
        
        $parentTable->update([
            'merged_with' => $childIds,
            'capacity' => $totalCapacity,
            'status' => 'occupied',
        ]);

        $this->showMergeModal = false;
        $this->selectedTables = [];
        $this->loadTables();
    }

    public function unmergeTables($tableId)
    {
        $table = Table::find($tableId);
        if (!$table) return;

        // If this is a parent table
        if ($table->merged_with) {
            foreach ($table->merged_with as $childId) {
                Table::find($childId)?->update([
                    'is_merged' => false,
                    'merge_parent_id' => null,
                    'status' => 'available',
                ]);
            }
            
            $table->update([
                'merged_with' => null,
                'status' => 'available',
            ]);
        }
        
        // If this is a child table
        if ($table->merge_parent_id) {
            $parent = Table::find($table->merge_parent_id);
            if ($parent && $parent->merged_with) {
                $newMerged = array_diff($parent->merged_with, [$tableId]);
                $parent->update([
                    'merged_with' => empty($newMerged) ? null : array_values($newMerged),
                ]);
            }
            
            $table->update([
                'is_merged' => false,
                'merge_parent_id' => null,
                'status' => 'available',
            ]);
        }

        $this->loadTables();
    }

    public function batchAddTables()
    {
        // Add 5 tables at once
        $lastTable = Table::where('tenant_id', auth()->user()->tenant_id)
            ->where('section_id', $this->selectedSection)
            ->orderBy('sort_order', 'desc')
            ->first();
            
        $startNum = 1;
        if ($lastTable) {
            preg_match('/\d+/', $lastTable->name, $matches);
            if (!empty($matches)) {
                $startNum = (int)$matches[0] + 1;
            }
        }

        for ($i = 0; $i < 5; $i++) {
            Table::create([
                'tenant_id' => auth()->user()->tenant_id,
                'section_id' => $this->selectedSection,
                'floor' => $this->selectedFloor,
                'name' => '#' . ($startNum + $i),
                'capacity' => 4,
                'shape' => 'square',
                'sort_order' => $startNum + $i,
            ]);
        }

        $this->loadTables();
    }

    public function render()
    {
        return view('livewire.admin.table-management');
    }
}
