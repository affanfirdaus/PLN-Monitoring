<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$output = [];
$output[] = "Check Timestamp: " . date('Y-m-d H:i:s');

if (Schema::hasColumn('service_requests', 'nomor_permohonan')) {
    $output[] = "SUCCESS: Column 'nomor_permohonan' exists.";
} else {
    $output[] = "FAIL: Column 'nomor_permohonan' missing.";
}

if (Schema::hasColumn('service_requests', 'applicant_identity_id')) { // It might be applicant_id based on previous file audit
     $output[] = "INFO: Column 'applicant_identity_id' check skipped (using applicant_id).";
}

file_put_contents('db_check_result.txt', implode("\n", $output));
