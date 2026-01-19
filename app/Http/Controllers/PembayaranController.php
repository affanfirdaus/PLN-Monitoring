<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
         // Ensure user is 'pelanggan'
        if (Auth::user()->role !== 'pelanggan') {
            return redirect()->route('landing');
        }

        // Check if user has requests/invoices (Placeholder for logic)
        // $hasRequest = Permohonan::where('pelanggan_id', auth()->id())->exists();
        $hasRequest = false; 

        if (!$hasRequest) {
            return view('pelanggan.pembayaran-empty');
        }

        return view('pelanggan.pembayaran', compact('hasRequest'));
    }
}
