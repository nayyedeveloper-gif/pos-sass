<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain', 'localhost');
        
        // Extract subdomain
        $subdomain = $this->extractSubdomain($host, $baseDomain);
        
        // Skip tenant check for main domain (super admin)
        if (!$subdomain || $subdomain === 'www' || $subdomain === 'admin') {
            return $next($request);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::where('subdomain', $subdomain)
            ->orWhere('domain', $host)
            ->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        if (!$tenant->isActive()) {
            if ($tenant->isSuspended()) {
                abort(403, 'This account has been suspended. Please contact support.');
            }
            abort(403, 'This account is inactive. Please contact support.');
        }
        
        // Set current tenant in app container
        app()->instance('tenant', $tenant);
        
        // Set tenant in session for easy access
        session(['tenant_id' => $tenant->id]);
        
        return $next($request);
    }

    /**
     * Extract subdomain from host
     */
    protected function extractSubdomain(string $host, string $baseDomain): ?string
    {
        // For localhost development
        if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
            // Check for subdomain.localhost format
            $parts = explode('.', $host);
            if (count($parts) > 1 && $parts[count($parts) - 1] === 'localhost') {
                return $parts[0];
            }
            return null;
        }
        
        // For production domains
        if (str_ends_with($host, $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            return $subdomain !== $baseDomain ? $subdomain : null;
        }
        
        return null;
    }
}
