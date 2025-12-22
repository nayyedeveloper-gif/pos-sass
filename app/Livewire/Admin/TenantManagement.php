<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TenantManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $planFilter = '';
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Form data
    public $tenantId;
    public $name = '';
    public $businessType = 'general';
    public $subdomain = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $status = 'trial';
    public $plan = 'free';
    public $trialDays = 14;
    
    // Admin user for new tenant
    public $adminName = '';
    public $adminEmail = '';
    public $adminPassword = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'subdomain' => 'required|string|max:63|alpha_dash|unique:tenants,subdomain',
        'email' => 'required|email|unique:tenants,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'status' => 'required|in:active,inactive,suspended,trial',
        'plan' => 'required|in:free,basic,pro,enterprise',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['tenantId', 'name', 'businessType', 'subdomain', 'email', 'phone', 'address', 'status', 'plan', 'adminName', 'adminEmail', 'adminPassword']);
        $this->businessType = 'general';
        $this->status = 'trial';
        $this->plan = 'free';
        $this->trialDays = 14;
        $this->showCreateModal = true;
    }

    public function openEditModal(Tenant $tenant)
    {
        $this->tenantId = $tenant->id;
        $this->name = $tenant->name;
        $this->businessType = $tenant->business_type ?? 'general';
        $this->subdomain = $tenant->subdomain;
        $this->email = $tenant->email;
        $this->phone = $tenant->phone;
        $this->address = $tenant->address;
        $this->status = $tenant->status;
        $this->plan = $tenant->plan;
        $this->showEditModal = true;
    }

    public function openDeleteModal(Tenant $tenant)
    {
        $this->tenantId = $tenant->id;
        $this->name = $tenant->name;
        $this->showDeleteModal = true;
    }

    public function createTenant()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'businessType' => 'required|in:' . implode(',', array_keys(Tenant::BUSINESS_TYPES)),
            'subdomain' => 'required|string|max:63|alpha_dash|unique:tenants,subdomain',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'plan' => 'required|in:free,basic,pro,enterprise',
            'adminName' => 'required|string|max:255',
            'adminEmail' => 'required|email|unique:users,email',
            'adminPassword' => 'required|string|min:8',
        ]);

        // Create tenant with business type
        $tenant = Tenant::create([
            'name' => $this->name,
            'business_type' => $this->businessType,
            'subdomain' => Str::lower($this->subdomain),
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => 'trial',
            'plan' => $this->plan,
            'trial_ends_at' => now()->addDays($this->trialDays),
        ]);

        // Create owner user for tenant (using 'owner' role instead of 'admin')
        $user = User::create([
            'name' => $this->adminName,
            'email' => $this->adminEmail,
            'password' => Hash::make($this->adminPassword),
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);
        
        $user->assignRole('owner');

        $this->showCreateModal = false;
        $this->reset(['name', 'businessType', 'subdomain', 'email', 'phone', 'address', 'adminName', 'adminEmail', 'adminPassword']);
        
        session()->flash('message', 'Tenant created successfully!');
    }

    public function updateTenant()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:63|alpha_dash|unique:tenants,subdomain,' . $this->tenantId,
            'email' => 'required|email|unique:tenants,email,' . $this->tenantId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,suspended,trial',
            'plan' => 'required|in:free,basic,pro,enterprise',
        ]);

        $tenant = Tenant::findOrFail($this->tenantId);
        $tenant->update([
            'name' => $this->name,
            'business_type' => $this->businessType,
            'subdomain' => Str::lower($this->subdomain),
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
            'plan' => $this->plan,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Tenant updated successfully!');
    }

    public function deleteTenant()
    {
        $tenant = Tenant::findOrFail($this->tenantId);
        
        // Delete all users belonging to this tenant
        User::where('tenant_id', $tenant->id)->delete();
        
        $tenant->delete();

        $this->showDeleteModal = false;
        session()->flash('message', 'Tenant deleted successfully!');
    }

    public function activateTenant(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);
        session()->flash('message', 'Tenant activated successfully!');
    }

    public function suspendTenant(Tenant $tenant)
    {
        $tenant->update(['status' => 'suspended']);
        session()->flash('message', 'Tenant suspended successfully!');
    }

    public function render()
    {
        $tenants = Tenant::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('subdomain', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->planFilter, fn($q) => $q->where('plan', $this->planFilter))
            ->withCount('users')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('status', 'active')->count(),
            'trial' => Tenant::where('status', 'trial')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
        ];

        return view('livewire.admin.tenant-management', [
            'tenants' => $tenants,
            'stats' => $stats,
            'businessTypes' => Tenant::BUSINESS_TYPES,
        ]);
    }
}
