# 🔧 PERBAIKAN MENYELURUH - "Loading Stuck" Issue

## 📋 **KOREKSI DIAGNOSIS**

### ❌ **Diagnosis Awal (Setengah Benar)**
> Root cause: `PelangganAuthController::showLogin` tidak cek `Auth::check()`

### ✅ **Diagnosis Lengkap (Setelah Koreksi)**
**Root Cause Sebenarnya**: Kombinasi 3 masalah:

1. **Inconsistent Auto-Redirect Logic**
   - `PelangganAuthController::showLogin()` tidak handle case pegawai yang klik "Login Pelanggan"
   - Pegawai yang sudah login tetap ditampilkan form login pelanggan → bingung

2. **Middleware Redirect yang Tidak Tegas**
   - `RequireCustomerAuth` redirect pegawai ke landing (bukan ke panel mereka)
   - Ini bikin "loading stuck" karena pegawai mental ke landing → klik login lagi → loop

3. **Satu Guard untuk Semua Role**
   - Guard `web` dipakai untuk pegawai DAN pelanggan
   - Tidak ada role-check yang tegas di route monitoring/pembayaran
   - Pegawai bisa nyasar ke area pelanggan (atau sebaliknya)

---

## 🛠️ **SOLUSI YANG DITERAPKAN**

### **1. Middleware `EnsureRole` (Baru)** ✅

**File**: `app/Http/Middleware/EnsureRole.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * 
     * Usage: EnsureRole:pelanggan
     *        EnsureRole:admin_pelayanan,unit_survey
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. If user is not logged in, let auth middleware handle it
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // 2. Check if user's role is in allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. User is logged in but has wrong role
        // Redirect to appropriate location based on their actual role
        
        // If user is pelanggan trying to access internal routes
        if ($user->role === 'pelanggan') {
            return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // If user is pegawai trying to access pelanggan routes
        $roleConfig = config('internal_roles');
        if (isset($roleConfig[$user->role])) {
            $path = $roleConfig[$user->role]['path'];
            return redirect($path)->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Fallback: abort 403
        abort(403, 'Akses ditolak. Role Anda tidak diizinkan mengakses halaman ini.');
    }
}
```

**Perilaku**:
- ✅ Kalau user tidak login → biarkan middleware `auth` yang handle
- ✅ Kalau login tapi role salah → redirect ke halaman yang benar (bukan stuck)
- ✅ Pelanggan nyasar ke internal → redirect ke landing
- ✅ Pegawai nyasar ke pelanggan → redirect ke panel mereka

---

### **2. Perbaikan `RequireCustomerAuth`** ✅

**File**: `app/Http/Middleware/RequireCustomerAuth.php`

**SEBELUM** (❌ Bermasalah):
```php
// Pegawai redirect ke landing → bikin stuck!
if (Auth::user()->role !== 'pelanggan') {
    return redirect()->route('landing', [
        'need_login' => 1,
        'from' => $request->route()?->getName() ?? 'unknown',
    ]);
}
```

**SESUDAH** (✅ Diperbaiki):
```php
// Pegawai redirect ke panel mereka
if (Auth::user()->role !== 'pelanggan') {
    $roleConfig = config('internal_roles');
    $userRole = Auth::user()->role;
    
    // Redirect pegawai to their appropriate panel
    if (isset($roleConfig[$userRole])) {
        $path = $roleConfig[$userRole]['path'];
        return redirect($path)->with('error', 'Halaman ini khusus untuk pelanggan.');
    }
    
    // Fallback: redirect to landing if role not found
    return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}
```

---

### **3. Perbaikan `PelangganAuthController::showLogin()`** ✅

**File**: `app/Http/Controllers/Auth/PelangganAuthController.php`

**SEBELUM** (❌ Setengah Benar):
```php
public function showLogin()
{
    // Hanya handle pelanggan, tidak handle pegawai
    if (Auth::check() && Auth::user()->role === 'pelanggan') {
        return redirect()->route('landing');
    }

    return view('auth.pelanggan.login');
}
```

**SESUDAH** (✅ Lebih Tegas):
```php
public function showLogin()
{
    // If already authenticated, redirect based on role
    if (Auth::check()) {
        $user = Auth::user();
        
        // If pelanggan, redirect to landing
        if ($user->role === 'pelanggan') {
            return redirect()->route('landing');
        }
        
        // If pegawai (any internal role), redirect to their panel
        $roleConfig = config('internal_roles');
        if (isset($roleConfig[$user->role])) {
            $path = $roleConfig[$user->role]['path'];
            return redirect($path)->with('info', 'Anda sudah login sebagai pegawai.');
        }
    }

    return view('auth.pelanggan.login');
}
```

