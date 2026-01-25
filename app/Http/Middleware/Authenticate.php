<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Specific redirect for Admin Pelayanan
            if ($request->is('internal/admin-pelayanan*')) {
                return url('/pegawai/login?role=admin_pelayanan');
            }

            // Check if the request is for internal panel routes
            if ($request->is('internal/*') || $request->is('internal')) {
                return route('pegawai.login');
            }
            
            // Default to pelanggan login for other routes
            return route('pelanggan.login');
        }

        return null;
    }
}
