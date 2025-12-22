<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LicenseService;
use App\Models\Setting;

class LicenseController extends Controller
{
    public function showActivate(LicenseService $licenseService)
    {
        // Check if already activated
        $licenseKey = Setting::where('key', 'license_key')->value('value');
        if ($licenseKey) {
            $verification = $licenseService->verifyLicense($licenseKey);
            if ($verification['valid']) {
                return redirect()->route('login');
            }
        }

        $machineId = $licenseService->getMachineId();
        return view('license.activate', compact('machineId'));
    }

    public function activate(Request $request, LicenseService $licenseService)
    {
        $request->validate([
            'license_key' => 'required|string'
        ]);

        $key = trim($request->input('license_key'));
        
        $verification = $licenseService->verifyLicense($key);

        if ($verification['valid']) {
            // Save key to settings
            Setting::updateOrCreate(
                ['key' => 'license_key'],
                ['value' => $key, 'type' => 'string']
            );

            return redirect()->route('login')->with('success', 'License activated successfully! Type: ' . $verification['type']);
        }

        return back()->with('error', $verification['message']);
    }
}
