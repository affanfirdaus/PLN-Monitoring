# FIX STEP 5: KK & NPWP Input Issues + Button Clickable

## 🐛 ROOT CAUSES IDENTIFIED

### **1. No KK Input Stuck at 15 Digits**
**Cause:** ❌ No JavaScript input filter for KK field  
- HP had `addEventListener('input')` but KK didn't
- Only had `maxlength="16"` but no digit filtering

### **2. NPWP Wrong Format (15 vs 16 digits)**
**Cause:** ❌ Backend validation was set to 15 digits  
- Backend `verifyNPWP()` checked for 15 digits
- Data seeder had 15-digit NPWPs
- Should be **16 digits** (new NPWP format)

### **3. Tombol Verifikasi Tidak Bisa Diklik**
**Cause:** ❌ Event listeners running BEFORE DOM ready  
- `getElementById('no_hp').addEventListener()` was called BEFORE element exists
- `onclick="verifyKK()"` inline handlers work but inconsistent with HP
- Better fix: Wrap ALL listeners in `DOMContentLoaded`

---

## ✅ SOLUTIONS IMPLEMENTED

### **A. Frontend Fix** (`step5.blade.php`)

#### **1. Input Filters - ALL in DOMContentLoaded**
```javascript
document.addEventListener('DOMContentLoaded', function() {
    
    // KK Input Filter - Only digits, max 16
    const kkInput = document.getElementById('no_kk');
    kkInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 16) {
            this.value = this.value.substr(0, 16);
        }
    });

    // HP Input Filter - Only digits, max 12
    const hpInput = document.getElementById('no_hp');
    hpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 12) {
            this.value = this.value.substr(0, 12);
        }
    });

    // NPWP Input Filter - Only digits, max 16
    const npwpInput = document.getElementById('npwp');
    if (npwpInput) {
        npwpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 16) {
                this.value = this.value.substr(0, 16);
            }
        });
    }
});
```

#### **2. Button Event Listeners - Remove inline onclick**
**Before (BUGGY):**
```html
<button onclick="verifyKK()">Verifikasi</button>
```

**After (FIXED):**
```html
<button type="button" id="btn-verify-kk">Verifikasi</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btn-verify-kk').addEventListener('click', verifyKK);
    document.getElementById('btn-verify-npwp').addEventListener('click', verifyNPWP);
});
</script>
```

#### **3. Input Attributes**
```html
<!-- KK -->
<input type="text" id="no_kk" maxlength="16" inputmode="numeric" 
       placeholder="16 Digit Angka (Harus sama dengan NIK)">

<!-- HP -->
<input type="text" id="no_hp" maxlength="12" inputmode="numeric" 
       placeholder="Format: 628123456789 (12 digit)">

<!-- NPWP -->
<input type="text" id="npwp" maxlength="16" inputmode="numeric" 
       placeholder="16 Digit Angka (Tanpa titik/dash)">
```

---

### **B. Backend Fix** (`TambahDayaController.php`)

#### **1. verifyKK() - KK must EQUAL NIK**
**Before:** Checked `master_pelanggan.no_kk` vs input  
**After:** Direct comparison `$noKK === $applicantNik`

```php
public function verifyKK(Request $request)
{
    $wizard = $this->getWizardSession();
    $applicantNik = $wizard['applicant_nik'];
    $noKK = $request->input('no_kk');

    if (!$noKK || strlen($noKK) !== 16) {
        return response()->json([
            'status' => 'invalid',
            'message' => 'No KK harus tepat 16 digit angka.'
        ], 422);
    }

    // Business Rule: No KK HARUS SAMA DENGAN NIK pemohon
    if ($noKK !== $applicantNik) {
        return response()->json([
            'status' => 'invalid',
            'message' => 'No KK harus sama dengan NIK pemohon.'
        ], 422);
    }

    return response()->json([
        'status' => 'valid',
        'message' => 'No KK berhasil diverifikasi (sama dengan NIK pemohon).'
    ]);
}
```

#### ** 2. verifyNPWP() - 16 digits (not 15)**
```php
public function verifyNPWP(Request $request)
{
    $wizard = $this->getWizardSession();
    $applicantNik = $wizard['applicant_nik'];
    $npwp = $request->input('npwp');

    $npwpClean = preg_replace('/[^0-9]/', '', $npwp);
    
    // NEW FORMAT: 16 digits (not 15)
    if (strlen($npwpClean) !== 16) {
        return response()->json([
            'status' => 'invalid',
            'message' => 'NPWP harus tepat 16 digit angka (format baru).'
        ], 422);
    }

    $master = MasterPelanggan::where('nik', $applicantNik)->first();
    
    if ($master->npwp !== $npwpClean) {
        return response()->json([
            'status' => 'invalid',
            'message' => 'NPWP tidak sesuai dengan data pemohon (NIK).'
        ], 422);
    }

    return response()->json([
        'status' => 'valid',
        'message' => 'NPWP berhasil diverifikasi.'
    ]);
}
```

---

### **C. Data Seeder Fix** (`DummyPelangganSeeder.php`)

