<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PWAController extends Controller
{
    /**
     * Handle PWA installation event
     */
    public function installed(Request $request)
    {
        try {
            // Log the installation event
            Log::info('PWA Installed', [
                'user_id' => auth()->id(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'data' => $request->all()
            ]);

            // You can add more logic here, like updating user preferences
            
            return response()->json([
                'success' => true,
                'message' => 'PWA installation recorded successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error recording PWA installation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record PWA installation'
            ], 500);
        }
    }
}
