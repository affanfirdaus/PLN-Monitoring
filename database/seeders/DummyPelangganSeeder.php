<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * SINGLE SOURCE OF TRUTH untuk semua dummy data pelanggan
 * Semua data user, master_pelanggan, dan master_slo ADA DI SINI
 */
class DummyPelangganSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (ORDER MATTERS - delete children first!)
        // 1. Delete service_requests that reference users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_requests')->truncate();
        DB::table('applicant_identities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // 2. Delete master data
        DB::table('master_slo')->truncate();
        DB::table('master_pelanggan')->truncate();
        
        // 3. Delete pelanggan users (now safe)
        User::where('role', 'pelanggan')->delete();

        /*
        |--------------------------------------------------------------------------
        | DATA FINAL - 5 PELANGGAN
        |--------------------------------------------------------------------------
        */
        $pelanggan = [
            [
                // USER LOGIN
                'name' => 'Budi Santoso',
                'email' => 'pelanggan1@kudus.id',
                'nik' => '3319010101010001',
                'password' => 'Password123!',
                
                // MASTER PELANGGAN PLN
                'id_pelanggan' => '512345678901',
                'no_meter' => '12345678901',
                'no_hp' => '081234567801',
                'no_kk' => '3319010101010001',
                'npwp' => '1234567890123456', // 16 digits (new format)
                
                // Alamat Instalasi
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Kudus',
                'kecamatan' => 'Kota',
                'kelurahan' => 'Mlati Lor',
                'rt' => '008',
                'rw' => '012',
                'alamat_detail' => 'Jl. Jenderal Sudirman No. 12, Perumahan Melati Asri',
                
                // SLO
                'slo_reg' => 'SLO-REG-2026-000241',
                'slo_cert' => 'SLO-CERT-2026-KUDUS-12001',
                'slo_lembaga' => 'Lembaga Inspeksi Teknik Kudus',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'pelanggan2@kudus.id',
                'nik' => '3319010101010002',
                'password' => 'Password123!',
                
                'id_pelanggan' => '512345678902',
                'no_meter' => '12345678902',
                'no_hp' => '081234567802',
                'no_kk' => '3319010101010002',
                'npwp' => '2345678901234567', // 16 digits (new format)
                
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Kudus',
                'kecamatan' => 'Kota',
                'kelurahan' => 'Kauman',
                'rt' => '009',
                'rw' => '011',
                'alamat_detail' => 'Jl. Sunan Kudus No. 7, Gang Masjid No. 3',
                
                'slo_reg' => 'SLO-REG-2026-000242',
                'slo_cert' => 'SLO-CERT-2026-KUDUS-12002',
                'slo_lembaga' => 'Lembaga Inspeksi Teknik Kudus',
            ],
            [
                'name' => 'Affan Sholeh Firdaus',
                'email' => 'pelanggan3@kudus.id',
                'nik' => '3319010101010003',
                'password' => 'Password123!',
                
                'id_pelanggan' => '512345678903',
                'no_meter' => '12345678903',
                'no_hp' => '081234567803',
                'no_kk' => '3319010101010003',
                'npwp' => '3456789012345672', // 16 digits (new format) - Updated
                
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Kudus',
                'kecamatan' => 'Kota',
                'kelurahan' => 'Rendeng',
                'rt' => '003',
                'rw' => '004',
                'alamat_detail' => 'Jl. Ahmad Yani No. 25',
                
                'slo_reg' => 'SLO-REG-2025-913402',
                'slo_cert' => 'SLO-CERT-2025-BOGOR-00019',
                'slo_lembaga' => 'Lembaga Inspeksi Teknik Nasional',
            ],
            [
                'name' => 'Rina Wati',
                'email' => 'pelanggan4@kudus.id',
                'nik' => '3319010101010004',
                'password' => 'Password123!',
                
                'id_pelanggan' => '512345678904',
                'no_meter' => '12345678904',
                'no_hp' => '081234567804',
                'no_kk' => '3319010101010004',
                'npwp' => '4567890123456789', // 16 digits (new format)
                
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Kudus',
                'kecamatan' => 'Kota',
                'kelurahan' => 'Demaan',
                'rt' => '006',
                'rw' => '007',
                'alamat_detail' => 'Jl. Diponegoro No. 5, Komplek Diponegoro Residence',
                
                'slo_reg' => 'SLO-REG-2024-450110',
                'slo_cert' => 'SLO-CERT-2024-SIANT-54321',
                'slo_lembaga' => 'Lembaga Inspeksi Teknik Sumut',
            ],
            [
                'name' => 'Agus Salim',
                'email' => 'pelanggan5@kudus.id',
                'nik' => '3319010101010005',
                'password' => 'Password123!',
                
                'id_pelanggan' => '512345678905',
                'no_meter' => '12345678905',
                'no_hp' => '081234567805',
                'no_kk' => '3319010101010005',
                'npwp' => '5678901234567890', // 16 digits (new format)
                
                'provinsi' => 'Jawa Tengah',
                'kab_kota' => 'Kudus',
                'kecamatan' => 'Mejobo',
                'kelurahan' => 'Kesambi',
                'rt' => '010',
                'rw' => '008',
                'alamat_detail' => 'Jl. Raya Mejobo Km. 2 No. 18',
                
                'slo_reg' => 'SLO-REG-2023-777001',
                'slo_cert' => 'SLO-CERT-2023-KUDUS-10010',
                'slo_lembaga' => 'Lembaga Inspeksi Teknik Kudus',
            ],
        ];

        foreach ($pelanggan as $p) {
            // 1. CREATE USER ACCOUNT
            User::create([
                'name' => $p['name'],
                'email' => $p['email'],
                'nik' => $p['nik'],
                'role' => 'pelanggan',
                'password' => Hash::make($p['password']),
            ]);

            // 2. CREATE MASTER PELANGGAN
            DB::table('master_pelanggan')->insert([
                'nama_lengkap' => strtoupper($p['name']), // Uppercase untuk konsistensi
                'nik' => $p['nik'],
                'id_pelanggan_12' => $p['id_pelanggan'],
                'no_meter' => $p['no_meter'],
                'no_hp' => $p['no_hp'],
                'no_kk' => $p['no_kk'],
                'npwp' => $p['npwp'],
                
                'provinsi' => $p['provinsi'],
                'kab_kota' => $p['kab_kota'],
                'kecamatan' => $p['kecamatan'],
                'kelurahan' => $p['kelurahan'],
                'rt' => $p['rt'],
                'rw' => $p['rw'],
                'alamat_detail' => $p['alamat_detail'],
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. CREATE MASTER SLO
            DB::table('master_slo')->insert([
                'no_registrasi_slo' => $p['slo_reg'],
                'no_sertifikat_slo' => $p['slo_cert'],
                'nama_lembaga' => $p['slo_lembaga'],
                'nik_pemilik' => $p['nik'],
                'nama_pemilik' => strtoupper($p['name']), // Uppercase untuk konsistensi
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✓ Created 5 pelanggan with consistent data across users, master_pelanggan, and master_slo');
    }
}
