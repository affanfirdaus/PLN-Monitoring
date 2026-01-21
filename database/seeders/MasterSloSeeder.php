<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DEPRECATED - Data sudah dipindah ke DummyPelangganSeeder
 * File ini dikosongkan untuk menghindari duplikasi data
 */
class MasterSloSeeder extends Seeder
{
    public function run(): void
    {
        // INTENTIONALLY EMPTY
        // All data moved to DummyPelangganSeeder for single source of truth
        $this->command->warn('⚠ MasterSloSeeder is deprecated. Data is in DummyPelangganSeeder.');
    }
}
