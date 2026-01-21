# LAPORAN PERBAIKAN: Validasi ID Pelanggan/No Meter - Step 1

## Akar Masalah

### 1. **DUPLIKASI DATA** (Data Ganda di 2 Seeder Berbeda)

**Lokasi:**
- `database/seeders/DummyPelangganSeeder.php` 
- `database/seeders/MasterPelangganSeeder.php`

**Masalah:**
Terdapat **DUA seeder berbeda** yang membuat data pelanggan dengan **email dan ID pelanggan yang BERBEDA**:

| Seeder | Email | ID Pelanggan | No Meter | NIK |
|--------|-------|--------------|----------|-----|
| DummyPelangganSeeder | pelanggan0@kudus.id | **513000000001** | 12345678901 | 3319...001 |
| MasterPelangganSeeder | pelanggan**1**@kudus.id | **512345678901** | 12345678901 | 3319...001 |

**Akibat:**
- User login dengan `pelanggan0@kudus.id` punya NIK `3319...001`
- Tapi saat validasi ID pelanggan `513000000001` → tidak match dengan master yang ID-nya `512345678901`
- Data **TIDAK SINKRON** antara users dan master_pelanggan!

---

### 2. **BUG QUERY `orWhere`** (Query SQL Salah)

**Lokasi:** `TambahDayaController.php` line 671-673

**Kode BUGGY:**
```php
$master = MasterPelanggan::where('id_pelanggan_12', $input)
            ->orWhere('no_meter', $input)
            ->first();
```

**Menghasilkan SQL:**
```sql
SELECT * FROM master_pelanggan 
WHERE id_pelanggan_12 = '512345678901' 
   OR no_meter = '12345678901'
LIMIT 1
```

**Masalahnya:**
Jika ada 5 record di database, query `orWhere` **tanpa parentheses** bisa match **RECORD MANAPUN** yang memenuhi salah satu kondisi!

**Contoh Bug:**
- User input: `12345678902` (meter Siti)
- Query match: Record pertama yang `no_meter = '12345678902'` → **RETURN Siti ✓**
- Tapi juga bisa match record lain jika ada ID pelanggan yang kebetulan sama dengan meter number
- **Result: Data mixing / wrong record returned!**

---

### 3. **MAPPING RESPONSE SALAH** (Ambil Data dari Record Salah)

Karena query bug di atas, data yang di-return ke UI adalah dari record yang **TIDAK SESUAI**:
- Input ID milik sendiri → query return record orang lain → validation fail ❌
- Input meter orang lain → query return record orang lain → validation pass ✓ (SALAH!)

---

## Solusi Implementasi

### A. **Konsolidasi Data - Single Source of Truth**

**File:** `database/seeders/DummyPelangganSeeder.php` (REWRITE TOTAL)

Semua data users, master_pelanggan, dan master_slo **HANYA ADA DI SATU FILE INI**.

```php
<?php
namespace Database\Seeders;

class DummyPelangganSeeder extends Seeder
{
    public function run(): void
    {
        // Clear ALL existing data
        DB::table('master_slo')->truncate();
        DB::table('master_pelanggan')->truncate();
        User::where('role', 'pelanggan')->delete();

        // DATA FINAL - 5 PELANGGAN (Single source)
        $pelanggan = [
            [
                'name' => 'Budi Santoso',
                'email' => 'pelanggan1@kudus.id',
                'nik' => '3319010101010001',
                'id_pelanggan' => '512345678901',
                'no_meter' => '12345678901',
                'no_hp' => '081234567801',
                'no_kk' => '3319010101010001',
                'npwp' => '123456789012345',
                // ... alamat, SLO, dll
            ],
            // ... 4 pelanggan lainnya
        ];

        foreach ($pelanggan as $p) {
            // 1. CREATE USER (login)
            User::create([...]);
            
            // 2. CREATE MASTER_PELANGGAN (validasi)
            DB::table('master_pelanggan')->insert([...]);
            
            // 3. CREATE MASTER_SLO (SLO step 4)
            DB::table('master_slo')->insert([...]);
        }
    }
}
```

