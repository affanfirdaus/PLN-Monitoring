<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin PLN',
            'email' => 'admin@pln-kudus.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'gender' => 'L',
            'address_text' => 'Jl. Admin No. 1, Kudus',
        ]);

        // Create Pegawai User
        User::create([
            'name' => 'Pegawai PLN',
            'email' => 'pegawai@pln-kudus.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'phone' => '081234567891',
            'gender' => 'L',
            'address_text' => 'Jl. Pegawai No. 2, Kudus',
        ]);

        // Create Dummy Pelanggan Users
        User::create([
            'name' => 'Pelanggan Satu',
            'email' => 'pelanggan1@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '081234567892',
            'gender' => 'L',
            'address_text' => 'Jl. Pelanggan No. 3, Kudus',
        ]);

        User::create([
            'name' => 'Pelanggan Dua',
            'email' => 'pelanggan2@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '081234567893',
            'gender' => 'P',
            'address_text' => 'Jl. Pelanggan No. 4, Kudus',
        ]);

        User::create([
            'name' => 'Pelanggan Tiga',
            'email' => 'pelanggan3@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'phone' => '081234567894',
            'gender' => 'L',
            'address_text' => 'Jl. Pelanggan No. 5, Kudus',
        ]);
    }
}