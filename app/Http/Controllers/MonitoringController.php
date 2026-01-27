<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Enums\PermohonanStatus;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'pelanggan') {
            return redirect()->route('landing');
        }

        // Determine active tab with smart default
        $tab = $request->get('tab');
        
        // If no tab specified, default to processing, unless empty then fallback to waiting
        if (!$tab) {
            $processingCount = ServiceRequest::where('submitter_user_id', Auth::id())->processing()->count();
            $tab = $processingCount > 0 ? 'processing' : 'waiting';
        }

        // Base query
        $query = ServiceRequest::with(['applicant'])
            ->where('submitter_user_id', Auth::id());

        // Filter by tab
        switch($tab) {
            case 'waiting':
                $requests = $query->draft()->latest('updated_at')->get();
                break;
            
            case 'processing':
                $requests = $query->processing()->latest('updated_at')->get();
                break;
            
            case 'done':
                $requests = $query->done()
                    ->orderByRaw('COALESCE(completed_at, cancelled_at, updated_at) DESC')
                    ->get();
                break;
            
            default:
                $requests = $query->processing()->latest('updated_at')->get();
                $tab = 'processing';
        }

        $counts = [
            'waiting' => ServiceRequest::where('submitter_user_id', Auth::id())->draft()->count(),
            'processing' => ServiceRequest::where('submitter_user_id', Auth::id())->processing()->count(),
            'done' => ServiceRequest::where('submitter_user_id', Auth::id())->done()->count(),
        ];

        return view('pelanggan.monitoring.index', compact('requests', 'tab', 'counts'));
    }

    public function show($id)
    {
        $req = ServiceRequest::with(['applicant'])
            ->where('submitter_user_id', Auth::id())
            ->findOrFail($id);

        // Build stepper data (only for processing/completed requests)
        $steps = PermohonanStatus::getStepperLabels();
        $currentStepIndex = $req->status->getStepIndex();
        
        // Safe default: if step index is null but request is processing/completed, default to step 0 (DITERIMA_PLN)
        if ($currentStepIndex === null && ($req->isProcessing() || $req->status === PermohonanStatus::SELESAI)) {
            $currentStepIndex = 0;
        }
        
        $shouldShowStepper = $req->isProcessing() || $req->status === PermohonanStatus::SELESAI;
        
        // Check if payment is needed
        $showPaymentCTA = $req->status === PermohonanStatus::MENUNGGU_PEMBAYARAN;

        return view('pelanggan.monitoring.show', compact('req', 'steps', 'currentStepIndex', 'shouldShowStepper', 'showPaymentCTA'));
    }
}
