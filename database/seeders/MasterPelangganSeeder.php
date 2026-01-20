<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 Dummy Data for Testing
        $data = [
            [
                'nik' => '3319010101010001',
                'nama_lengkap' => 'Budi Santoso',
                'id_pelanggan_12' => '512345678901',
                'no_meter' => '12345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3319010101010002',
                'nama_lengkap' => 'Siti Aminah',
                'id_pelanggan_12' => '512345678902',
                'no_meter' => '12345678902',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3319010101010003',
                'nama_lengkap' => 'Joko Widodo',
                'id_pelanggan_12' => '512345678903',
                'no_meter' => '12345678903',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3319010101010004',
                'nama_lengkap' => 'Rina Wati',
                'id_pelanggan_12' => '512345678904',
                'no_meter' => '12345678904',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3319010101010005',
                'nama_lengkap' => 'Agus Salim',
                'id_pelanggan_12' => '512345678905',
                'no_meter' => '12345678905',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('master_pelanggan')->insertOrIgnore($data);
    }
}