**Perilaku**:
- ✅ Pelanggan yang sudah login → redirect ke landing
- ✅ Pegawai yang sudah login → redirect ke panel mereka (bukan tampil form pelanggan!)
- ✅ Guest → tampil form login pelanggan

---

### **4. Route Monitoring & Pembayaran** ✅

**File**: `routes/web.php` (baris 67-72)

```php
// Monitoring & Pembayaran (Protected)
Route::middleware(['customer.only'])->group(function () {
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/monitoring/{id}', [App\Http\Controllers\MonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
    
    // ... (tambah daya wizard, dll)
});
```

**Status**: ✅ Sudah pakai middleware `customer.only` (RequireCustomerAuth)

**Perilaku Sekarang**:
- ✅ Guest buka `/monitoring` → redirect ke landing dengan `need_login=1`
- ✅ Pegawai buka `/monitoring` → redirect ke panel pegawai dengan error message
- ✅ Pelanggan buka `/monitoring` → boleh akses

---

### **5. Middleware Registration** ✅

**File**: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'login.focus' => \App\Http\Middleware\RequireLoginAndFocus::class,
        'customer.only' => \App\Http\Middleware\RequireCustomerAuth::class,
        'role' => \App\Http\Middleware\EnsureRole::class,  // ✅ BARU
    ]);
})
```

---

## 🧪 **4 SKENARIO TESTING (WAJIB LOLOS)**

### **Skenario A: Guest** ✅

**Test**:
1. Buka DevTools (F12) → Tab Network → Preserve log
2. Klik "Login Pegawai"
3. Klik "Login Pelanggan"

**Expected**:
```
Request: GET /pegawai/login
Status: 200 OK
Type: document
→ Tampil halaman login pegawai

Request: GET /pelanggan/login
Status: 200 OK
Type: document
→ Tampil halaman login pelanggan
```

---

### **Skenario B: Login Pegawai** ✅

**Test**:
1. Login sebagai pegawai → sukses masuk panel
2. Klik back button → balik ke landing
3. Klik "Login Pegawai" lagi
4. Klik "Login Pelanggan"

**Expected**:
```
Step 3:
Request: GET /pegawai/login
Status: 302 Found
Location: /internal/{panel}
→ Langsung redirect ke panel (TIDAK STUCK!)

Step 4:
Request: GET /pelanggan/login
Status: 302 Found
Location: /internal/{panel}
→ Redirect ke panel pegawai (BUKAN tampil form pelanggan!)
```

---

### **Skenario C: Login Pelanggan** ✅

**Test**:
1. Login sebagai pelanggan → sukses ke landing/dashboard
2. Klik back button
3. Klik "Login Pelanggan" lagi

**Expected**:
```
Request: GET /pelanggan/login
Status: 302 Found
Location: /
→ Langsung redirect ke landing (TIDAK STUCK!)
```

---

### **Skenario D: Akses Menu Monitoring/Pembayaran** ✅

**Test**:
1. Guest buka `/monitoring`
2. Pegawai buka `/monitoring`
3. Pelanggan buka `/monitoring`

**Expected**:
```
Guest:
Request: GET /monitoring
Status: 302 Found
Location: /?need_login=1&focus=hero_login
→ Redirect ke landing dengan toast "harus login"

Pegawai:
Request: GET /monitoring
Status: 302 Found
Location: /internal/{panel}
→ Redirect ke panel pegawai dengan error "khusus pelanggan"

Pelanggan:
Request: GET /monitoring
Status: 200 OK
→ Boleh akses
```

---

## 📝 **POTONGAN KODE FINAL**

### **1. Route Group Monitoring + Pembayaran**

```php
// File: routes/web.php (baris 67-112)

