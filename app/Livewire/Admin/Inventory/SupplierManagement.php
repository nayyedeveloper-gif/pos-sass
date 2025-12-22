<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    
    public $supplierId;
    public $name;
    public $name_mm;
    public $contact_person;
    public $phone;
    public $email;
    public $address;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'name_mm' => 'nullable|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->supplierId = null;
        $this->name = '';
        $this->name_mm = '';
        $this->contact_person = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $supplier = Supplier::findOrFail($this->supplierId);
                $supplier->update([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'contact_person' => $this->contact_person,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'address' => $this->address,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ပစ္စည်းပေးသွင်းသူကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
            } else {
                Supplier::create([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'contact_person' => $this->contact_person,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'address' => $this->address,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ပစ္စည်းပေးသွင်းသူအသစ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'တစ်ခုခု မှားယွင်းနေပါသည်: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->name_mm = $supplier->name_mm;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
        $this->is_active = $supplier->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            
            session()->flash('message', 'ပစ္စည်းပေးသွင်းသူကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            session()->flash('error', 'ဖျက်၍မရပါ: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('name_mm', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.inventory.supplier-management', [
            'suppliers' => $suppliers,
        ]);
    }
}
