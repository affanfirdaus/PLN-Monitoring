<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create master_slo table
        Schema::create('master_slo', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi_slo')->unique()->index();
            $table->string('no_sertifikat_slo')->unique()->index();
            $table->string('nama_lembaga')->nullable();
            
            // Kolom Baru untuk Verifikasi Owner
            $table->string('nik_pemilik', 16)->index();
            $table->string('nama_pemilik')->nullable();
            
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_berlaku_sampai')->nullable();
            $table->timestamps();
        });

        // 2. Add SLO columns to service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('slo_no_registrasi')->nullable()->after('peruntukan_koneksi');
            $table->string('slo_no_sertifikat')->nullable()->after('slo_no_registrasi');
            $table->dateTime('slo_verified_at')->nullable()->after('slo_no_sertifikat');
            $table->string('slo_verification_status')->default('pending')->after('slo_verified_at'); // pending, valid, invalid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'slo_no_registrasi',
                'slo_no_sertifikat',
                'slo_verified_at',
                'slo_verification_status'
            ]);
        });

        Schema::dropIfExists('master_slo');
    }
};
