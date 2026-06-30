<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $maintenance = setting('maintenance_mode', false);
        } catch (\Exception $e) {
            $maintenance = false;
        }

        if ($maintenance) {
            // Check if user is logged in and is admin
            if (Auth::check() && Auth::user()->is_admin) {
                return $next($request);
            }

            // Allowed paths during maintenance
            $adminSecret = 'admin-fg-secure';
            try {
                $adminSecret = setting('admin_secret_path', 'admin-fg-secure');
            } catch (\Exception $e) {}

            $allowedPaths = [
                'login',
                'logout',
                $adminSecret,
                $adminSecret . '/*',
                'admin-login/*',
            ];

            foreach ($allowedPaths as $path) {
                if ($request->is($path)) {
                    return $next($request);
                }
            }

            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