// Monitoring & Pembayaran (Protected)
Route::middleware(['customer.only'])->group(function () {
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/monitoring/{id}', [App\Http\Controllers\MonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
    
    // Protected Permohonan Forms - Wizard Tambah Daya
    Route::get('/pelanggan/tambah-daya/step-1', [TambahDayaController::class, 'step1'])->name('tambah-daya.step1');
    Route::post('/pelanggan/tambah-daya/step-1', [TambahDayaController::class, 'storeStep1'])->name('tambah-daya.step1.store');
    
    // ... (wizard steps lainnya)
    
    // Profile Management
    Route::get('/pelanggan/profile', [PelangganProfileController::class, 'edit'])->name('pelanggan.profile');
    Route::put('/pelanggan/profile', [PelangganProfileController::class, 'update'])->name('pelanggan.profile.update');
});
```

**Middleware**: `customer.only` (RequireCustomerAuth)

---

### **2. PelangganAuthController::showLogin() Versi Final**

```php
// File: app/Http/Controllers/Auth/PelangganAuthController.php

public function showLogin()
{
    // If already authenticated, redirect based on role
    if (Auth::check()) {
        $user = Auth::user();
        
        // If pelanggan, redirect to landing
        if ($user->role === 'pelanggan') {
            return redirect()->route('landing');
        }
        
        // If pegawai (any internal role), redirect to their panel
        $roleConfig = config('internal_roles');
        if (isset($roleConfig[$user->role])) {
            $path = $roleConfig[$user->role]['path'];
            return redirect($path)->with('info', 'Anda sudah login sebagai pegawai.');
        }
    }

    return view('auth.pelanggan.login');
}
```

---

### **3. Middleware EnsureRole**

```php
// File: app/Http/Middleware/EnsureRole.php

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Usage: EnsureRole:pelanggan
     *        EnsureRole:admin_pelayanan,unit_survey
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. If user is not logged in, let auth middleware handle it
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // 2. Check if user's role is in allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. User is logged in but has wrong role
        // If user is pelanggan trying to access internal routes
        if ($user->role === 'pelanggan') {
            return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // If user is pegawai trying to access pelanggan routes
        $roleConfig = config('internal_roles');
        if (isset($roleConfig[$user->role])) {
            $path = $roleConfig[$user->role]['path'];
            return redirect($path)->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Fallback: abort 403
        abort(403, 'Akses ditolak. Role Anda tidak diizinkan mengakses halaman ini.');
    }
}
```

**Registered di**: `bootstrap/app.php` dengan alias `'role'`

---

## ⚠️ **KOREKSI DOKUMENTASI**

### **Command yang Salah** ❌
```bash
php artisan session:flush  # ❌ TIDAK ADA!
```

### **Command yang Benar** ✅
```bash
# Clear semua cache
php artisan optimize:clear

# Kalau SESSION_DRIVER=database dan mau reset semua session:
# (HATI-HATI: semua user logout!)
# Truncate tabel sessions di database

# Kalau SESSION_DRIVER=file:
# Hapus folder storage/framework/sessions/*
```

---

## 🎯 **KESIMPULAN**

### **Root Cause Lengkap**:
1. ❌ `PelangganAuthController::showLogin()` tidak handle pegawai yang klik "Login Pelanggan"
2. ❌ `RequireCustomerAuth` redirect pegawai ke landing (bukan panel mereka) → loop
3. ❌ Tidak ada role-check yang tegas di route monitoring/pembayaran

### **Solusi Diterapkan**:
1. ✅ Buat middleware `EnsureRole` yang tegas
2. ✅ Perbaiki `RequireCustomerAuth` agar redirect pegawai ke panel mereka
3. ✅ Perbaiki `PelangganAuthController::showLogin()` agar handle semua case
4. ✅ Route monitoring/pembayaran sudah pakai `customer.only` middleware

### **Prevention**:
- ✅ Selalu gunakan middleware `role:pelanggan` untuk route pelanggan
- ✅ Selalu gunakan middleware `role:admin_pelayanan,unit_survey,...` untuk route internal
- ✅ Semua `showLogin()` method harus handle semua role (bukan cuma role sendiri)
- ✅ Middleware redirect harus tegas: pegawai → panel, pelanggan → landing

---

## 📞 **NEXT STEPS**

1. **Test 4 skenario di atas** dengan DevTools Network tab
2. **Verifikasi** tidak ada redirect loop
3. **Pastikan** pegawai tidak bisa akses monitoring/pembayaran
4. **Pastikan** pelanggan tidak bisa akses internal panel

**Status**: ✅ **READY FOR TESTING**

---

**Last Updated**: 2026-01-22 12:44 WIB  
**Complexity**: 7/10 (Medium-High)
