<?php

namespace App\Livewire\Display;

use App\Models\Category;
use App\Models\Item;
use App\Models\Setting;
use App\Models\SignageMedia;
use Livewire\Component;

class MenuBoard extends Component
{
    public $categories = [];
    public $items = [];
    public $media = [];
    public $currentCategoryIndex = 0;
    public $promotionalMessage = '';
    public $appName = '';
    public $logo = null;
    
    // Settings
    public $signageEnabled = true;
    public $rotationSpeed = 10;
    public $showPrices = true;
    public $showDescriptions = true;
    public $showAvailability = true;
    public $theme = 'dark';
    public $showMedia = true;
    
    public function mount()
    {
        $this->loadData();
        $this->loadSettings();
    }

    public function loadData()
    {
        $this->categories = Category::where('is_active', true)
            ->withCount('items')
            ->orderBy('sort_order')
            ->get()
            ->toArray();
        
        $this->items = Item::with('category')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category_id')
            ->toArray();
        
        $this->media = SignageMedia::active()
            ->ordered()
            ->get()
            ->toArray();
        
        $this->promotionalMessage = Setting::get('promotional_message', 'Welcome to our restaurant!');
        $this->appName = Setting::get('app_name', config('app.name'));
        $this->logo = Setting::get('app_logo');
    }

    public function loadSettings()
    {
        $this->signageEnabled = Setting::get('signage_enabled', true);
        $this->rotationSpeed = Setting::get('signage_rotation_speed', 10);
        $this->showPrices = Setting::get('signage_show_prices', true);
        $this->showDescriptions = Setting::get('signage_show_descriptions', true);
        $this->showAvailability = Setting::get('signage_show_availability', true);
        $this->theme = Setting::get('signage_theme', 'dark');
        $this->showMedia = Setting::get('signage_show_media', true);
    }

    public function nextCategory()
    {
        $this->currentCategoryIndex = ($this->currentCategoryIndex + 1) % count($this->categories);
    }

    public function previousCategory()
    {
        $this->currentCategoryIndex = ($this->currentCategoryIndex - 1 + count($this->categories)) % count($this->categories);
    }

    public function render()
    {
        return view('livewire.display.menu-board-enhanced', [
            'theme' => $this->theme,
            'autoRefresh' => $this->signageEnabled ? Setting::get('signage_auto_refresh', 5) : 5
        ])->layout('layouts.display');
    }
}
