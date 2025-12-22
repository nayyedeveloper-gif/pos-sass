<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductsManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';
    
    public $productId;
    public $type = '';
    public $name = '';
    public $name_mm = '';
    public $description = '';
    public $image;
    public $existingImage = '';
    public $price = '';
    public $is_active = true;
    public $sort_order = 0;
    
    // Type-specific fields
    public $typeData = [];
    
    public $showModal = false;
    public $editMode = false;
    public $deleteConfirm = false;
    public $productToDelete;

    protected $queryString = ['search', 'typeFilter', 'statusFilter'];

    protected function rules()
    {
        $rules = [
            'type' => 'required|in:diamond,gold,platinum',
            'name' => 'required|string|max:255',
            'name_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image' => $this->editMode ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
        ];

        // Add type-specific validation rules
        if ($this->type === 'diamond') {
            $rules['typeData.carat'] = 'required|numeric|min:0';
            $rules['typeData.clarity'] = 'nullable|string|max:255';
            $rules['typeData.color'] = 'nullable|string|max:255';
            $rules['typeData.cut'] = 'nullable|string|max:255';
            $rules['typeData.certificate_number'] = 'nullable|string|max:255';
            $rules['typeData.certificate_file'] = 'nullable|file|max:5120';
        } elseif ($this->type === 'gold') {
            $rules['typeData.weight'] = 'required|numeric|min:0';
            $rules['typeData.purity'] = 'nullable|string|max:255';
            $rules['typeData.karat'] = 'nullable|string|max:255';
            $rules['typeData.hallmark'] = 'nullable|string|max:255';
            $rules['typeData.manufacturer'] = 'nullable|string|max:255';
        } elseif ($this->type === 'platinum') {
            $rules['typeData.weight'] = 'required|numeric|min:0';
            $rules['typeData.purity'] = 'nullable|string|max:255';
            $rules['typeData.hallmark'] = 'nullable|string|max:255';
            $rules['typeData.manufacturer'] = 'nullable|string|max:255';
            $rules['typeData.serial_number'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        // Reset type-specific data when type changes
        $this->typeData = [];
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        $this->productId = $product->id;
        $this->type = $product->type;
        $this->name = $product->name;
        $this->name_mm = $product->name_mm;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->existingImage = $product->image;
        $this->is_active = $product->is_active;
        $this->sort_order = $product->sort_order;
        $this->typeData = $product->type_data ?? [];
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'name_mm' => $this->name_mm,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'type_data' => $this->typeData,
        ];

        // Handle image upload
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $data['image'] = $imagePath;
            
            // Delete old image if editing
            if ($this->editMode && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
        }

        // Handle certificate file upload for diamond
        if ($this->type === 'diamond' && isset($this->typeData['certificate_file']) && is_object($this->typeData['certificate_file'])) {
            $certPath = $this->typeData['certificate_file']->store('products/certificates', 'public');
            $this->typeData['certificate_file'] = $certPath;
            $data['type_data'] = $this->typeData;
        }

        if ($this->editMode) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('message', 'Product ကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Product::create($data);
            session()->flash('message', 'Product ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->productToDelete = $id;
        $this->deleteConfirm = true;
    }

    public function delete()
    {
        $product = Product::findOrFail($this->productToDelete);
        
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete certificate file if exists
        if ($product->type_data && isset($product->type_data['certificate_file'])) {
            Storage::disk('public')->delete($product->type_data['certificate_file']);
        }
        
        $product->delete();
        
        session()->flash('message', 'Product ကို အောင်မြင်စွာ ဖျက်ပစ်ပြီးပါပြီ။');
        $this->deleteConfirm = false;
        $this->productToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function cancelDelete()
    {
        $this->deleteConfirm = false;
        $this->productToDelete = null;
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->type = '';
        $this->name = '';
        $this->name_mm = '';
        $this->description = '';
        $this->image = null;
        $this->existingImage = '';
        $this->price = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->typeData = [];
        $this->resetValidation();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $products = $query->ordered()->paginate(15);

        return view('livewire.admin.products-management', [
            'products' => $products,
        ]);
    }
}
