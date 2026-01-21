<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DEPRECATED - Columns already exist in create_master_slo migration
 * This migration is intentionally emptied to avoid duplicate columns
 */
return new class extends Migration
{
    public function up(): void
    {
        // INTENTIONALLY EMPTY
        // Columns nik_pemilik and nama_pemilik already created in:
        // 2025_01_20_000001_create_master_slo_and_update_requests.php
    }

    public function down(): void
    {
        // INTENTIONALLY EMPTY
    }
};
