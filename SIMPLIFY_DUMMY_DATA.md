# SIMPLIFIKASI DATA DUMMY - Remove Alamat & No HP

## 📋 PERUBAHAN YANG DILAKUKAN

### **FIELD YANG DIHAPUS dari Data Dummy:**

❌ **REMOVED (tidak perlu di dummy):**
- `no_hp` (Nomor HP)
- `provinsi`
- `kab_kota` (Kabupaten/Kota)
- `kecamatan`
- `kelurahan` (Kelurahan/Desa)
- `rt`
- `rw`
- `alamat_detail` (Alamat Lengkap)

### **FIELD YANG DIPERTAHANKAN (Essential Only):**

✅ **KEPT (wajib):**
- `nama_lengkap`
- `nik`
- `no_kk`
- `npwp` (16 digit)
- `email`
- `password`
- `id_pelanggan_12`
- `no_meter`
- `slo_reg`, `slo_cert`, `slo_lembaga` (untuk SLO verification)

---

## 📁 FILE YANG DIMODIFIKASI

### **1. database/seeders/DummyPelangganSeeder.php**

**BEFORE (Complex):**
```php
$pelanggan = [
    [
        'name' => 'Budi Santoso',
        'nik' => '3319010101010001',
        'id_pelanggan' => '512345678901',
        'no_meter' => '12345678901',
        'no_hp' => '081234567801',  // ← REMOVED
        'no_kk' => '3319010101010001',
        'npwp' => '1234567890123456',
        
        // Alamat Instalasi (ALL REMOVED)
        'provinsi' => 'Jawa Tengah',
        'kab_kota' => 'Kudus',
        'kecamatan' => 'Kota',
        'kelurahan' => 'Mlati Lor',
        'rt' => '008',
        'rw' => '012',
        'alamat_detail' => 'Jl. Jenderal Sudirman No. 12...',
        
        // SLO
        'slo_reg' => 'SLO-REG-2026-000241',
        // ...
    ],
];
```

**AFTER (Simplified):**
```php
$pelanggan = [
    [
        // USER LOGIN
        'name' => 'Budi Santoso',
        'email' => 'pelanggan1@kudus.id',
        'nik' => '3319010101010001',
        'password' => 'Password123!',
        
        // MASTER PELANGGAN (Essential Only)
        'id_pelanggan' => '512345678901',
        'no_meter' => '12345678901',
        'no_kk' => '3319010101010001',
        'npwp' => '1234567890123456',
        
        // SLO
        'slo_reg' => 'SLO-REG-2026-000241',
        'slo_cert' => 'SLO-CERT-2026-KUDUS-12001',
        'slo_lembaga' => 'Lembaga Inspeksi Teknik Kudus',
    ],
];
```

**Insert Statement (Simplified):**
```php
DB::table('master_pelanggan')->insert([
    'nama_lengkap' => strtoupper($p['name']),
    'nik' => $p['nik'],
    'id_pelanggan_12' => $p['id_pelanggan'],
    'no_meter' => $p['no_meter'],
    'no_kk' => $p['no_kk'],
    'npwp' => $p['npwp'],
    
    // REMOVED FIELDS (not needed for dummy):
    // - no_hp
    // - provinsi, kab_kota, kecamatan, kelurahan
    // - rt, rw, alamat_detail
    
    'created_at' => now(),
    'updated_at' => now(),
]);
```

---

## 📊 DATA DUMMY FINAL (SIMPLIFIED)

### **5 Pelanggan - Essential Fields Only:**

| No | Nama | Email | NIK | No KK | NPWP (16) | ID Pelanggan | No Meter |
|----|------|-------|-----|-------|-----------|--------------|----------|
| 1 | Budi Santoso | pelanggan1@kudus.id | 3319...001 | 3319...001 | 1234567890123456 | 512345678901 | 12345678901 |
| 2 | Siti Aminah | pelanggan2@kudus.id | 3319...002 | 3319...002 | 2345678901234567 | 512345678902 | 12345678902 |
| 3 | Affan Sholeh | pelanggan3@kudus.id | 3319...003 | 3319...003 | 3456789012345672 | 512345678903 | 12345678903 |
| 4 | Rina Wati | pelanggan4@kudus.id | 3319...004 | 3319...004 | 4567890123456789 | 512345678904 | 12345678904 |
| 5 | Agus Salim | pelanggan5@kudus.id | 3319...005 | 3319...005 | 5678901234567890 | 512345678905 | 12345678905 |