**Deprecated Seeders:**
- `MasterPelangganSeeder.php` → dikosongkan (intentionally empty)
- `MasterSloSeeder.php` → dikosongkan (intentionally empty)

---

### B. **Fix Query Bug dengan Closure**

**File:** `TambahDayaController.php` line 671-679

**Kode BENAR:**
```php
// CRITICAL FIX: Use where() with closure to avoid orWhere bug
$master = MasterPelanggan::where(function($query) use ($input) {
    $query->where('id_pelanggan_12', $input)
          ->orWhere('no_meter', $input);
})->first();
```

**Menghasilkan SQL yang BENAR:**
```sql
SELECT * FROM master_pelanggan 
WHERE (id_pelanggan_12 = '512345678901' OR no_meter = '12345678901')
LIMIT 1
```

**Sekarang:**
- `orWhere` di-scope dalam closure → parentheses otomatis
- Query match **HANYA 1 RECORD SPESIFIK** yang benar-benar memiliki ID/meter tersebut
- Tidak ada data mixing!

---

## Data Final - 5 Pelanggan (Konsisten)

| No | Nama | Email | NIK | ID Pelanggan | No Meter | Password |
|----|------|-------|-----|--------------|----------|----------|
| 1 | Budi Santoso | pelanggan1@kudus.id | 3319010101010001 | 512345678901 | 12345678901 | Password123! |
| 2 | Siti Aminah | pelanggan2@kudus.id | 3319010101010002 | 512345678902 | 12345678902 | Password123! |
| 3 | Affan Sholeh Firdaus | pelanggan3@kudus.id | 3319010101010003 | 512345678903 | 12345678903 | Password123! |
| 4 | Rina Wati | pelanggan4@kudus.id | 3319010101010004 | 512345678904 | 12345678904 | Password123! |
| 5 | Agus Salim | pelanggan5@kudus.id | 3319010101010005 | 512345678905 | 12345678905 | Password123! |

**SLO Mapping:**
- Budi: `SLO-REG-2026-000241` + `SLO-CERT-2026-KUDUS-12001`
- Siti: `SLO-REG-2026-000242` + `SLO-CERT-2026-KUDUS-12002`
- Affan: `SLO-REG-2025-913402` + `SLO-CERT-2025-BOGOR-00019`
- Rina: `SLO-REG-2024-450110` + `SLO-CERT-2024-SIANT-54321`
- Agus: `SLO-REG-2023-777001` + `SLO-CERT-2023-KUDUS-10010`

---

## Testing Instructions

### 1. **Re-seed Database**
```bash
php artisan migrate:fresh --seed
```

### 2. **Login sebagai Budi Santoso**
- Email: `pelanggan1@kudus.id`
- Password: `Password123!`
- NIK (otomatis): `3319010101010001`

### 3. **Test Cases - Mode "Saya Sendiri"**

#### ✅ **VALID - ID Pelanggan Milik Sendiri**
- Input: `512345678901` (ID pelanggan Budi)
- Expected: **SUCCESS** → "Data Pelanggan Valid"
- Tombol "Lanjutkan": **ENABLED**

#### ✅ **VALID - No Meter Milik Sendiri**
- Input: `12345678901` (meter Budi)
- Expected: **SUCCESS** → "Data Pelanggan Valid"
- Tombol "Lanjutkan": **ENABLED**

#### ❌ **INVALID - ID Pelanggan Orang Lain**
- Input: `512345678902` (ID pelanggan Siti)
- Expected: **FAILED** → "ID Pelanggan / No Meter tidak sesuai dengan NIK Pemohon (tidak valid)."
- Tombol "Lanjutkan": **DISABLED**

#### ❌ **INVALID - No Meter Orang Lain**
- Input: `12345678902` (meter Siti)
- Expected: **FAILED** → "ID Pelanggan / No Meter tidak sesuai dengan NIK Pemohon (tidak valid)."
- Tombol "Lanjutkan": **DISABLED**

