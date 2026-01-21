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
        Schema::table('master_pelanggan', function (Blueprint $table) {
            $table->string('no_kk', 16)->nullable()->after('no_meter');
            $table->string('no_hp')->nullable()->after('no_kk');
            
            // Address
            $table->string('provinsi')->nullable()->after('no_hp');
            $table->string('kab_kota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kab_kota');
            $table->string('kelurahan')->nullable()->after('kecamatan');
            $table->string('rt')->nullable()->after('kelurahan');
            $table->string('rw')->nullable()->after('rt');
            $table->text('alamat_detail')->nullable()->after('rw');

            // NPWP
            $table->string('npwp', 20)->nullable()->after('alamat_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_pelanggan', function (Blueprint $table) {
            $table->dropColumn([
                'no_kk',
                'no_hp',
                'provinsi',
                'kab_kota',
                'kecamatan',
                'kelurahan',
                'rt',
                'rw',
                'alamat_detail',
                'npwp'
            ]);
        });
    }
};
