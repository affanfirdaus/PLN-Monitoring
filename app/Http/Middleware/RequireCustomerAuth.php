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
        // Guest -> Handler Specific
        if (!Auth::check()) {
            // Case 1: Tamu akses Monitoring/Pembayaran via URL
            if ($request->is('monitoring') || $request->is('pembayaran') || $request->is('monitoring/*')) {
                return redirect()->route('landing')->with('need_login', true);
            }
            
            // Case 2: Tamu akses route lain yang diproteksi (misal wizard tengah jalan) -> Lempar login pelanggan
            return redirect()->route('pelanggan.login', [
                'next' => $request->fullUrl(),
            ]);
        }

        // Pegawai nyasar ke area pelanggan -> lempar ke panelnya
        if (Auth::user()->role !== 'pelanggan') {
            $roleConfig = config('internal_roles');
            $userRole = Auth::user()->role;

            if (isset($roleConfig[$userRole])) {
                return redirect($roleConfig[$userRole]['path'])
                    ->with('error', 'Halaman ini khusus untuk pelanggan.');
            }

            return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
