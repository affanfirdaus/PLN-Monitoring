<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PegawaiAuthController extends Controller
{
    /**
     * Show the pegawai login form.
     */
    public function showLogin(Request $request)
    {
        // If already authenticated, redirect based on role
        if (Auth::check()) {
            $user = Auth::user();
            
            // If pegawai (any internal role), redirect to their panel
            $roleConfig = config('internal_roles');
            if (isset($roleConfig[$user->role])) {
                return $this->redirectToPanel($user);
            }
            
            // If pelanggan tries to access pegawai login, redirect to landing
            if ($user->role === 'pelanggan') {
                return redirect()->route('landing')->with('info', 'Anda sudah login sebagai pelanggan.');
            }
        }

        $role = $request->query('role');
        $roleConfig = config('internal_roles');

        // If no role specified or invalid role -> show selection page
        if (!$role || !isset($roleConfig[$role])) {
            return view('auth.pegawai-select-role');
        }

        // Show login form for specific role
        return view('auth.pegawai-login', [
            'role_key' => $role,
            'role_label' => $roleConfig[$role]['label']
        ]);
    }

    /**
     * Handle pegawai login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Get expected role from query param
        $expectedRole = $request->query('role');

        // Attempt authentication
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Kredensial yang Anda berikan tidak cocok dengan data kami.'),
            ]);
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Akun Anda tidak aktif. Silakan hubungi administrator.'),
            ]);
        }

        // Check if user role matches the selected unit
        if ($user->role !== $expectedRole) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Akun tidak sesuai unit yang dipilih.'),
            ]);
        }

        // Get role configuration
        $roleConfig = config('internal_roles');
        
        if (!isset($roleConfig[$user->role])) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Role Anda tidak memiliki akses ke sistem ini.'),
            ]);
        }

        // Validate email domain matches role
        $emailDomain = explode('@', $user->email)[1] ?? '';
        $expectedDomain = $roleConfig[$user->role]['domain'];

        if ($emailDomain !== $expectedDomain) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => __('Domain email Anda tidak sesuai dengan role. Harap gunakan email @' . $expectedDomain),
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to appropriate panel
        return $this->redirectToPanel($user);
    }

    /**
     * Handle pegawai logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect user to their appropriate panel based on role.
     */
    protected function redirectToPanel($user)
    {
        $roleConfig = config('internal_roles');
        
        if (isset($roleConfig[$user->role])) {
            $path = $roleConfig[$user->role]['path'];
            return redirect($path);
        }

        // Fallback to login if role not found
        Auth::logout();
        return redirect()->route('pegawai.login')->withErrors([
            'email' => 'Role tidak ditemukan dalam konfigurasi sistem.',
        ]);
    }
}
