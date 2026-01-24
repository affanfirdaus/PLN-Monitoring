<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InternalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing internal users (optional - be careful in production!)
        User::whereIn('role', [
            'admin_pelayanan',
            'unit_survey',
            'unit_perencanaan',
            'unit_konstruksi',
            'unit_te',
            'supervisor'
        ])->delete();

        // Create test users for each internal role
        $internalUsers = [
            [
                'name' => 'Hasan',
                'email' => 'hasan@supervisor.com',
                'password' => Hash::make('Password123!'),
                'role' => 'supervisor',
                'is_active' => true,
                'phone' => '081234567801',
                'gender' => 'L',
                'address_text' => 'Jl. Supervisor No. 1, Kudus',
            ],
            [
                'name' => 'Affan',
                'email' => 'affan@adminlayanan.com',
                'password' => Hash::make('Password123!'),
                'role' => 'admin_pelayanan',
                'is_active' => true,
                'phone' => '081234567802',
                'gender' => 'L',
                'address_text' => 'Jl. Admin Pelayanan No. 2, Kudus',
            ],
            [
                'name' => 'Budi Surveyor',
                'email' => 'budi@unitsurvey.com',
                'password' => Hash::make('Password123!'),
                'role' => 'unit_survey',
                'is_active' => true,
                'phone' => '081234567803',
                'gender' => 'L',
                'address_text' => 'Jl. Survey No. 3, Kudus',
            ],
            [
                'name' => 'Citra Planner',
                'email' => 'citra@unitperencanaan.com',
                'password' => Hash::make('Password123!'),
                'role' => 'unit_perencanaan',
                'is_active' => true,
                'phone' => '081234567804',
                'gender' => 'P',
                'address_text' => 'Jl. Perencanaan No. 4, Kudus',
            ],
            [
                'name' => 'Dedi Konstruktor',
                'email' => 'dedi@unitkonstruksi.com',
                'password' => Hash::make('Password123!'),
                'role' => 'unit_konstruksi',
                'is_active' => true,
                'phone' => '081234567805',
                'gender' => 'L',
                'address_text' => 'Jl. Konstruksi No. 5, Kudus',
            ],
            [
                'name' => 'Eka Teknisi',
                'email' => 'eka@unitte.com',
                'password' => Hash::make('Password123!'),
                'role' => 'unit_te',
                'is_active' => true,
                'phone' => '081234567806',
                'gender' => 'P',
                'address_text' => 'Jl. TE No. 6, Kudus',
            ],
        ];

        foreach ($internalUsers as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ Created 6 internal staff users successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   - hasan@supervisor.com / Password123!');
        $this->command->info('   - affan@adminlayanan.com / Password123!');
        $this->command->info('   - budi@unitsurvey.com / Password123!');
        $this->command->info('   - citra@unitperencanaan.com / Password123!');
        $this->command->info('   - dedi@unitkonstruksi.com / Password123!');
        $this->command->info('   - eka@unitte.com / Password123!');
    }
}
