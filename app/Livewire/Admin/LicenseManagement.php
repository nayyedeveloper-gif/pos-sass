<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\LicenseService;
use App\Models\Setting;

class LicenseManagement extends Component
{
    public $machineIdInput = '';
    public $selectedBusinessType = 'UNIVERSAL';
    public $selectedLicenseType = 'LIFETIME';
    public $subscriptionDays = 30;
    public $generatedLicense = '';
    
    public $currentLicense = null;
    public $businessTypes = [];

    protected $licenseService;

    public function boot(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function mount()
    {
        $this->businessTypes = LicenseService::BUSINESS_TYPES;
        $this->loadCurrentLicense();
    }

    public function loadCurrentLicense()
    {
        $licenseKey = Setting::where('key', 'license_key')->value('value');
        if ($licenseKey) {
            $this->currentLicense = $this->licenseService->verifyLicense($licenseKey);
            $this->currentLicense['key'] = $licenseKey;
        }
    }

    public function generateLicense()
    {
        $this->validate([
            'machineIdInput' => 'required|string|min:8',
            'selectedBusinessType' => 'required|string',
            'selectedLicenseType' => 'required|in:LIFETIME,SUBSCRIPTION',
            'subscriptionDays' => 'required_if:selectedLicenseType,SUBSCRIPTION|integer|min:1',
        ]);

        try {
            $this->generatedLicense = $this->licenseService->generateLicense(
                $this->machineIdInput,
                $this->selectedBusinessType,
                $this->selectedLicenseType,
                $this->subscriptionDays
            );
            
            session()->flash('success', 'License key generated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error generating license: ' . $e->getMessage());
        }
    }

    public function copyLicense()
    {
        $this->dispatch('copy-to-clipboard', text: $this->generatedLicense);
    }

    public function render()
    {
        return view('livewire.admin.license-management');
    }
}
