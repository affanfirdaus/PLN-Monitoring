<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireCustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if Guest -> Redirect to Landing with focus param
        if (!Auth::check()) {
            return redirect()->route('landing', [
                'need_login' => 1,
                'focus' => 'hero_login', // Sesuai instruction B1
                'from' => $request->route()?->getName() ?? 'unknown',
            ]);
        }

        // 2. Check if Role is NOT pelanggan (Optional: same redirect or 403)
        // User requested: "kalau login tapi role bukan pelanggan: boleh redirect same landing... atau 403"
        // Let's stricter: Redirect to landing (cleaner UX for employees trying to access customer routes)
        // 2. Check if Role is NOT pelanggan
        if (Auth::user()->role !== 'pelanggan') {
             return redirect()->route('landing', [
                'need_login' => 1,
                'from' => $request->route()?->getName() ?? 'unknown',
             ]);
        }

        return $next($request);
    }
}
