<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user exists to avoid duplicates
        if (!User::where('email', 'pelanggan@coba.com')->exists()) {
            User::create([
                'name' => 'Pelanggan Coba',
                'email' => 'pelanggan@coba.com',
                'password' => Hash::make('Password123!'),
                'role' => 'pelanggan',
            ]);
        }
    }
}
