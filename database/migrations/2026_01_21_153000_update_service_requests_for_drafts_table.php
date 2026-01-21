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
            // New columns
            $table->string('draft_number')->nullable()->unique()->after('status');
            $table->integer('current_step')->default(1)->after('draft_number');
            $table->json('payload_json')->nullable()->after('current_step');
            $table->timestamp('last_saved_at')->nullable()->after('updated_at');
            $table->timestamp('submitted_at')->nullable()->after('last_saved_at');
            
            // Make existing columns nullable
            $table->integer('daya_baru')->nullable()->change();
            $table->string('jenis_produk')->nullable()->change();
            $table->string('peruntukan_koneksi')->nullable()->change();
            $table->foreignId('applicant_id')->nullable()->change(); // Might need to drop FK first if constrained, but let's try
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['draft_number', 'current_step', 'payload_json', 'last_saved_at', 'submitted_at']);
        });
    }
};
