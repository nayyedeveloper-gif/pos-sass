<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $printerFilter = '';
    
    public $categoryId;
    public $name;
    public $name_mm;
    public $description;
    public $printer_type = 'kitchen';
    public $is_active = true;
    public $sort_order = 0;
    
    public $showModal = false;
    public $editMode = false;
    public $deleteConfirm = false;
    public $categoryToDelete;

    protected $queryString = ['search', 'printerFilter'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'name_mm' => 'required|string|max:255',
            'description' => 'nullable|string',
            'printer_type' => 'required|in:kitchen,bar,nan_pyar,none',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPrinterFilter()
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
        $category = Category::findOrFail($id);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->name_mm = $category->name_mm;
        $this->description = $category->description;
        $this->printer_type = $category->printer_type;
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'name_mm' => $this->name_mm,
            'description' => $this->description,
            'printer_type' => $this->printer_type,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editMode) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($data);
            session()->flash('message', 'အမျိုးအစားကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Category::create($data);
            session()->flash('message', 'အမျိုးအစားကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has items
        if ($category->items()->count() > 0) {
            session()->flash('error', 'ဤအမျိုးအစားတွင် ပစ္စည်းများ ရှိနေသောကြောင့် ဖျက်၍မရပါ။');
            return;
        }
        
        $this->categoryToDelete = $id;
        $this->deleteConfirm = true;
    }

    public function delete()
    {
        $category = Category::findOrFail($this->categoryToDelete);
        $category->delete();
        
        session()->flash('message', 'အမျိုးအစားကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        $this->deleteConfirm = false;
        $this->categoryToDelete = null;
    }

    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        session()->flash('message', 'အမျိုးအစား အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'name_mm',
            'description',
            'printer_type',
            'is_active',
            'sort_order',
        ]);
        $this->printer_type = 'kitchen';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Category::withCount('items');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->printerFilter) {
            $query->where('printer_type', $this->printerFilter);
        }

        $categories = $query->ordered()->paginate(20);

        return view('livewire.admin.categories-management', [
            'categories' => $categories,
        ]);
    }
}
