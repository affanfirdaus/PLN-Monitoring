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
        // 1.1 Add 'nik' to 'users' table
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists to avoid error if re-running
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 16)->nullable()->unique()->index()->after('email');
            }
        });

        // 1.2 Create 'pelanggan_profiles' table
        Schema::create('pelanggan_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nik', 16)->unique()->index();
            $table->string('nama_lengkap');
            $table->string('no_meter')->nullable();
            $table->string('id_pelanggan_12')->nullable();
            
            // Address fields
            $table->string('provinsi')->nullable();
            $table->string('kab_kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->text('alamat_detail')->nullable();

            $table->timestamps();
        });

        // 1.3 Create 'applicant_identities' table (Master Pemohon)
        Schema::create('applicant_identities', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->index();
            $table->string('nama_lengkap');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Linking lookup
            
            $table->string('no_meter')->nullable();
            $table->string('id_pelanggan_12')->nullable();

            // Default location/address for this identity
            $table->string('default_provinsi')->nullable();
            $table->string('default_kab_kota')->nullable();
            $table->string('default_kecamatan')->nullable();
            $table->string('default_kelurahan')->nullable();
            $table->string('default_rt')->nullable();
            $table->string('default_rw')->nullable();
            $table->text('default_alamat_detail')->nullable();

            $table->timestamps();
        });

        // 1.4 Create 'service_requests' table (Inti Histori)
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_layanan', ['TAMBAH_DAYA', 'PASANG_BARU'])->default('TAMBAH_DAYA');
            $table->string('nomor_permohonan')->unique()->nullable(); // Generated later
            $table->string('status')->default('DRAFT'); // DRAFT, SUBMITTED, IN_REVIEW, APPROVED, REJECTED, etc.
            
            // Submitter (Account that logged in)
            $table->foreignId('submitter_user_id')->constrained('users');

            // Applicant (The actual person requesting)
            $table->foreignId('applicant_id')->constrained('applicant_identities');
            $table->string('applicant_nik', 16)->index(); // Denormalized for easier query

            // Location Snapshot
            $table->string('lokasi_provinsi')->nullable();
            $table->string('lokasi_kab_kota')->nullable();
            $table->string('lokasi_kecamatan')->nullable();
            $table->string('lokasi_kelurahan')->nullable();
            $table->string('lokasi_rt')->nullable();
            $table->string('lokasi_rw')->nullable();
            $table->text('lokasi_detail_tambahan')->nullable();

            $table->decimal('koordinat_lat', 10, 7)->nullable();
            $table->decimal('koordinat_lng', 10, 7)->nullable();

            // Service Details
            $table->integer('daya_baru');
            $table->enum('jenis_produk', ['PASCABAYAR', 'PRABAYAR']);
            $table->enum('peruntukan_koneksi', ['RUMAH_TANGGA', 'BISNIS', 'INDUSTRI', 'SOSIAL', 'PEMERINTAH', 'RUMAH_IBADAH']);

            $table->timestamps();
        });

        // 1.5 Create 'payments' table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED'])->default('PENDING');
            $table->integer('amount');
            $table->string('ref_no')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 1.6 Create 'master_pelanggan' table (Validation Data)
        Schema::create('master_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->index();
            $table->string('nama_lengkap');
            $table->string('id_pelanggan_12')->nullable();
            $table->string('no_meter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pelanggan');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('applicant_identities');
        Schema::dropIfExists('pelanggan_profiles');
        
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }
};
