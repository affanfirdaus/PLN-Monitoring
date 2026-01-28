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
        Schema::table('service_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('service_requests', 'nomor_permohonan')) {
                // Add unique nullable string for official application number
                $table->string('nomor_permohonan')->nullable()->unique()->after('id');
            }
            
            // Ensure status index exists if not already
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (Schema::hasColumn('service_requests', 'nomor_permohonan')) {
                $table->dropColumn('nomor_permohonan');
            }
            
            $table->dropIndex(['status']);
        });
    }
};
