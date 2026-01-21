<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DummyPelangganSeeder::class,  // Single source: users + master_pelanggan + master_slo
            DummyCustomerAccountRequestSeeder::class,
            
            // Deprecated - data sudah ada di DummyPelangganSeeder:
            // MasterPelangganSeeder::class,
            // MasterSloSeeder::class,
        ]);
    }
}
