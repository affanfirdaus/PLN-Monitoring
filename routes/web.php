<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Dummy routes biar tombol jalan dulu
Route::get('/pelanggan/login', function () {
    return 'Login Pelanggan (dummy)';
})->name('pelanggan.login');

Route::get('/pegawai/login', function () {
    return 'Login Pegawai (dummy)';
})->name('pegawai.login');
