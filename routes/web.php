<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PelangganAuthController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [PelangganAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PelangganAuthController::class, 'login'])->name('login.submit'); // Explicit name
        Route::get('/register', [PelangganAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [PelangganAuthController::class, 'register'])->name('register.submit'); // Explicit name
        Route::get('/register/success', [PelangganAuthController::class, 'showRegisterPending'])->name('register.pending');
    });

    Route::middleware(['auth', CheckRole::class . ':pelanggan'])->group(function () {
        Route::post('/logout', [PelangganAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', function () {
            return view('pelanggan.dashboard');
        })->name('dashboard');
        Route::get('/profil', [App\Http\Controllers\PelangganProfileController::class, 'show'])->name('profile');
    });
});

// Admin Helper Routes (Simple Blade implementation for now)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/permintaan-akun-pelanggan', [App\Http\Controllers\Admin\CustomerRequestController::class, 'index'])->name('requests.index');
    Route::get('/permintaan-akun-pelanggan/{id}', [App\Http\Controllers\Admin\CustomerRequestController::class, 'show'])->name('requests.show');
    Route::post('/permintaan-akun-pelanggan/{id}/approve', [App\Http\Controllers\Admin\CustomerRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/permintaan-akun-pelanggan/{id}/reject', [App\Http\Controllers\Admin\CustomerRequestController::class, 'reject'])->name('requests.reject');
});

// Pegawai dummy route for now
// Pegawai Login Unit Selection
Route::get('/pegawai/login', [App\Http\Controllers\PegawaiUnitController::class, 'index'])->name('pegawai.login');

// Monitoring & Pembayaran (Protected)
Route::middleware(['customer.only'])->group(function () {
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
    
    // Protected Permohonan Forms
    Route::get('/permohonan/tambah-daya', [App\Http\Controllers\PermohonanController::class, 'tambahDayaForm'])->name('permohonan.tambah-daya');
    Route::get('/permohonan/pasang-baru', [App\Http\Controllers\PermohonanController::class, 'pasangBaruForm'])->name('permohonan.pasang-baru');
});

// Public Info Routes for Layanan
Route::get('/layanan/tambah-daya', [App\Http\Controllers\LayananInfoController::class, 'tambahDaya'])->name('layanan.tambah-daya.info');
Route::get('/layanan/pasang-baru', [App\Http\Controllers\LayananInfoController::class, 'pasangBaru'])->name('layanan.pasang-baru.info');
