<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class QrMenuManagement extends Component
{
    public $menuUrl;

    public function mount()
    {
        $this->menuUrl = route('public.menu');
    }

    public function render()
    {
        return view('livewire.admin.qr-menu-management');
    }
}