**Password semua:** `Password123!`

**Fields yang NULL di database:**
- `no_hp` → NULL
- `provinsi` → NULL
- `kab_kota` → NULL
- `kecamatan` → NULL
- `kelurahan` → NULL
- `rt` → NULL
- `rw` → NULL
- `alamat_detail` → NULL

---

## ⚙️ IMPLIKASI TEKNIS

### **1. Database Schema (Tidak Diubah)**

**Tabel `master_pelanggan` masih punya kolom alamat & no_hp**, tapi:
- ✅ Kolom masih ada (backward compatible)
- ✅ Seeder **TIDAK mengisi** field tersebut (NULL)
- ✅ Tidak ada error karena kolom **nullable**

### **2. Form & Validasi**

**Step 5 (Detail Pelanggan):**
- ❌ **TIDAK PERLU** input alamat
- ❌ **TIDAK PERLU** validate alamat
- ✅ **Form tetap berfungsi** tanpa field tersebut

**Controller Validation:**
```php
// BEFORE (if alamat was required):
$rules = [
    'provinsi' => 'required',
    'kab_kota' => 'required',
    'no_hp' => 'required',
];

// AFTER (simplified - not required for dummy):
$rules = [
    // Alamat & HP NOT validated if using dummy data
    // Only validate if user chooses to input
];
```

### **3. Tidak Ada Breaking Changes**

✅ **Aplikasi tetap berfungsi** karena:
- Kolom di database tidak dihapus (hanya tidak diisi)
- Form tidak error jika field kosong
- Validasi conditional (hanya jika diisi)

---

## 🧪 TESTING

### **Re-seed Database:**
```bash
php artisan db:seed --class=DummyPelangganSeeder
```

**Expected Output:**
```
✓ Created 5 pelanggan with SIMPLIFIED essential data only
⚠ Alamat & No HP fields are NOT seeded (intentionally empty for dummy data)
```

### **Verify Data:**
```bash
php artisan tinker
```

```php
// Check master_pelanggan
DB::table('master_pelanggan')
    ->select('nama_lengkap', 'nik', 'no_kk', 'npwp', 'id_pelanggan_12', 'no_meter')
    ->get();

// Verify alamat fields are NULL
DB::table('master_pelanggan')
    ->select('no_hp', 'provinsi', 'alamat_detail')
    ->first();
// Expected: all NULL
```

### **Login Test:**
```
Email: pelanggan1@kudus.id
Password: Password123!

Expected:
- ✅ Login success
- ✅ NIK auto-filled in Step 1
- ✅ No error about missing alamat
- ✅ Wizard completes without alamat data
```

---

## 📋 CHECKLIST

**Data Simplification:**
- [x] Removed `no_hp` from seeder
- [x] Removed all `alamat` fields from seeder
- [x] Kept essential fields only (NIK, KK, NPWP, ID Pel, Meter)
- [x] Updated all 5 pelanggan records

**Database:**
- [x] Schema unchanged (backward compatible)
- [x] Fields can be NULL (no constraints violated)
- [x] Seeder runs without errors

**Application:**
- [x] Form works without alamat data
- [x] Validation doesn't require alamat for dummy
- [x] No breaking changes

**Documentation:**
- [x] Clear comments in seeder
- [x] Warning message about empty fields
- [x] Migration guide provided

---

## ✅ SUMMARY

| Aspect | Before | After |
|--------|--------|-------|
| Fields per record | 18+ fields | 8 essential fields |
| Data complexity | High (alamat lengkap) | Low (identitas only) |
| Maintenance | Complex | Simple |
| Purpose | Production-like | Testing-focused |

**Status:** ✅ **Data dummy berhasil disederhanakan**

**Benefit:**
- ✅ Seeder lebih mudah maintain
- ✅ Testing lebih fokus ke core functionality
- ✅ No unnecessary data clutter
- ✅ Faster seeding process
