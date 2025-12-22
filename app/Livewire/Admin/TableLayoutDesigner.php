<?php

namespace App\Livewire\Admin;

use App\Models\Table;
use App\Models\TableSection;
use App\Models\TableLayoutElement;
use Livewire\Component;

class TableLayoutDesigner extends Component
{
    // Sections
    public $sections = [];
    public $selectedSection = null;
    public $currentSection = null;
    
    // Canvas
    public $canvasWidth = 1280;
    public $canvasHeight = 620;
    public $gridSize = 20;
    
    // Tables & Elements
    public $tables = [];
    public $layoutElements = [];
    
    // Selected Element
    public $selectedElement = null;
    public $selectedElementType = null; // 'table' or 'element'
    
    // Element Properties
    public $elementName = '';
    public $elementWidth = 100;
    public $elementHeight = 40;
    public $elementColor = '#6b7280';
    public $elementRotation = 0;
    
    // Modals
    public $showSectionModal = false;
    public $showSettingsModal = false;
    public $showMergeSectionModal = false;
    public $showAddTableModal = false;
    
    // Section Form
    public $sectionForm = [
        'id' => null,
        'name' => '',
        'name_mm' => '',
        'floor' => 1,
        'layout_size' => '1280x620',
    ];
    
    // New Table Form
    public $newTableName = '';
    public $newTableCapacity = 4;
    public $newTableShape = 'square';
    
    // Settings
    public $enableTableLayout = true;
    
    // Merge
    public $sectionsToMerge = [];

    protected $listeners = [
        'elementMoved' => 'handleElementMoved',
        'elementDropped' => 'handleElementDropped',
    ];

