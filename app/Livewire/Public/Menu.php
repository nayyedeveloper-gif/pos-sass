<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Item;
use App\Models\Setting;
use Livewire\Component;

class Menu extends Component
{
    public $selectedCategory = null;
    public $searchTerm = '';
    public $categories = [];
    public $items = [];
    public $businessName;
    public $businessNameMm;
    public $businessPhone;
    public $businessAddress;
    public $logo;

    public function mount()
    {
        $this->loadBusinessInfo();
        $this->loadCategories();
        $this->loadItems();
    }

    public function loadBusinessInfo()
    {
        $this->businessName = Setting::get('business_name', config('app.name'));
        $this->businessNameMm = Setting::get('business_name_mm', $this->businessName);
        $this->businessPhone = Setting::get('business_phone', '');
        $this->businessAddress = Setting::get('business_address', '');
        $this->logo = Setting::get('app_logo');
    }

    public function loadCategories()
    {
        $this->categories = Category::withCount(['items' => function ($query) {
            $query->active();
        }])
            ->having('items_count', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function loadItems()
    {
        $query = Item::with('category')->active();

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('name_mm', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->items = $query->orderBy('name')->get();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId == $this->selectedCategory ? null : $categoryId;
        $this->loadItems();
    }

    public function updatedSearchTerm()
    {
        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.public.menu')->layout('layouts.public');
    }
}
