<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ensure user is 'pelanggan'
        if (Auth::user()->role !== 'pelanggan') {
            return redirect()->route('landing');
        }

        $user = Auth::user();

        // 1. Permohonan Saya (Where I am the Applicant)
        // If user has no NIK, this list is empty.
        $myRequests = collect();
        if ($user->nik) {
            $myRequests = \App\Models\ServiceRequest::with(['applicant', 'submitter'])
                ->where('applicant_nik', $user->nik)
                ->latest()
                ->get();
        }

        // 2. Permohonan yang Saya Ajukan (Where i am the submitter)
        $submittedRequests = \App\Models\ServiceRequest::with(['applicant'])
            ->where('submitter_user_id', $user->id)
            ->latest()
            ->get();

        $hasRequest = $myRequests->isNotEmpty() || $submittedRequests->isNotEmpty();

        if (!$hasRequest) {
            return view('pelanggan.monitoring-empty');
        }

        return view('pelanggan.monitoring', compact('myRequests', 'submittedRequests'));
    }
}
