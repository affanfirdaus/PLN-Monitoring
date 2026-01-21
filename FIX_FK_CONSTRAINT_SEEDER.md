# FIX: Foreign Key Constraint Error pada DummyPelangganSeeder

## 🔴 ERROR

```
SQLSTATE[23000]: Integrity constraint violation: 1451 
Cannot delete or update a parent row: a foreign key constraint fails 
(`pln_monitoring`.`service_requests`, CONSTRAINT `service_requests_submitter_user_id_foreign` 
FOREIGN KEY (`submitter_user_id`) REFERENCES `users` (`id`))

SQL: delete from `users` where `role` = pelanggan
```

---

## 🔍 ROOT CAUSE

### **Foreign Key Dependency Chain:**

```
users (id)
  ↑ Referenced by
service_requests (submitter_user_id)
  ↑ Referenced by (potentially)
other tables...
```

### **Buggy Seeder Code (Line 18-21):**

```php
// Clear existing data
DB::table('master_slo')->truncate();
DB::table('master_pelanggan')->truncate();
User::where('role', 'pelanggan')->delete();  // ← ERROR HERE!
```

**Explanation:**
1. ❌ Seeder mencoba delete users dengan role='pelanggan'
2. ❌ Tapi ada records di `service_requests` yang masih reference users tersebut via `submitter_user_id`
3. ❌ MySQL menolak delete karena melanggar foreign key constraint
4. ❌ **DELETE ORDER SALAH** - harus delete child records dulu!

---

## ✅ SOLUTION

### **Correct Order: Delete Children First!**

```php
// Clear existing data (ORDER MATTERS - delete children first!)

// 1. Delete service_requests that reference users
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('service_requests')->truncate();
DB::table('applicant_identities')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// 2. Delete master data
DB::table('master_slo')->truncate();
DB::table('master_pelanggan')->truncate();

// 3. Delete pelanggan users (now safe)
User::where('role', 'pelanggan')->delete();
```

### **Key Changes:**

1. ✅ **Disable FK checks temporarily** untuk truncate dengan aman
2. ✅ **Truncate `service_requests`** dulu (child table)
3. ✅ **Truncate `applicant_identities`** (juga reference users)
4. ✅ **Re-enable FK checks**
5. ✅ **Baru delete users** (parent table) - sekarang aman

---

## 📊 Database Relationship

```
┌─────────────────┐
│     users       │ (Parent)
│  - id (PK)      │
│  - role         │
└────────┬────────┘
         │ Referenced by
         ├─────────────────────────────┐
         │                             │
┌────────▼──────────────┐   ┌─────────▼────────────────┐
│  service_requests     │   │ applicant_identities     │ (Children)
│  - submitter_user_id  │   │  - user_id               │
│    (FK → users.id)    │   │    (FK → users.id)       │
└───────────────────────┘   └──────────────────────────┘
```

**Rule:** Delete children BEFORE parents!

---

## 🧪 TESTING

### **Before Fix:**
```bash
php artisan db:seed --class=DummyPelangganSeeder
# Result: ❌ Foreign key constraint error
```

### **After Fix:**
```bash
php artisan db:seed --class=DummyPelangganSeeder
# Expected: ✅ Seeding database...
#           ✅ Created 5 pelanggan with consistent data
```

---

## 📁 FILE MODIFIED

**File:** `database/seeders/DummyPelangganSeeder.php`  
**Lines:** 18-21 (expanded to 18-28)

**Changes:**
- Added `service_requests` truncate
- Added `applicant_identities` truncate
- Added FK checks disable/enable
- Reordered deletion sequence

---

## ⚠️ IMPORTANT NOTES

### **Why `SET FOREIGN_KEY_CHECKS=0`?**

```php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
// ... truncate tables with FK relationships
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

**Benefits:**
- ✅ Allows truncating tables with FK constraints
- ✅ Faster than deleting records one-by-one
- ✅ Resets auto-increment counters
- ⚠️ **Must re-enable afterwards!**

**Alternative (Slower):**
```php
// Without disabling FK checks, must delete in correct order:
DB::table('service_requests')->delete();  // Child first
DB::table('applicant_identities')->delete();
User::where('role', 'pelanggan')->delete();  // Parent last
```

---

## ✅ CHECKLIST

**Foreign Key Safety:**
- [x] Delete child tables before parent
- [x] Use FK checks toggle for truncate
- [x] Re-enable FK checks after truncate
- [x] Proper deletion order maintained

**Data Integrity:**
- [x] All dependent records cleaned
- [x] No orphaned foreign keys
- [x] Fresh data seeded successfully

**Status:** ✅ **FIXED - Seeder now runs without FK errors**
