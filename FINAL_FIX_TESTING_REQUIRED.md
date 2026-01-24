# 🔧 PERBAIKAN FINAL - "Loading Stuck" Issue (REVISI)

## ⚠️ **STATUS: MENUNGGU TESTING**

**PENTING**: Status **BELUM "FIXED"** sampai 4 skenario testing lulus!

---

## 📋 **3 PERBAIKAN KRITIS (Revisi)**

### **1. EnsureRole: Guest TIDAK Boleh Lewat** ✅ **FIXED**

**Masalah Sebelumnya**: ❌ Guest bisa lewat → CELAH KEAMANAN!
```php
// ❌ SALAH - Guest lewat begitu saja
if (!Auth::check()) {
    return $next($request);  // BAHAYA!
}
```

**Solusi Sekarang**: ✅ Guest redirect ke login yang sesuai
```php
// ✅ BENAR - Guest redirect ke login
if (!Auth::check()) {
    // Internal routes → pegawai.login
    if ($request->is('internal/*')) {
        return redirect()->route('pegawai.login');
    }
    
    // Other routes → pelanggan.login
    return redirect()->route('pelanggan.login');
}
```

---

### **2. RequireCustomerAuth: Gate Pelanggan yang Tegas** ✅ **SUDAH BENAR**

**Perilaku**:
- ✅ Guest → redirect `pelanggan.login`
- ✅ Login tapi bukan pelanggan (pegawai) → redirect ke **panel pegawai** (bukan landing!)
- ✅ Pelanggan → allow

**Kode**:
```php
// Guest → redirect landing dengan need_login
if (!Auth::check()) {
    return redirect()->route('landing', [
        'need_login' => 1,
        'focus' => 'hero_login',
        'from' => $request->route()?->getName() ?? 'unknown',
    ]);
}

// Pegawai → redirect ke panel mereka (BUKAN landing!)
if (Auth::user()->role !== 'pelanggan') {
    $roleConfig = config('internal_roles');
    $userRole = Auth::user()->role;
    
    if (isset($roleConfig[$userRole])) {
        $path = $roleConfig[$userRole]['path'];
        return redirect($path)->with('error', 'Halaman ini khusus untuk pelanggan.');
    }
    
    return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}

// Pelanggan → allow
return $next($request);
```

---

### **3. PegawaiAuthController::showLogin() Konsisten** ✅ **FIXED**

**Sebelumnya**: ❌ Tidak handle pelanggan yang klik "Login Pegawai"
```php
// ❌ TIDAK HANDLE PELANGGAN
if (Auth::check()) {
    return $this->redirectToPanel(Auth::user());  // Pelanggan juga masuk sini!
}
```

**Sekarang**: ✅ Handle semua case
```php
// ✅ HANDLE SEMUA ROLE
if (Auth::check()) {
    $user = Auth::user();
    
    // Pegawai → redirect panel mereka
    $roleConfig = config('internal_roles');
    if (isset($roleConfig[$user->role])) {
        return $this->redirectToPanel($user);
    }
    
    // Pelanggan → redirect landing
    if ($user->role === 'pelanggan') {
        return redirect()->route('landing')->with('info', 'Anda sudah login sebagai pelanggan.');
    }
}

return view('auth.pegawai-login');
```

---

## 📝 **4 POTONGAN KODE FINAL**

