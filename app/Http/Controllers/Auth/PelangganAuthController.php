<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\CustomerAccountRequest;

class PelangganAuthController extends Controller
{
    public function showLogin()
    {
        // If already authenticated, redirect based on role
        if (Auth::check()) {
            $user = Auth::user();
            
            // If pelanggan, redirect to landing
            if ($user->role === 'pelanggan') {
                return redirect()->route('landing');
            }
            
            // If pegawai (any internal role), redirect to their panel
            $roleConfig = config('internal_roles');
            if (isset($roleConfig[$user->role])) {
                $path = $roleConfig[$user->role]['path'];
                return redirect($path)->with('info', 'Anda sudah login sebagai pegawai.');
            }
        }

        return view('auth.pelanggan.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login via Users table (Active accounts only)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'pelanggan'])) {
            $request->session()->regenerate();
            
            // Redirect to 'next' URL if exists, otherwise to landing
            // Redirect to 'next' URL if exists and is internal
            $next = $request->input('next');
            if ($next && str_starts_with($next, '/') && !str_starts_with($next, '//')) {
                 return redirect()->to($next);
            }
            return redirect()->route('landing');
        }

        // Check if pending request exists
        $pending = CustomerAccountRequest::where('email', $request->email)->where('status', 'pending')->first();
        if ($pending) {
            return back()->withErrors(['email' => 'Akun belum diverifikasi. Silakan tunggu konfirmasi admin.']);
        }
        
        // Check if rejected
        $rejected = CustomerAccountRequest::where('email', $request->email)->where('status', 'rejected')->first();
        if ($rejected) {
             return back()->withErrors(['email' => 'Permintaan akun Anda ditolak.']);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.pelanggan.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:customer_account_requests,email'], // Check unique in both
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'province' => ['required'],
            'regency' => ['required'],
            'district' => ['required'],
            'village' => ['required'],
            'postal_code' => ['required'],
        ]);

        CustomerAccountRequest::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address_text' => $request->address_detail, // Optional detail
            'province' => $request->province,
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'postal_code' => $request->postal_code,
            'password_hash' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        // Do NOT login
        return redirect()->route('pelanggan.register.pending');
    }

    public function showRegisterPending()
    {
        return view('auth.pelanggan.register-pending');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
