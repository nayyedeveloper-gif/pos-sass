<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Illuminate\Support\Facades\Auth;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        'login/*',
        'logout',
        'livewire/*',
        'super-admin-bypass',
        'super-admin-bypass/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Allow super-admin to bypass maintenance mode
        if (Auth::check() && Auth::user()->hasRole('super-admin')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
