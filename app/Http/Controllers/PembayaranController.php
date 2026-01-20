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

        $user = Auth::user();
        
        $payments = collect();
        if ($user->nik) {
            $payments = \App\Models\Payment::whereHas('serviceRequest', function($q) use ($user) {
                $q->where('applicant_nik', $user->nik);
            })->with('serviceRequest.applicant')->latest()->get();
        }

        if ($payments->isEmpty()) {
            return view('pelanggan.pembayaran-empty');
        }

        return view('pelanggan.pembayaran', compact('payments'));
    }
}