### **1. EnsureRole.php (FINAL)**

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
     * Usage: middleware('role:pelanggan')
     *        middleware('role:admin_pelayanan,unit_survey')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Guest → redirect to appropriate login based on context
        if (!Auth::check()) {
            // Internal routes → pegawai.login
            if ($request->is('internal/*')) {
                return redirect()->route('pegawai.login');
            }
            
            // Other routes → pelanggan.login
            return redirect()->route('pelanggan.login');
        }

        $user = Auth::user();
        
        // 2. Role match → allow
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Role mismatch → redirect to appropriate location
        
        // Pelanggan trying to access internal routes → landing
        if ($user->role === 'pelanggan') {
            return redirect()->route('landing')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Pegawai trying to access pelanggan routes → their panel
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

**Registered**: `bootstrap/app.php` dengan alias `'role'`

**Perilaku**:
- ✅ Guest → redirect login (internal/* → pegawai, lainnya → pelanggan)
- ✅ Role match → lanjut
- ✅ Role salah → redirect ke tempat yang benar (TIDAK STUCK!)

---

### **2. RequireCustomerAuth.php (FINAL)**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireCustomerAuth
{
    /**
     * Handle an incoming request.
     * Gate untuk route pelanggan (monitoring, pembayaran, dll)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Guest → redirect to landing with need_login flag
        if (!Auth::check()) {
            return redirect()->route('landing', [
                'need_login' => 1,
                'focus' => 'hero_login',
                'from' => $request->route()?->getName() ?? 'unknown',
            ]);
        }

        // 2. Non-pelanggan (pegawai) → redirect to their panel
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

        // 3. Pelanggan → allow
        return $next($request);
    }
}
```

**Registered**: `bootstrap/app.php` dengan alias `'customer.only'`

**Perilaku**:
- ✅ Guest → landing dengan `need_login=1`
- ✅ Pegawai → redirect ke **panel mereka** (BUKAN landing!)
- ✅ Pelanggan → allow

---

### **3. PelangganAuthController::showLogin() (FINAL)**

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

**Perilaku**:
- ✅ Pelanggan sudah login → redirect landing
- ✅ Pegawai sudah login → redirect **panel mereka** (BUKAN tampil form!)
- ✅ Guest → tampil form login pelanggan

---

### **4. Route Group Monitoring + Pembayaran (FINAL)**

```php
// File: routes/web.php (baris 67-112)

// Monitoring & Pembayaran (Protected - Pelanggan Only)
Route::middleware(['customer.only'])->group(function () {
    Route::get('/monitoring', [App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/monitoring/{id}', [App\Http\Controllers\MonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
    
    // Protected Permohonan Forms - Wizard Tambah Daya
    Route::get('/pelanggan/tambah-daya/step-1', [TambahDayaController::class, 'step1'])->name('tambah-daya.step1');
    Route::post('/pelanggan/tambah-daya/step-1', [TambahDayaController::class, 'storeStep1'])->name('tambah-daya.step1.store');
    
    Route::get('/pelanggan/tambah-daya/step-2', [TambahDayaController::class, 'step2'])->name('tambah-daya.step2');
    Route::post('/pelanggan/tambah-daya/step-2', [TambahDayaController::class, 'storeStep2'])->name('tambah-daya.step2.store');
    
    Route::get('/pelanggan/tambah-daya/step-3', [TambahDayaController::class, 'step3'])->name('tambah-daya.step3');
    Route::post('/pelanggan/tambah-daya/step-3', [TambahDayaController::class, 'storeStep3'])->name('tambah-daya.step3.store');
    
    Route::get('/pelanggan/tambah-daya/step-4', [TambahDayaController::class, 'step4'])->name('tambah-daya.step4');
    Route::post('/pelanggan/tambah-daya/step-4', [TambahDayaController::class, 'storeStep4'])->name('tambah-daya.step4.store');
    
    Route::get('/pelanggan/tambah-daya/step-5', [TambahDayaController::class, 'step5'])->name('tambah-daya.step5');
    Route::post('/pelanggan/tambah-daya/step-5', [TambahDayaController::class, 'storeStep5'])->name('tambah-daya.step5.store');
    
    // Profile Management
    Route::get('/pelanggan/profile', [PelangganProfileController::class, 'edit'])->name('pelanggan.profile');
    Route::put('/pelanggan/profile', [PelangganProfileController::class, 'update'])->name('pelanggan.profile.update');
    
    // Draft & Verification Routes
    Route::post('/pelanggan/permohonan/{id}/autosave', [TambahDayaController::class, 'autosave'])->name('tambah-daya.autosave');
    Route::get('/pelanggan/permohonan/{id}/resume', [TambahDayaController::class, 'resume'])->name('tambah-daya.resume');
    Route::delete('/pelanggan/permohonan/{id}/cancel', [TambahDayaController::class, 'cancel'])->name('tambah-daya.cancel');
    
    Route::post('/pelanggan/tambah-daya/check-nik', [TambahDayaController::class, 'checkNik'])->name('tambah-daya.check-nik');
    Route::post('/pelanggan/tambah-daya/check-slo', [TambahDayaController::class, 'checkSlo'])->name('tambah-daya.check-slo');
    Route::post('/pelanggan/tambah-daya/verify-kk', [TambahDayaController::class, 'verifyKK'])->name('tambah-daya.verify-kk');
    Route::post('/pelanggan/tambah-daya/verify-npwp', [TambahDayaController::class, 'verifyNPWP'])->name('tambah-daya.verify-npwp');
});
```

**Middleware**: `customer.only` (RequireCustomerAuth)

**Perilaku**:
- ✅ Guest → landing dengan `need_login=1`
- ✅ Pegawai → redirect ke panel mereka dengan error
- ✅ Pelanggan → allow

---

## 🧪 **4 SKENARIO TESTING (WAJIB LOLOS)**

### **Skenario A: Guest**

**Test**:
1. Buka DevTools (F12) → Tab Network → ✅ Preserve log
2. Klik "Login Pegawai"
3. Klik "Login Pelanggan"

**Expected**:
```
Klik "Login Pegawai":
Request: GET /pegawai/login
Status: 200 OK
Type: document
Final URL: http://127.0.0.1:8000/pegawai/login
→ Tampil halaman login pegawai

Klik "Login Pelanggan":
Request: GET /pelanggan/login
Status: 200 OK
Type: document
Final URL: http://127.0.0.1:8000/pelanggan/login
→ Tampil halaman login pelanggan
```

---

### **Skenario B: Sudah Login Pegawai**

**Test**:
1. Login sebagai pegawai → sukses masuk panel
2. Klik back button → balik ke landing
3. Klik "Login Pegawai" lagi
4. Klik "Login Pelanggan"

**Expected**:
```
Step 3 - Klik "Login Pegawai":
Request: GET /pegawai/login
Status: 302 Found
Location: /internal/{panel_id}
Final URL: http://127.0.0.1:8000/internal/{panel_id}
→ Langsung redirect ke panel (TIDAK STUCK!)

Step 4 - Klik "Login Pelanggan":
Request: GET /pelanggan/login
Status: 302 Found
Location: /internal/{panel_id}
Final URL: http://127.0.0.1:8000/internal/{panel_id}
→ Redirect ke panel pegawai (BUKAN tampil form pelanggan!)
```

---

### **Skenario C: Sudah Login Pelanggan**

**Test**:
1. Login sebagai pelanggan → sukses ke landing/dashboard
2. Klik back button
3. Klik "Login Pelanggan" lagi

**Expected**:
```
Klik "Login Pelanggan":
Request: GET /pelanggan/login
Status: 302 Found
Location: /
Final URL: http://127.0.0.1:8000/
→ Langsung redirect ke landing (TIDAK STUCK!)
```

---

### **Skenario D: Akses Menu Monitoring/Pembayaran**

**Test**:
1. Guest buka `/monitoring`
2. Pegawai buka `/monitoring`
3. Pelanggan buka `/monitoring`

**Expected**:
```
Guest:
Request: GET /monitoring
Status: 302 Found
Location: /?need_login=1&focus=hero_login&from=monitoring
Final URL: http://127.0.0.1:8000/?need_login=1&focus=hero_login&from=monitoring
→ Redirect ke landing dengan toast "harus login"

Pegawai:
Request: GET /monitoring
Status: 302 Found
Location: /internal/{panel_id}
Final URL: http://127.0.0.1:8000/internal/{panel_id}
→ Redirect ke panel pegawai (BUKAN landing, BUKAN stuck!)

Pelanggan:
Request: GET /monitoring
Status: 200 OK
Final URL: http://127.0.0.1:8000/monitoring
→ Boleh akses
```

---

## 📊 **CHECKLIST VALIDASI**

Sebelum klaim "FIXED", pastikan:

- [ ] **Skenario A** lulus (guest bisa akses form login)
- [ ] **Skenario B** lulus (pegawai tidak stuck, tidak lihat form pelanggan)
- [ ] **Skenario C** lulus (pelanggan tidak stuck)
- [ ] **Skenario D** lulus (pegawai tidak bisa akses monitoring, redirect ke panel)
- [ ] Tidak ada redirect loop di semua skenario
- [ ] Back button tetap konsisten setelah login
- [ ] Pegawai dan pelanggan tidak bisa nyasar ke area masing-masing

---

## 🎯 **KESIMPULAN**

### **Perbaikan yang Diterapkan**:
1. ✅ **EnsureRole**: Guest redirect ke login (TIDAK lewat begitu saja!)
2. ✅ **RequireCustomerAuth**: Pegawai redirect ke panel (BUKAN landing!)
3. ✅ **PegawaiAuthController**: Handle pelanggan yang klik "Login Pegawai"
4. ✅ **Route monitoring/pembayaran**: Pakai `customer.only` middleware

### **Celah Keamanan yang Ditutup**:
- ❌ Guest tidak bisa lewat middleware `role` tanpa login
- ❌ Pegawai tidak bisa akses monitoring/pembayaran
- ❌ Pelanggan tidak bisa akses internal panel

### **Konsistensi yang Dijaga**:
- ✅ Tombol login selalu responsif (tidak stuck/loop)
- ✅ Back button tetap konsisten
- ✅ Redirect selalu ke tempat yang benar

---

## ⚠️ **STATUS**

**BELUM "FIXED"** - Menunggu hasil testing 4 skenario!

Silakan jalankan 4 skenario di atas dengan DevTools Network (preserve log ON) dan kirim hasil:
- Status code
- Final URL
- Screenshot jika ada yang tidak sesuai expected

---

**Last Updated**: 2026-01-22 12:52 WIB  
**Complexity**: 8/10 (High)  
**Security**: Critical fixes applied
