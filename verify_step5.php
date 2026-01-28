<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceRequest;

echo "--- VERIFICATION START ---\n";

// 1. Check Column
if (Schema::hasColumn('service_requests', 'nomor_permohonan')) {
    echo "Column 'nomor_permohonan' EXISTS.\n";
} else {
    echo "Column 'nomor_permohonan' MISSING. Migration might needed.\n";
}

// 2. Check Recent
$recent = DB::table('service_requests')->orderBy('id', 'desc')->limit(3)->get();
echo "Recent ServiceRequests:\n";
foreach ($recent as $r) {
    echo "ID: {$r->id} | No: " . ($r->nomor_permohonan ?? 'NULL') . " | Status: {$r->status} | AppID: " . ($r->applicant_identity_id ?? $r->applicant_id ?? 'NULL') . "\n";
}

echo "--- VERIFICATION END ---\n";
