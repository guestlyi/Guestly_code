<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!basicControl()->is_maintenance_mode) {
            return $next($request);
        }

        if (auth()->guard('admin')->check()) {
            return $next($request);
        }

        if (!$request->is('maintenance-mode')) {
            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