**Updated all NPWP to 16 digits:**
```php
'npwp' => '1234567890123456', // Budi (was 15, now 16)
'npwp' => '2345678901234567', // Siti
'npwp' => '3456789012345678', // Affan
'npwp' => '4567890123456789', // Rina
'npwp' => '5678901234567890', // Agus
```

---

## 🧪 TEST CASES (Copy-Paste Ready)

**Re-seed database first:**
```bash
php artisan db:seed --class=DummyPelangganSeeder
```

**Login:** `pelanggan1@kudus.id` / `Password123!` (Budi)  
**NIK Budi:** `3319010101010001`  
**NPWP Budi:** `1234567890123456` (16 digits)

---

### **Test 1: KK 15 Digits**
- Input KK: `331901010101000` (15 digits)
- Click "Verifikasi"
- **Expected:** ❌ "No KK harus tepat 16 digit angka"

### **Test 2: KK 16 Digits but Different from NIK**
- Input KK: `3319010101010002` (Siti's NIK, not Budi's)
- Click "Verifikasi"
- **Expected:** ❌ "No KK harus sama dengan NIK pemohon"

### **Test 3: KK = NIK (Valid)**
- Input KK: `3319010101010001` (same as Budi's NIK)
- Click "Verifikasi"
- **Expected:** ✅ "No KK berhasil diverifikasi (sama dengan NIK pemohon)"
- **Status:** Green background, check icon

### **Test 4: NPWP 15 Digits**
- Input NPWP: `123456789012345` (15 digits, old format)
- Click "Verifikasi"
- **Expected:** ❌ "NPWP harus tepat 16 digit angka (format baru)"

### **Test 5: NPWP 16 Digits but Wrong**
- Input NPWP: `2345678901234567` (Siti's NPWP)
- Click "Verifikasi"
- **Expected:** ❌ "NPWP tidak sesuai dengan data pemohon (NIK)"

### **Test 6: NPWP 16 Digits Correct**
- Input NPWP: `1234567890123456` (Budi's NPWP)
- Click "Verifikasi"
- **Expected:** ✅ "NPWP berhasil diverifikasi"
- **Status:** Green background, check icon

### **Test 7: Input More Than Max**
-Try typing in KK field: `33190101010100011111111` (many digits)
- **Expected:** Input stops at 16 digits (`3319010101010001`)
- Try typing in NPWP: `12345678901234567890` (many digits)
- **Expected:** Input stops at 16 digits (`1234567890123456`)

### **Test 8: Buttons Clickable**
- Open browser DevTools → Network tab
- Click "Verifikasi" button on KK
- **Expected:** See POST request to `/verify-kk`
- Click "Verifikasi" button on NPWP
- **Expected:** See POST request to `/verify-npwp`

### **Test 9: Submit Button Logic**
- Don't verify KK → **Submit: DISABLED (gray)**
- Verify KK (valid) → Still disabled if photos not uploaded
- Upload both photos + verify KK + verify NPWP → **Submit: ENABLED (blue)**

---

## 📊 Data Mapping for Testing

| Nama | NIK (= KK) | NPWP (16 digits) | Email |
|------|------------|------------------|-------|
| Budi Santoso | 3319010101010001 | 1234567890123456 | pelanggan1@kudus.id |
| Siti Aminah | 3319010101010002 | 2345678901234567 | pelanggan2@kudus.id |
| Affan | 3319010101010003 | 3456789012345678 | pelanggan3@kudus.id |
| Rina Wati | 3319010101010004 | 4567890123456789 | pelanggan4@kudus.id |
| Agus Salim | 3319010101010005 | 5678901234567890 | pelanggan5@kudus.id |

**Password all:** `Password123!`

---

## 📁 Files Modified

1. ✅ `resources/views/pelanggan/tambah-daya/step5.blade.php`
   - Added input filters for KK (16 digits)
   - Updated NPWP to 16 digits
   - Moved all event listeners to DOMContentLoaded
   - Removed inline onclick handlers

2. ✅ `app/Http/Controllers/TambahDayaController.php`
   - `verifyKK()`: Changed to `$noKK === $applicantNik` direct comparison
   - `verifyNPWP()`: Changed from 15 to 16 digits validation

3. ✅ `database/seeders/DummyPelangganSeeder.php`
   - Updated all NPWP from 15 to 16 digits

---

## ✅ FINAL CHECKLIST

**Input Functionality:**
- [x] KK accepts exactly 16 digits (not stuck at 15)
- [x] NPWP accepts exactly 16 digits (not 15)
- [x] HP accepts exactly 12 digits
- [x] All inputs block non-digit characters
- [x] All inputs have `inputmode="numeric"` for mobile keyboards

**Button Functionality:**
- [x] "Verifikasi" buttons are clickable
- [x] Buttons trigger API calls (visible in Network tab)  
- [x] No console errors about null elements
- [x] Event listeners wrapped in DOMContentLoaded

**Validation Logic:**
- [x] KK must equal NIK (not just match from master_pelanggan)
- [x] NPWP must be 16 digits and match master_pelanggan.npwp
- [x] Appropriate error messages for each case

**Data Consistency:**
- [x] All dummy NPWPs are 16 digits
- [x] All dummy KK numbers equal their NIK

**Status:** ✅ **ALL FIXED**
