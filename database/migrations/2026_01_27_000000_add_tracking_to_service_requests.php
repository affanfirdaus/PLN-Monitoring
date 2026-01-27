<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Draft management
            // Check and add columns only if they don't exist
            if (!Schema::hasColumn('service_requests', 'is_draft')) {
                $table->boolean('is_draft')->default(true)->after('status');
            }
            if (!Schema::hasColumn('service_requests', 'last_step')) {
                $table->integer('last_step')->nullable()->after('is_draft');
            }
            
            // Submission tracking
            if (!Schema::hasColumn('service_requests', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('created_at');
            }
            
            // Cancellation tracking (admin action)
            if (!Schema::hasColumn('service_requests', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (!Schema::hasColumn('service_requests', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('service_requests', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
            
            // Completion tracking
            if (!Schema::hasColumn('service_requests', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
        
        // CRITICAL: Audit and map existing status values before enum cast
        // Step 1: Set is_draft and submitted_at based on current status
        DB::table('service_requests')->update([
            'is_draft' => DB::raw("CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END"),
            'submitted_at' => DB::raw("CASE WHEN status != 'DRAFT' THEN created_at ELSE NULL END"),
        ]);
        
        // Step 2: Map known legacy statuses to new enum values
        $statusMapping = [
            'WAITING_PAYMENT' => 'MENUNGGU_PEMBAYARAN',
            'WAITING_SLO_VERIFICATION' => 'VERIFIKASI_SLO',
            'APPROVED' => 'DITERIMA_PLN',
            'SUBMITTED' => 'DITERIMA_PLN',
            'IN_PROGRESS' => 'SURVEY_LAPANGAN',
            'COMPLETED' => 'SELESAI',
            'REJECTED' => 'DIBATALKAN_ADMIN',
            'CANCELLED' => 'DIBATALKAN_ADMIN',
            // Add more mappings based on actual DB audit
        ];
        
        foreach ($statusMapping as $oldStatus => $newStatus) {
            DB::table('service_requests')
                ->where('status', $oldStatus)
                ->update(['status' => $newStatus]);
        }
        
        // Step 3: Log unknown statuses for manual review
        $validStatuses = [
            'DRAFT', 'DITERIMA_PLN', 'VERIFIKASI_SLO', 'SURVEY_LAPANGAN',
            'PERENCANAAN_MATERIAL', 'MENUNGGU_PEMBAYARAN', 'KONSTRUKSI_INSTALASI',
            'PENYALAAN_TE', 'SELESAI', 'DIBATALKAN_ADMIN'
        ];
        
        $unknownStatuses = DB::table('service_requests')
            ->whereNotIn('status', $validStatuses)
            ->distinct()
            ->pluck('status')
            ->toArray();
        
        if (!empty($unknownStatuses)) {
            // Log for manual review - DO NOT auto-update yet
            \Log::warning('Unknown statuses found in service_requests table. Manual mapping required:', [
                'statuses' => $unknownStatuses,
                'count' => DB::table('service_requests')->whereNotIn('status', $validStatuses)->count()
            ]);
            
            // OPTIONAL: Only if you want to auto-fallback truly unknown statuses
            // Uncomment after reviewing the log and confirming these are safe to default
            // DB::table('service_requests')
            //     ->whereNotIn('status', $validStatuses)
            //     ->where('status', '!=', 'DRAFT')
            //     ->update(['status' => 'DITERIMA_PLN']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'is_draft',
                'last_step',
                'submitted_at',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason',
                'completed_at',
            ]);
        });
    }
};