    public function mount()
    {
        $this->loadSections();
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $settings = $tenant->settings ?? [];
            $this->enableTableLayout = $settings['enable_table_layout'] ?? true;
        }
    }

    public function loadSections()
    {
        $this->sections = TableSection::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('floor')
            ->orderBy('sort_order')
            ->get();
            
        if ($this->sections->isNotEmpty() && !$this->selectedSection) {
            $this->selectSection($this->sections->first()->id);
        }
    }

    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
        $this->currentSection = TableSection::find($sectionId);
        
        if ($this->currentSection) {
            $size = explode('x', $this->currentSection->layout_size);
            $this->canvasWidth = (int)($size[0] ?? 1280);
            $this->canvasHeight = (int)($size[1] ?? 620);
        }
        
        $this->loadTables();
        $this->loadLayoutElements();
        $this->selectedElement = null;
    }

    public function loadTables()
    {
        if ($this->selectedSection) {
            $this->tables = Table::where('tenant_id', auth()->user()->tenant_id)
                ->where('section_id', $this->selectedSection)
                ->orderBy('sort_order')
                ->get()
                ->toArray();
        }
    }

    public function loadLayoutElements()
    {
        if ($this->selectedSection) {
            $this->layoutElements = TableLayoutElement::where('tenant_id', auth()->user()->tenant_id)
                ->where('section_id', $this->selectedSection)
                ->get()
                ->toArray();
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
                'floor' => 1,
                'layout_size' => '1280x620',
            ];
        }
        $this->showSectionModal = true;
    }

    public function saveSection()
    {
        $this->validate([
            'sectionForm.name' => 'required|string|max:255',
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
            $section = TableSection::create($data);
            $this->selectedSection = $section->id;
        }

        $this->showSectionModal = false;
        $this->loadSections();
        if ($this->selectedSection) {
            $this->selectSection($this->selectedSection);
        }
    }

    public function deleteSection($sectionId)
    {
        TableSection::find($sectionId)?->delete();
        Table::where('section_id', $sectionId)->update(['section_id' => null]);
        TableLayoutElement::where('section_id', $sectionId)->delete();
        
        $this->selectedSection = null;
        $this->loadSections();
    }

    // Add Table to Layout
    public function openAddTableModal()
    {
        $this->newTableName = '#' . (count($this->tables) + 1);
        $this->newTableCapacity = 4;
        $this->newTableShape = 'square';
        $this->showAddTableModal = true;
    }

    public function addTableToLayout()
    {
        $table = Table::create([
            'tenant_id' => auth()->user()->tenant_id,
            'section_id' => $this->selectedSection,
            'floor' => $this->currentSection?->floor ?? 1,
            'name' => $this->newTableName,
            'capacity' => $this->newTableCapacity,
            'shape' => $this->newTableShape,
            'position_x' => 100 + (count($this->tables) * 20),
            'position_y' => 100 + (count($this->tables) * 20),
            'width' => 80,
            'height' => 80,
        ]);

        $this->showAddTableModal = false;
        $this->loadTables();
    }

    public function addComponentToCanvas($type)
    {
        if ($type === 'square' || $type === 'round') {
            $this->newTableShape = $type;
            $this->openAddTableModal();
        } elseif ($type === 'barrier') {
            TableLayoutElement::create([
                'tenant_id' => auth()->user()->tenant_id,
                'section_id' => $this->selectedSection,
                'type' => 'barrier',
                'name' => 'Barrier',
                'position_x' => 200,
                'position_y' => 200,
                'width' => 120,
                'height' => 40,
                'color' => '#6b7280',
            ]);
            $this->loadLayoutElements();
        } elseif ($type === 'label') {
            TableLayoutElement::create([
                'tenant_id' => auth()->user()->tenant_id,
                'section_id' => $this->selectedSection,
                'type' => 'label',
                'name' => 'Label',
                'position_x' => 200,
                'position_y' => 200,
                'width' => 80,
                'height' => 30,
                'color' => 'transparent',
            ]);
            $this->loadLayoutElements();
        }
    }

    // Element Selection & Properties
    public function selectElement($id, $type)
    {
        $this->selectedElement = $id;
        $this->selectedElementType = $type;
        
        if ($type === 'table') {
            $table = Table::find($id);
            if ($table) {
                $this->elementName = $table->name;
                $this->elementWidth = $table->width ?? 80;
                $this->elementHeight = $table->height ?? 80;
            }
        } else {
            $element = TableLayoutElement::find($id);
            if ($element) {
                $this->elementName = $element->name;
                $this->elementWidth = $element->width;
                $this->elementHeight = $element->height;
                $this->elementColor = $element->color;
                $this->elementRotation = $element->rotation;
            }
        }
    }

    public function updateElementProperties()
    {
        if (!$this->selectedElement) return;

        if ($this->selectedElementType === 'table') {
            $table = Table::find($this->selectedElement);
            if ($table) {
                $table->update([
                    'name' => $this->elementName,
                    'width' => max(40, (int)$this->elementWidth),
                    'height' => max(40, (int)$this->elementHeight),
                ]);
            }
            $this->loadTables();
        } else {
            $element = TableLayoutElement::find($this->selectedElement);
            if ($element) {
                $element->update([
                    'name' => $this->elementName,
                    'width' => max(40, (int)$this->elementWidth),
                    'height' => max(20, (int)$this->elementHeight),
                    'color' => $this->elementColor,
                    'rotation' => (int)$this->elementRotation,
                ]);
            }
            $this->loadLayoutElements();
        }
        
        $this->dispatch('tablesUpdated', tables: $this->tables, elements: $this->layoutElements);
    }
    
    public function updateElementSize($id, $type, $width, $height, $x, $y)
    {
        if ($type === 'table') {
            Table::find($id)?->update([
                'width' => max(40, (int)$width),
                'height' => max(40, (int)$height),
                'position_x' => max(0, (int)$x),
                'position_y' => max(0, (int)$y),
            ]);
            $this->loadTables();
        } else {
            TableLayoutElement::find($id)?->update([
                'width' => max(40, (int)$width),
                'height' => max(20, (int)$height),
                'position_x' => max(0, (int)$x),
                'position_y' => max(0, (int)$y),
            ]);
            $this->loadLayoutElements();
        }
    }

    public function deleteSelectedElement()
    {
        if (!$this->selectedElement) return;

        if ($this->selectedElementType === 'table') {
            Table::find($this->selectedElement)?->delete();
            $this->loadTables();
        } else {
            TableLayoutElement::find($this->selectedElement)?->delete();
            $this->loadLayoutElements();
        }
        
        $this->selectedElement = null;
        $this->selectedElementType = null;
    }

    public function duplicateElement()
    {
        if (!$this->selectedElement) return;

        if ($this->selectedElementType === 'table') {
            $table = Table::find($this->selectedElement);
            if ($table) {
                $newTable = $table->replicate();
                $newTable->name = $table->name . ' (copy)';
                $newTable->position_x = ($table->position_x ?? 0) + 20;
                $newTable->position_y = ($table->position_y ?? 0) + 20;
                $newTable->save();
                $this->loadTables();
            }
        } else {
            $element = TableLayoutElement::find($this->selectedElement);
            if ($element) {
                $newElement = $element->replicate();
                $newElement->position_x = $element->position_x + 20;
                $newElement->position_y = $element->position_y + 20;
                $newElement->save();
                $this->loadLayoutElements();
            }
        }
    }

    // Handle drag events from JS
    public function handleElementMoved($id, $type, $x, $y)
    {
        if ($type === 'table') {
            Table::find($id)?->update([
                'position_x' => max(0, (int)$x),
                'position_y' => max(0, (int)$y),
            ]);
        } else {
            TableLayoutElement::find($id)?->update([
                'position_x' => max(0, (int)$x),
                'position_y' => max(0, (int)$y),
            ]);
        }
    }

    // Settings
    public function openSettingsModal()
    {
        $this->showSettingsModal = true;
    }

    public function saveSettings()
    {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $settings = $tenant->settings ?? [];
            $settings['enable_table_layout'] = $this->enableTableLayout;
            $tenant->update(['settings' => $settings]);
        }
        $this->showSettingsModal = false;
    }

    // Merge Sections
    public function openMergeSectionModal()
    {
        $this->sectionsToMerge = [];
        $this->showMergeSectionModal = true;
    }

    public function toggleSectionForMerge($sectionId)
    {
        if (in_array($sectionId, $this->sectionsToMerge)) {
            $this->sectionsToMerge = array_diff($this->sectionsToMerge, [$sectionId]);
        } else {
            $this->sectionsToMerge[] = $sectionId;
        }
    }

    public function confirmMergeSections()
    {
        if (count($this->sectionsToMerge) < 2) {
            session()->flash('error', 'Select at least 2 sections to merge');
            return;
        }

        $primaryId = $this->sectionsToMerge[0];
        $otherIds = array_slice($this->sectionsToMerge, 1);

        // Move all tables and elements to primary section
        foreach ($otherIds as $sectionId) {
            Table::where('section_id', $sectionId)->update(['section_id' => $primaryId]);
            TableLayoutElement::where('section_id', $sectionId)->update(['section_id' => $primaryId]);
            TableSection::find($sectionId)?->delete();
        }

        $this->showMergeSectionModal = false;
        $this->sectionsToMerge = [];
        $this->loadSections();
        $this->selectSection($primaryId);
    }

    public function saveLayout()
    {
        session()->flash('success', 'Layout saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.table-layout-designer');
    }
}
