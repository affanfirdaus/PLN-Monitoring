<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'pelanggan') {
            return redirect()->route('landing');
        }

        // Fetch all requests where user is submitter (covers draft and submitted)
        $requests = \App\Models\ServiceRequest::with(['applicant'])
            ->where('submitter_user_id', Auth::id())
            ->latest('updated_at')
            ->get();

        return view('pelanggan.monitoring', compact('requests'));
    }

    public function show($id)
    {
        $req = \App\Models\ServiceRequest::with(['applicant'])
            ->where('submitter_user_id', Auth::id())
            ->findOrFail($id);

        return view('pelanggan.monitoring.show', compact('req'));
    }
}
