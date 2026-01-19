<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerAccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerRequestController extends Controller
{
    public function index()
    {
        // List pending requests
        $requests = CustomerAccountRequest::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.requests.index', compact('requests'));
    }

    public function show($id)
    {
        $request = CustomerAccountRequest::findOrFail($id);
        return view('admin.requests.show', compact('request'));
    }

    public function approve($id)
    {
        $request = CustomerAccountRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed');
        }

        DB::transaction(function () use ($request) {
            // Create User
            User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => $request->password_hash,
                'role' => 'pelanggan',
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address_text' => $request->address_text,
            ]);

            // Update Request
            $request->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                // 'reviewed_by' => auth()->id() // If admin auth is active
            ]);
        });

        return redirect()->route('admin.requests.index')->with('success', 'User approved and created successfully.');
    }

    public function reject(Request $request, $id)
    {
        $accRequest = CustomerAccountRequest::findOrFail($id);

        if ($accRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed');
        }

        $accRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason ?? 'Rejected by admin',
            'reviewed_at' => now(),
            // 'reviewed_by' => auth()->id()
        ]);

        return redirect()->route('admin.requests.index')->with('success', 'Request rejected.');
    }
}
