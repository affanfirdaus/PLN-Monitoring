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
        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            
            // User reference (nullable initially, but filled if verified)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Input Data for Audit
            $table->string('nama_input');
            $table->string('email_input');
            $table->string('nik_input', 16);
            
            // Status & Token
            $table->enum('status', ['pending', 'sent', 'rejected'])->default('pending');
            $table->string('request_token')->unique();
            
            // Admin Processing
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            
            // Security
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('email_input');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
