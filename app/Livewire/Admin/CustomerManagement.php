<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    
    // Customer properties
    public $customerId;
    public $name;
    public $name_mm;
    public $phone;
    public $email;
    public $date_of_birth;
    public $gender;
    public $address;
    public $notes;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'name_mm' => 'nullable|string|max:255',
        'phone' => 'required|string|max:50',
        'email' => 'nullable|email|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string',
        'notes' => 'nullable|string',
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
        $this->customerId = null;
        $this->name = '';
        $this->name_mm = '';
        $this->phone = '';
        $this->email = '';
        $this->date_of_birth = null;
        $this->gender = null;
        $this->address = '';
        $this->notes = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $customer = Customer::findOrFail($this->customerId);
                $customer->update([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'address' => $this->address,
                    'notes' => $this->notes,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ဖောက်သည်အချက်အလက်ကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
            } else {
                Customer::create([
                    'name' => $this->name,
                    'name_mm' => $this->name_mm,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'address' => $this->address,
                    'notes' => $this->notes,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'ဖောက်သည်အသစ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'တစ်ခုခု မှားယွင်းနေပါသည်: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->name_mm = $customer->name_mm;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->date_of_birth = $customer->date_of_birth?->format('Y-m-d');
        $this->gender = $customer->gender;
        $this->address = $customer->address;
        $this->notes = $customer->notes;
        $this->is_active = $customer->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            
            session()->flash('message', 'ဖောက်သည်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            session()->flash('error', 'ဖျက်၍မရပါ: ' . $e->getMessage());
        }
    }

    public function adjustPoints($id)
    {
        $this->dispatch('open-points-modal', customerId: $id);
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('name_mm', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_code', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.customer-management', [
            'customers' => $customers,
        ]);
    }
}
