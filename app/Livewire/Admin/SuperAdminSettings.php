<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Services\LicenseService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SuperAdminSettings extends Component
{
    use WithFileUploads;

    // License Settings
    public $licenseKey = '';
    public $licenseStatus = 'inactive';
    
    // Cloud Sync Settings
    public $cloudSyncEnabled = false;
    public $cloudSyncUrl = '';
    public $cloudSyncToken = '';
    public $cloudSyncInterval = 5;
    
    // System Settings
    public $appName = 'POS Pro';
    public $appTimezone = 'Asia/Yangon';
    public $appLocale = 'my';
    public $debugMode = false;
    public $maintenanceMode = false;
    
    // Logo Upload
    public $logo;
    public $currentLogo = null;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // License
        $licenseService = new LicenseService();
        $license = $licenseService->getCurrentLicense();
        $this->licenseKey = Setting::get('license_key', '');
        $this->licenseStatus = $license ? 'active' : 'inactive';
        
        // Cloud Sync
        $this->cloudSyncEnabled = (bool) Setting::get('cloud_sync_enabled', false);
        $this->cloudSyncUrl = Setting::get('cloud_sync_url', '');
        $this->cloudSyncToken = Setting::get('cloud_sync_token', '');
        $this->cloudSyncInterval = (int) Setting::get('cloud_sync_interval', 5);
        
        // System
        $this->appName = Setting::get('app_name', config('app.name', 'NexaPOS'));
        $this->appTimezone = config('app.timezone', 'Asia/Yangon');
        $this->appLocale = config('app.locale', 'my');
        $this->debugMode = config('app.debug', false);
        $this->maintenanceMode = app()->isDownForMaintenance();
        
        // Logo
        $this->currentLogo = Setting::get('app_logo', null);
    }

    public function saveCloudSync()
    {
        $this->validate([
            'cloudSyncUrl' => 'nullable|url',
            'cloudSyncInterval' => 'required|integer|min:1|max:60',
        ]);

        Setting::set('cloud_sync_enabled', $this->cloudSyncEnabled);
        Setting::set('cloud_sync_url', $this->cloudSyncUrl);
        Setting::set('cloud_sync_token', $this->cloudSyncToken);
        Setting::set('cloud_sync_interval', $this->cloudSyncInterval);

        session()->flash('message', 'Cloud Sync settings saved successfully.');
    }

    public function saveSystem()
    {
        $this->validate([
            'appName' => 'required|string|max:100',
        ]);

        Setting::set('app_name', $this->appName);

        session()->flash('message', 'System settings saved successfully.');
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:2048', // 2MB max
        ]);
    }

    public function uploadLogo()
    {
        $this->validate([
            'logo' => 'required|image|max:2048',
        ]);

        // Delete old logo if exists
        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }

        // Store new logo
        $path = $this->logo->store('logos', 'public');
        
        Setting::set('app_logo', $path);
        $this->currentLogo = $path;
        $this->logo = null;

        session()->flash('message', 'Logo uploaded successfully.');
    }

    public function removeLogo()
    {
        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }

        Setting::set('app_logo', null);
        $this->currentLogo = null;

        session()->flash('message', 'Logo removed successfully.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        
        session()->flash('message', 'All caches cleared successfully.');
    }

    public function toggleMaintenance()
    {
        if ($this->maintenanceMode) {
            Artisan::call('up');
            $this->maintenanceMode = false;
            session()->flash('message', 'Application is now live.');
        } else {
            Artisan::call('down', [
                '--secret' => 'super-admin-bypass',
                '--render' => 'errors.503',
            ]);
            $this->maintenanceMode = true;
            session()->flash('message', 'Maintenance mode ဖွင့်လိုက်ပါပြီ။ Super Admin များသာ ဝင်ရောက်နိုင်ပါသည်။');
        }
    }

    public function render()
    {
        return view('livewire.admin.super-admin-settings');
    }
}
