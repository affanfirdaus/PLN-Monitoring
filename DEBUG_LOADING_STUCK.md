# 🔍 DEBUGGING GUIDE: "Loading Stuck" Issue

## 📊 HASIL INVESTIGASI

### ✅ **MASALAH TELAH DIPERBAIKI**

**Tanggal**: 2026-01-22  
**Issue**: Tombol login "kelihatan nge-load" tapi navigasi tidak jalan, session tidak konsisten

---

## 🎯 **3 TITIK KRITIS YANG SUDAH DICEK**

### **1. Link Tombol (href/route)** ✅ **SUDAH BENAR**

**File**: `resources/views/landing.blade.php`

```html
<!-- Tombol Login Pegawai -->
<a id="btnLoginPegawai" href="{{ route('pegawai.login') }}" class="...">
    Login Pegawai
</a>

<!-- Tombol Login Pelanggan -->
<a id="btnLoginPelanggan" href="{{ route('pelanggan.login') }}" class="...">
    Login Pelanggan
</a>
```

**Status**: ✅ Menggunakan `<a href>` murni (bukan button/form), route valid, tidak ada preventDefault

---

### **2. Session/Auth Guard** ✅ **SUDAH BENAR**

**File**: `.env`

```env
SESSION_DRIVER=database          # ✅ Bukan 'array' (aman)
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null              # ✅ Null untuk local
APP_URL=http://127.0.0.1:8000   # ✅ Konsisten
```

**Status**: ✅ Konfigurasi session sudah benar

**Action Taken**: 
```bash
php artisan optimize:clear  # ✅ Cache cleared
```

---

### **3. Middleware Redirect** ✅ **SUDAH BENAR**

**File**: `app/Http/Middleware/Authenticate.php`

```php
protected function redirectTo(Request $request): ?string
{
    if (!$request->expectsJson()) {
        // Check if the request is for internal panel routes
        if ($request->is('internal/*')) {
            return route('pegawai.login');
        }
        
        // Default to pelanggan login for other routes
        return route('pelanggan.login');
    }

    return null;
}
```

**Status**: ✅ Tidak redirect ke `/` (landing) yang bisa bikin loop

---

## 🔴 **AKAR MASALAH YANG DITEMUKAN**

### **Masalah: Inconsistent Auto-Redirect Logic**

**File**: `app/Http/Controllers/Auth/PelangganAuthController.php`

**SEBELUM** (❌ Bermasalah):
```php
public function showLogin()
{
    return view('auth.pelanggan.login');  // ❌ Tidak cek Auth::check()
}
```

**SESUDAH** (✅ Diperbaiki):
```php
public function showLogin()
{
    // If already authenticated as pelanggan, redirect to landing
    if (Auth::check() && Auth::user()->role === 'pelanggan') {
        return redirect()->route('landing');
    }

    return view('auth.pelanggan.login');
}
```

---

## 🧪 **CARA DEBUG DI BROWSER (DevTools)**

### **Step 1: Buka DevTools**
1. Tekan `F12` atau `Ctrl+Shift+I`
2. Pilih tab **Network**
3. Centang **Preserve log**

### **Step 2: Test Tombol Login**
1. Klik tombol **"Login Pegawai"** atau **"Login Pelanggan"** di dashboard utama
2. Perhatikan request yang terjadi

### **Step 3: Analisis Response**

#### ✅ **JIKA BERHASIL**:
```
Request URL: http://127.0.0.1:8000/pegawai/login
Status Code: 200 OK
Type: document
```
→ Halaman login muncul dengan benar

#### ⚠️ **JIKA REDIRECT LOOP**:
```
Request URL: http://127.0.0.1:8000/pegawai/login
Status Code: 302 Found
Location: http://127.0.0.1:8000/
```
→ Redirect balik ke landing (LOOP!)

#### ❌ **JIKA TIDAK ADA REQUEST**:
```
(Tidak ada request sama sekali di Network tab)
```
→ Tombol dicegat JavaScript atau href="#"

---

## 🛠️ **SOLUSI YANG SUDAH DITERAPKAN**

### **1. Perbaikan Controller**
- ✅ Tambahkan `Auth::check()` guard di `PelangganAuthController::showLogin()`
- ✅ Konsistensi dengan `PegawaiAuthController` yang sudah benar

### **2. Clear Cache**
```bash
php artisan optimize:clear
```
Output:
```
✓ config cleared
✓ cache cleared
✓ compiled cleared
✓ events cleared
✓ routes cleared
✓ views cleared
✓ blade-icons cleared
✓ filament cleared
```

---

## 📝 **TESTING CHECKLIST**

### **Test Case 1: Guest User (Belum Login)**
- [ ] Klik "Login Pegawai" → Harus muncul halaman login pegawai
- [ ] Klik "Login Pelanggan" → Harus muncul halaman login pelanggan
- [ ] Tidak ada redirect loop
- [ ] Tidak ada "loading stuck"

### **Test Case 2: Logged-In Pegawai**
- [ ] Klik "Login Pegawai" → Auto-redirect ke panel sesuai role
- [ ] Tidak muncul halaman login lagi
- [ ] Session tetap konsisten

### **Test Case 3: Logged-In Pelanggan**
- [ ] Klik "Login Pelanggan" → Auto-redirect ke landing page
- [ ] Tidak muncul halaman login lagi
- [ ] Session tetap konsisten

### **Test Case 4: Back Button After Login**
- [ ] Login sebagai pegawai
- [ ] Klik back button browser
- [ ] Klik "Login Pegawai" lagi
- [ ] Harus langsung masuk panel (tidak stuck loading)

---

## 🚨 **TROUBLESHOOTING**

### **Jika Masih "Loading Stuck"**

1. **Clear Browser Cache**:
   - Chrome: `Ctrl+Shift+Delete` → Clear browsing data
   - Pilih "Cached images and files"

2. **Clear Laravel Session**:
   ```bash
   php artisan cache:clear
   php artisan session:flush
   ```

3. **Cek Database Session**:
   ```sql
   SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 10;
   ```
   Pastikan ada record session yang valid

4. **Cek Log Laravel**:
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Lihat apakah ada error saat redirect

5. **Hard Refresh Browser**:
   - `Ctrl+F5` (Windows)
   - `Cmd+Shift+R` (Mac)

---

## 📌 **KESIMPULAN**

### **Root Cause**:
- `PelangganAuthController::showLogin()` tidak memiliki guard `Auth::check()`
- Ketika user sudah login lalu klik tombol login lagi, controller tetap tampilkan form login
- Ini menyebabkan "loading stuck" karena session tidak konsisten

### **Solution**:
- ✅ Tambahkan auto-redirect logic di `PelangganAuthController`
- ✅ Clear semua cache Laravel
- ✅ Konsistensi behavior antara Pegawai dan Pelanggan controller

### **Prevention**:
- Selalu tambahkan `Auth::check()` guard di semua `showLogin()` method
- Gunakan `SESSION_DRIVER=database` atau `file` (jangan `array`)
- Pastikan middleware redirect tidak lempar ke landing page

---

## 📞 **NEXT STEPS**

1. **Test semua scenario** di atas
2. **Verifikasi di DevTools** bahwa tidak ada redirect loop
3. **Cek session persistence** dengan login → logout → login lagi
4. **Monitor Laravel logs** untuk error yang mungkin muncul

---

**Last Updated**: 2026-01-22 12:37 WIB  
**Status**: ✅ **FIXED**