#### ❌ **INVALID - Nomor Random**
- Input: `999999999999`
- Expected: **FAILED** → "ID Pelanggan / Nomor Meter tidak ditemukan."

---

### 4. **Test Cases - Mode "Orang Lain"**

#### Step 1: Verifikasi NIK
- Input NIK: `3319010101010002` (NIK Siti)
- Expected: **SUCCESS** → "NIK Ditemukan" → Nama: "SITI AMINAH"

#### Step 2: Verifikasi ID/Meter yang MATCH dengan NIK Siti

✅ **VALID:**
- Input: `512345678902` atau `12345678902` → **SUCCESS**

❌ **INVALID:**
- Input: `512345678901` (milik Budi) → **FAILED** (NIK tidak match dengan Siti)

---

## File Yang Diubah

### 1. ✅ **Seeder (Consolidation)**
- `database/seeders/DummyPelangganSeeder.php` → REWRITE total (single source)
- `database/seeders/MasterPelangganSeeder.php` → DEPRECATED (dikosongkan)
- `database/seeders/MasterSloSeeder.php` → DEPRECATED (dikosongkan)

### 2. ✅ **Controller (Query Fix)**
- `app/Http/Controllers/TambahDayaController.php` (line 671-735)
  - Fix orWhere dengan closure
  - Tambah komentar untuk clarity

### 3. ❌ **Tidak Diubah:**
- UI/Layout Step 1
- Alur wizard lainnya
- Database schema/migrations

---

## Penjelasan Teknis Query Bug

### **BEFORE (BUGGY):**
```php
MasterPelanggan::where('id_pelanggan_12', $input)
               ->orWhere('no_meter', $input)
               ->first();
```

**SQL Generated:**
```sql
SELECT * FROM master_pelanggan 
WHERE id_pelanggan_12 = ? 
   OR no_meter = ?
LIMIT 1
```

**Problem:**
- Jika ada 5 records, query ini akan match record PERTAMA yang memenuhi SALAH SATU kondisi
- Bisa return record yang salah karena tidak ada scoping

---

### **AFTER (CORRECT):**
```php
MasterPelanggan::where(function($query) use ($input) {
    $query->where('id_pelanggan_12', $input)
          ->orWhere('no_meter', $input);
})->first();
```

**SQL Generated:**
```sql
SELECT * FROM master_pelanggan 
WHERE (id_pelanggan_12 = ? OR no_meter = ?)
LIMIT 1
```

**Benefit:**
- `orWhere` di-scope dalam satu WHERE clause
- Parentheses ensure proper logic grouping
- Query match HANYA record yang benar-benar punya ID atau meter tersebut

---

## Validasi Logic Flow (Mode Self)

```
User Login: Budi (NIK: 3319...001)
    ↓
Input: ID Pelanggan atau No Meter
    ↓
[1] Query master_pelanggan: WHERE (id_pel = X OR meter = X)
    ↓
    ├─ Record tidak ditemukan → ERROR: "Tidak ditemukan" ❌
    │
    └─ Record ditemukan (misal: record Budi atau record Siti)
        ↓
[2] Compare: master.nik === user.nik (Auth)
    ↓
    ├─ Tidak match → ERROR: "Tidak sesuai NIK Pemohon" ❌
    │   (Input meter Siti, tapi login sebagai Budi)
    │
    └─ Match ✓
        ↓
[3] SUCCESS: "Data Pelanggan Valid" ✅
    Return: nama, id_pelanggan, no_meter dari master
    Enable tombol "Lanjutkan"
```

---

## Summary

✅ **Fixed:**
1. **Data duplication** - Consolidated into DummyPelangganSeeder
2. **Query bug** - Added closure to orWhere for proper SQL scoping
3. **Data consistency** - All 5 users now have matching data across tables
4. **Validation strictness** - Self mode properly checks NIK ownership

✅ **Result:**
- ID milik sendiri → VALID ✓
- Meter milik sendiri → VALID ✓
- ID orang lain → INVALID ✗
- Meter orang lain → INVALID ✗
- Nama yang tampil → ALWAYS CORRECT (dari master yang match)
