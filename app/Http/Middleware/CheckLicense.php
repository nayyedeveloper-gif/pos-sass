<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use App\Services\LicenseService;

class CheckLicense
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to activation page and public assets/install
        // Added direct path checks to prevent redirect loops if route matching hasn't occurred
        if ($request->routeIs('license.*') || 
            $request->is('install/*') || 
            $request->is('install') || 
            $request->is('activate') || 
            $request->is('activate/*')) {
            return $next($request);
        }

        // Check if license key exists in settings
        try {
            $licenseKey = Setting::where('key', 'license_key')->value('value');
            
            if (!$licenseKey) {
                return redirect()->route('license.activate');
            }

            // Verify License
            $licenseService = new LicenseService();
            $verification = $licenseService->verifyLicense($licenseKey);

            if (!$verification['valid']) {
                // If invalid, clear key and redirect
                return redirect()->route('license.activate')->with('error', $verification['message']);
            }

        } catch (\Exception $e) {
            // If DB not ready (during install), skip check
            return $next($request);
        }

        return $next($request);
    }
}
