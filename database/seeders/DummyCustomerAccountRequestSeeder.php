<?php

namespace Database\Seeders;

use App\Models\CustomerAccountRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyCustomerAccountRequestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a pending request
        if (!CustomerAccountRequest::where('email', 'pelanggan@coba.com')->exists()) {
            CustomerAccountRequest::create([
                'full_name' => 'Calon Pelanggan 1',
                'email' => 'pelanggan@coba.com',
                'phone' => '081234567890',
                'gender' => 'L',
                'address_text' => 'Jl. Mawar No. 12, RT 01 RW 02',
                'province' => 'Jawa Tengah',
                'regency' => 'Kudus',
                'district' => 'Kota Kudus',
                'village' => 'Melati',
                'postal_code' => '59312',
                'password_hash' => Hash::make('Password123!'),
                'status' => 'pending',
            ]);
        }
    }
}
