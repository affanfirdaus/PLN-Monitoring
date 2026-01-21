# LOKASI PENYIMPANAN NPWP - Database Structure

## 📊 NPWP Affan Sholeh Firdaus - UPDATE

### **Data Baru:**
```
Nama: Affan Sholeh Firdaus
Email: pelanggan3@kudus.id
NIK: 3319010101010003
NPWP BARU: 3456789012345672  (16 digit)
```

---

## 📁 LOKASI PENYIMPANAN NPWP

### **1. FILE SEEDER (Source Code)**

**File:** `database/seeders/DummyPelangganSeeder.php`  
**Line:** 91

```php
[
    'name' => 'Affan Sholeh Firdaus',
    'email' => 'pelanggan3@kudus.id',
    'nik' => '3319010101010003',
    'npwp' => '3456789012345672', // ← UPDATED!
    // ... data lainnya
]
```

**Fungsi:**
- Ini adalah **SUMBER DATA** untuk seeding database
- Dijalankan saat `php artisan db:seed --class=DummyPelangganSeeder`
- Memasukkan data ke tabel database

---

### **2. TABEL DATABASE (Runtime Storage)**

**Nama Tabel:** `master_pelanggan`  
**Kolom NPWP:** `npwp`

**Struktur Tabel:**
```sql
CREATE TABLE master_pelanggan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_lengkap VARCHAR(255),
    nik VARCHAR(16),
    id_pelanggan_12 VARCHAR(12),
    no_meter VARCHAR(20),
    no_hp VARCHAR(15),
    no_kk VARCHAR(16),
    npwp VARCHAR(16),  -- ← KOLOM INI menyimpan NPWP
    
    -- Alamat
    provinsi VARCHAR(255),
    kab_kota VARCHAR(255),
    kecamatan VARCHAR(255),
    kelurahan VARCHAR(255),
    rt VARCHAR(5),
    rw VARCHAR(5),
    alamat_detail TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Data Affan di Database:**
```sql
INSERT INTO master_pelanggan (
    nama_lengkap, nik, id_pelanggan_12, no_meter, no_hp, no_kk, npwp, ...
) VALUES (
    'AFFAN SHOLEH FIRDAUS', 
    '3319010101010003', 
    '512345678903', 
    '12345678903', 
    '081234567803', 
    '3319010101010003', 
    '3456789012345672',  -- ← NPWP DISIMPAN DI SINI
    ...
);
```

---

## 🔄 CARA UPDATE NPWP di DATABASE

### **Opsi 1: Re-seed Database (Recommended)**

```bash
# Re-seed hanya DummyPelangganSeeder
php artisan db:seed --class=DummyPelangganSeeder
```

**Catatan:**  
Seeder ini menggunakan `truncate()` untuk `master_pelanggan`, jadi data lama akan **DIHAPUS** dan diganti dengan data baru dari seeder.

---

### **Opsi 2: Update Manual via SQL**

```sql
UPDATE master_pelanggan 
SET npwp = '3456789012345672' 
WHERE nik = '3319010101010003';
```

**Atau via Tinker:**
```bash
php artisan tinker
```

```php
DB::table('master_pelanggan')
    ->where('nik', '3319010101010003')
    ->update(['npwp' => '3456789012345672']);
```

---

## 📍 DIAGRAM ALUR DATA NPWP

```
1. SOURCE CODE (Seeder)
   ↓
   database/seeders/DummyPelangganSeeder.php
   Line 91: 'npwp' => '3456789012345672'

2. SEEDING PROCESS
   ↓
   php artisan db:seed --class=DummyPelangganSeeder

3. DATABASE TABLE
   ↓
   master_pelanggan
   Column: npwp
   
4. APLIKASI MEMBACA DATA
   ↓
   Controller: TambahDayaController.php
   Method: verifyNPWP()
   
5. VERIFIKASI
   ↓
   Query: MasterPelanggan::where('nik', $nik)->first()->npwp
   Compare: $inputNPWP === $master->npwp
```

---

## 🧪 TESTING NPWP AFFAN

### **Login:**
```
Email: pelanggan3@kudus.id
Password: Password123!
```

### **Data untuk Test:**
```
NIK: 3319010101010003
NO KK: 3319010101010003 (sama dengan NIK)
NPWP: 3456789012345672 (16 digit)
NO HP: 628123456789 (format: 62 + 10 digit)
```

### **Step 5 - Verifikasi NPWP:**
1. Input NPWP: `3456789012345672`
2. Click "Verifikasi"
3. **Expected:** ✅ "NPWP berhasil diverifikasi"

### **Test NPWP Salah:**
- Input: `3456789012345678` (NPWP lama Affan)
- **Expected:** ❌ "NPWP tidak sesuai dengan data pemohon"

---

## 📊 RINGKASAN DATA SEMUA PELANGGAN

| No | Nama | Email | NIK | NPWP (16 digit) |
|----|------|-------|-----|-----------------|
| 1 | Budi Santoso | pelanggan1@kudus.id | 3319010101010001 | 1234567890123456 |
| 2 | Siti Aminah | pelanggan2@kudus.id | 3319010101010002 | 2345678901234567 |
| 3 | **Affan Sholeh Firdaus** | pelanggan3@kudus.id | 3319010101010003 | **3456789012345672** ← UPDATED! |
| 4 | Rina Wati | pelanggan4@kudus.id | 3319010101010004 | 4567890123456789 |
| 5 | Agus Salim | pelanggan5@kudus.id | 3319010101010005 | 5678901234567890 |

---

## 📁 FILES MODIFIED

1. ✅ `database/seeders/DummyPelangganSeeder.php`
   - Line 91: NPWP Affan updated from `3456789012345678` to `3456789012345672`

---

## ✅ CHECKLIST

- [x] Seeder updated dengan NPWP baru Affan
- [x] Format 16 digit (sesuai aturan baru)
- [x] Digit-only (tanpa titik/dash)
- [ ] **WAJIB:** Run `php artisan db:seed --class=DummyPelangganSeeder` untuk apply ke database

**Status:** ✅ **Seeder Updated, Perlu Re-seed Database!**
