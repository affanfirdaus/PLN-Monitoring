# FIX: Migration Conflict - Duplicate SLO Columns

## 🐛 Error Message

```
SQLSTATE[HY000]: General error: 1364 Field 'pemilik_nik' doesn't have a default value

SQL: insert into `master_slo` 
(`no_registrasi_slo`, `no_sertifikat_slo`, `nama_lembaga`, 
 `nik_pemilik`, `nama_pemilik`, `created_at`, `updated_at`)
```

## 🔍 Root Cause

**DUPLIKASI MIGRATION** - Ada 2 migration yang conflict!

### Migration 1 (BENAR):
**File:** `database/migrations/2025_01_20_000001_create_master_slo_and_update_requests.php`
```php
Schema::create('master_slo', function (Blueprint $table) {
    $table->id();
    $table->string('no_registrasi_slo')->unique()->index();
    $table->string('no_sertifikat_slo')->unique()->index();
    $table->string('nama_lembaga')->nullable();
    
    // ✓ COLUMN NAMES (NEW - CORRECT)
    $table->string('nik_pemilik', 16)->index();
    $table->string('nama_pemilik')->nullable();
    
    $table->timestamps();
});
```

### Migration 2 (CONFLICT - DUPLICATE):
**File:** `database/migrations/2025_01_21_000002_alter_master_slo_add_owner_columns.php`
```php
Schema::table('master_slo', function (Blueprint $table) {
    // ✗ COLUMN NAMES (OLD - DUPLICATE)
    $table->string('pemilik_nik', 16)->index()->after('nama_lembaga');
    $table->string('pemilik_nama')->nullable()->index()->after('pemilik_nik');
});
```

### Result:
**Table `master_slo` memiliki KEDUA kolom:**
- `nik_pemilik` (from migration 1) ✓
- `nama_pemilik` (from migration 1) ✓
- `pemilik_nik` (from migration 2) ✗ DUPLICATE!
- `pemilik_nama` (from migration 2) ✗ DUPLICATE!

**Seeder hanya mengisi:**
- `nik_pemilik` ✓
- `nama_pemilik` ✓

**Error:**
- Column `pemilik_nik` required tapi tidak di-fill → **Error!**

---

## ✅ Solusi

### Step 1: Hapus Migration Duplicate

```bash
# Delete the conflicting migration
del database\migrations\2025_01_21_000002_alter_master_slo_add_owner_columns.php
```

**Reasoning:**
- Migration 1 sudah LENGKAP (create table + semua columns)
- Migration 2 TIDAK PERLU (hanya menambah kolom duplikat dengan nama berbeda)

### Step 2: Fresh Migration + Seed

```bash
php artisan migrate:fresh --seed
```

**Expected Output:**
```
Dropped all tables successfully.
Migration table created successfully.
Migrating: 2025_01_20_000001_create_master_slo_and_update_requests
Migrated:  2025_01_20_000001_create_master_slo_and_update_requests
...
Seeding: Database\Seeders\DummyPelangganSeeder
✓ Created 5 pelanggan with consistent data across users, master_pelanggan, and master_slo
Database seeding completed successfully.
```

### Step 3: Verify Table Schema

```sql
DESCRIBE master_slo;
```

**Expected Columns (NO DUPLICATES):**
```
+---------------------+--------------+------+-----+---------+
| Field               | Type         | Null | Key | Default |
+---------------------+--------------+------+-----+---------+
| id                  | bigint       | NO   | PRI | NULL    |
| no_registrasi_slo   | varchar(255) | NO   | UNI | NULL    |
| no_sertifikat_slo   | varchar(255) | NO   | UNI | NULL    |
| nama_lembaga        | varchar(255) | YES  |     | NULL    |
| nik_pemilik         | varchar(16)  | NO   | MUL | NULL    |  ✓ NEW NAME
| nama_pemilik        | varchar(255) | YES  |     | NULL    |  ✓ NEW NAME
| tanggal_terbit      | date         | YES  |     | NULL    |
| tanggal_berlaku_..  | date         | YES  |     | NULL    |
| created_at          | timestamp    | YES  |     | NULL    |
| updated_at          | timestamp    | YES  |     | NULL    |
+---------------------+--------------+------+-----+---------+
```

**TIDAK ADA:**
- ❌ `pemilik_nik` (old name - should not exist)
- ❌ `pemilik_nama` (old name - should not exist)

---

## 📋 Files Changed

### ✅ Deleted:
- `database/migrations/2025_01_21_000002_alter_master_slo_add_owner_columns.php`

### ✅ Kept (No Changes):
- `database/migrations/2025_01_20_000001_create_master_slo_and_update_requests.php`
- `database/seeders/DummyPelangganSeeder.php`

---

## 🎯 Column Naming Convention

**FINAL DECISION: `nik_pemilik` + `nama_pemilik`**

**Reasoning:**
1. Konsisten dengan pattern: `{field}_{owner}` → `nik_pemilik`, `nama_pemilik`
2. Sesuai dengan controller logic yang sudah dibuat (Step 88)
3. Lebih readable: "nik pemilik" vs "pemilik nik"

**All References Updated:**
- ✅ Migration: Uses `nik_pemilik`, `nama_pemilik`
- ✅ Seeder: Uses `nik_pemilik`, `nama_pemilik`
- ✅ Controller (`TambahDayaController@checkSlo`): Uses `nik_pemilik`, `nama_pemilik`
- ✅ Controller (`TambahDayaController@storeStep4`): Uses `nik_pemilik`

---

## 🧪 Testing After Fix

```bash
# 1. Verify seeding success
php artisan tinker --execute="DB::table('master_slo')->count()"
# Expected: 5

# 2. Check structure
php artisan tinker --execute="DB::table('master_slo')->first()"
# Expected: Should have nik_pemilik, nama_pemilik (NOT pemilik_nik)

# 3. Check data sample
php artisan tinker --execute="DB::table('master_slo')->where('nik_pemilik', '3319010101010001')->get(['no_registrasi_slo', 'nama_pemilik'])"
# Expected: SLO-REG-2026-000241, BUDI SANTOSO
```

---

## Summary

**Problem:** Migration conflict causing duplicate columns with different names  
**Root Cause:** Old migration file creating `pemilik_nik` when main migration already has `nik_pemilik`  
**Solution:** Delete duplicate migration, run fresh migration  
**Result:** Clean table schema with correct column names  

✅ **Fixed!**
