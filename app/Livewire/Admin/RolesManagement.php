<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RolesManagement extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $editingUserId = null;
    public $deleteConfirmId = null;
    
    // Search & Filter
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    // Form fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRole = '';
    public $is_active = true;

    // Role definitions with display info
    public const ROLE_INFO = [
        'owner' => ['name' => 'Owner', 'name_mm' => 'ပိုင်ရှင်', 'color' => 'purple', 'icon' => 'crown'],
        'manager' => ['name' => 'Manager', 'name_mm' => 'မန်နေဂျာ', 'color' => 'indigo', 'icon' => 'briefcase'],
        'cashier' => ['name' => 'Cashier', 'name_mm' => 'ငွေကိုင်', 'color' => 'blue', 'icon' => 'banknotes'],
        'waiter' => ['name' => 'Waiter', 'name_mm' => 'စားပွဲထိုး', 'color' => 'orange', 'icon' => 'user'],
        'kitchen' => ['name' => 'Kitchen', 'name_mm' => 'မီးဖိုချောင်', 'color' => 'red', 'icon' => 'fire'],
        'bar' => ['name' => 'Bar', 'name_mm' => 'ဘား', 'color' => 'amber', 'icon' => 'beaker'],
        'barista' => ['name' => 'Barista', 'name_mm' => 'ဘာရစ်စတာ', 'color' => 'brown', 'icon' => 'coffee'],
        'inventory' => ['name' => 'Inventory', 'name_mm' => 'ကုန်ပစ္စည်း', 'color' => 'teal', 'icon' => 'cube'],
        'sales' => ['name' => 'Sales', 'name_mm' => 'အရောင်း', 'color' => 'green', 'icon' => 'shopping-bag'],
        'pharmacist' => ['name' => 'Pharmacist', 'name_mm' => 'ဆေးဝါးကျွမ်းကျင်', 'color' => 'cyan', 'icon' => 'beaker'],
        'stylist' => ['name' => 'Stylist', 'name_mm' => 'စတိုင်လစ်', 'color' => 'pink', 'icon' => 'scissors'],
        'staff' => ['name' => 'Staff', 'name_mm' => 'ဝန်ထမ်း', 'color' => 'gray', 'icon' => 'user'],
        'admin' => ['name' => 'Admin', 'name_mm' => 'အက်ဒမင်', 'color' => 'purple', 'icon' => 'shield-check'],
    ];

    protected $validationAttributes = [
        'selectedRole' => 'role',
    ];

    /**
     * Get available roles for current tenant
     */
    public function getAvailableRoles(): array
    {
        $tenant = $this->getCurrentTenant();
        
        if ($tenant) {
            return $tenant->getAvailableRoles();
        }
        
        // Default roles if no tenant (super-admin view)
        return ['owner', 'manager', 'cashier', 'waiter', 'kitchen', 'bar', 'inventory', 'staff'];
    }

    /**
     * Get current tenant
     */
    protected function getCurrentTenant(): ?Tenant
    {
        // Check if tenant is bound in app container
        if (app()->bound('tenant')) {
            return app('tenant');
        }
        
        // Check user's tenant
        $user = Auth::user();
        if ($user && $user->tenant_id) {
            return Tenant::find($user->tenant_id);
        }
        
        return null;
    }

    /**
     * Get role validation rule dynamically
     */
    protected function getRoleValidationRule(): string
    {
        $roles = $this->getAvailableRoles();
        return 'required|in:' . implode(',', $roles);
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateForm = true;
        $this->showEditForm = false;
    }

    public function openEditModal($userId)
    {
        $user = User::find($userId);
        $this->editingUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->selectedRole = $user->roles->first()?->name ?? '';
        $this->is_active = $user->is_active;

        $this->resetValidation();
        $this->resetErrorBag();

        $this->showEditForm = true;
        $this->showCreateForm = false;
    }

    public function createUser()
    {
        $availableRoles = $this->getAvailableRoles();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'selectedRole' => 'required|in:' . implode(',', $availableRoles),
            'is_active' => 'boolean',
        ]);

        $tenant = $this->getCurrentTenant();
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
            'tenant_id' => $tenant?->id,
        ]);

        $user->assignRole($this->selectedRole);

        $this->resetForm();

        session()->flash('message', 'ဝန်ထမ်းအသစ် ထည့်သွင်းပြီးပါပြီ။');
        $this->showCreateForm = false;
    }

    public function updateUser()
    {
        $availableRoles = $this->getAvailableRoles();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'selectedRole' => 'required|in:' . implode(',', $availableRoles),
            'is_active' => 'boolean',
        ]);

        $user = User::find($this->editingUserId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);

        if ($this->password) {
            $user->update(['password' => Hash::make($this->password)]);
        }

        $user->syncRoles([$this->selectedRole]);

        $this->resetForm();

        session()->flash('message', 'ဝန်ထမ်းအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။');
        $this->showEditForm = false;
    }

    public function confirmDelete($userId)
    {
        $this->deleteConfirmId = $userId;
    }

    public function deleteUser()
    {
        if ($this->deleteConfirmId) {
            $user = User::find($this->deleteConfirmId);
            if ($user) {
                $user->delete();
                session()->flash('message', 'ဝန်ထမ်းကို ဖျက်ပစ်ပြီးပါပြီ။');
            }
            $this->deleteConfirmId = null;
        }
    }

    public function cancelDelete()
    {
        $this->deleteConfirmId = null;
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ဖွင့်လိုက်ပါပြီ' : 'ပိတ်လိုက်ပါပြီ';
        session()->flash('message', "ဝန်ထမ်းအခြေအနေကို {$status}။");
    }

    public function cancelForm()
    {
        $this->resetForm();
        $this->showCreateForm = false;
        $this->showEditForm = false;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRole = '';
        $this->is_active = true;
        $this->editingUserId = null;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function render()
    {
        $tenant = $this->getCurrentTenant();
        $availableRoles = $this->getAvailableRoles();
        
        $usersQuery = User::with('roles')
            ->whereHas('roles', function ($query) use ($availableRoles) {
                // Show users with roles available for this tenant/business type
                $query->whereIn('name', $availableRoles);
            });
        
        // If tenant exists, scope to tenant users only
        if ($tenant) {
            $usersQuery->where('tenant_id', $tenant->id);
        }
        
        $users = $usersQuery
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.roles-management', [
            'users' => $users,
            'availableRoles' => $availableRoles,
            'roleInfo' => self::ROLE_INFO,
            'tenant' => $tenant,
        ]);
    }
}
