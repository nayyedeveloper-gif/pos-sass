<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ItemsManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    
    public $itemId;
    public $category_id;
    public $name;
    public $name_mm;
    public $description;
    public $price;
    public $image;
    public $existingImage;
    public $is_available = true;
    public $is_active = true;
    public $sort_order = 0;
    
    public $showModal = false;
    public $editMode = false;
    public $deleteConfirm = false;
    public $itemToDelete;

    protected $queryString = ['search', 'categoryFilter', 'statusFilter'];

    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_mm' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => $this->editMode ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
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
        $item = Item::findOrFail($id);
        
        $this->itemId = $item->id;
        $this->category_id = $item->category_id;
        $this->name = $item->name;
        $this->name_mm = $item->name_mm;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->existingImage = $item->image;
        $this->is_available = $item->is_available;
        $this->is_active = $item->is_active;
        $this->sort_order = $item->sort_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'name_mm' => $this->name_mm,
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => $this->is_available,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        // Handle image upload
        if ($this->image) {
            $imagePath = $this->image->store('items', 'public');
            $data['image'] = $imagePath;
            
            // Delete old image if editing
            if ($this->editMode && $this->existingImage) {
                \Storage::disk('public')->delete($this->existingImage);
            }
        }

        if ($this->editMode) {
            $item = Item::findOrFail($this->itemId);
            $item->update($data);
            session()->flash('message', 'ပစ္စည်းကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Item::create($data);
            session()->flash('message', 'ပစ္စည်းကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->itemToDelete = $id;
        $this->deleteConfirm = true;
    }

    public function delete()
    {
        $item = Item::findOrFail($this->itemToDelete);
        
        // Delete image if exists
        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
        }
        
        $item->delete();
        
        session()->flash('message', 'ပစ္စည်းကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        $this->deleteConfirm = false;
        $this->itemToDelete = null;
    }

    public function toggleAvailability($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['is_available' => !$item->is_available]);
        
        session()->flash('message', 'ပစ္စည်း အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
    }

    public function toggleActive($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);
        
        session()->flash('message', 'ပစ္စည်း အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'category_id',
            'name',
            'name_mm',
            'description',
            'price',
            'image',
            'existingImage',
            'is_available',
            'is_active',
            'sort_order',
        ]);
        $this->is_available = true;
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Item::with('category');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter === 'available') {
            $query->where('is_available', true);
        } elseif ($this->statusFilter === 'unavailable') {
            $query->where('is_available', false);
        } elseif ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $items = $query->ordered()->paginate(20);
        $categories = Category::active()->ordered()->get();

        return view('livewire.admin.items-management', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }
}
