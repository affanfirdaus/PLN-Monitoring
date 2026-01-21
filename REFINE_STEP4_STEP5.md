# REFINE STEP 4 & 5: Privacy + Strict Validations

## 📋 Summary of Changes

### **STEP 4 - Privacy Fix**
✅ **Remove sensitive data from SLO mismatch errors**
- No more showing NIK or name of actual SLO owner
- Generic message: "Data SLO terdaftar atas nama dan NIK orang lain."

### **STEP 5 - Add Strict Validations**
✅ **No KK - Verification Button**
✅ **No HP - Strict Format (62 + 10 digits = 12 total)**
✅ **NPWP - Verification Button (if required)**
✅ **Photos - Both Required (Bangunan + KTP Selfie)**

---

## 📁 Files Modified

### 1. **Backend Controller**
**File:** `app/Http/Controllers/TambahDayaController.php`

**Changes:**
- **checkSlo()** - Removed `data` field from nik_mismatch and name_mismatch responses
- **verifyKK()** - NEW method to verify No KK matches NIK
- **verifyNPWP()** - NEW method to verify NPWP matches NIK  
- **storeStep5()** - Updated validation rules:
  - `no_hp`: Must start with "62" + 10 digits (total 12)
  - `no_kk`: Required, exactly 16 digits
  - `foto_bangunan`: Required
  - `foto_ktp_selfie`: Required

---

### 2. **Frontend - Step 4**
**File:** `resources/views/pelanggan/tambah-daya/step4.blade.php`

**Changes:**
- Simplified error handling for `nik_mismatch` and `name_mismatch`
- No longer displays `data.data.slo_nik` or `data.data.slo_name`
- Shows generic error message only

---

### 3. **Frontend - Step 5**
**File:** `resources/views/pelanggan/tambah-daya/step5.blade.php`

**Complete Rewrite with:**
1. **No KK Field** + Verification Button
   - 16 digits required
   - Must match NIK from master_pelanggan
2. **No HP Field** + Strict Input Validation  
   - Only digits allowed
   - Format: 62xxxxxxxxxx (12 digits total)
   - Frontend blocks non-digit input
3. **NPWP Field** + Verification Button (if `wajibNPWP = true`)
   - 15 digits required
   - Must match NIK from master_pelanggan
4. **Photo Uploads** (Both Required)
   - Foto Bangunan (Tampak Depan)
   - Foto Diri dengan KTP
   - Submit button disabled until both uploaded

---

### 4. **Routes**
**File:** `routes/web.php`

**Added:**
```php
Route::post('/pelanggan/tambah-daya/verify-kk', [TambahDayaController::class, 'verifyKK'])->name('tambah-daya.verify-kk');
Route::post('/pelanggan/tambah-daya/verify-npwp', [TambahDayaController::class, 'verifyNPWP'])->name('tambah-daya.verify-npwp');
```

---

## 🔧 Backend Logic

### **A. verifyKK() Method**

```php
public function verifyKK(Request $request)
{
    $wizard = $this->getWizardSession();
    $applicantNik = $wizard['applicant_nik'];
    $noKK = $request->input('no_kk');

    // Validate format
    if (strlen($noKK) !== 16) {
        return response()->json(['status' => 'invalid', 'message' => 'No KK harus 16 digit angka.'], 422);
    }

    // Check against master_pelanggan
    $master = MasterPelanggan::where('nik', $applicantNik)->first();
    
    if ($master->no_kk !== $noKK) {
        return response()->json(['status' => 'invalid', 'message' => 'No KK tidak sesuai dengan NIK pemohon.'], 422);
    }

    return response()->json(['status' => 'valid', 'message' => 'No KK berhasil diverifikasi.']);
}
```

###**B. verifyNPWP() Method**

```php
public function verifyNPWP(Request $request)
{
    $wizard = $this->getWizardSession();
    $applicantNik = $wizard['applicant_nik'];
    $npwp = $request->input('npwp');

    // Clean formatting
    $npwpClean = preg_replace('/[^0-9]/', '', $npwp);
    
    // Validate 15 digits
    if (strlen($npwpClean) !== 15) {
        return response()->json(['status' => 'invalid', 'message' => 'NPWP harus 15 digit angka.'], 422);
    }

    // Check against master_pelanggan
    $master = MasterPelanggan::where('nik', $applicantNik)->first();
    
    if ($master->npwp !== $npwpClean) {
        return response()->json(['status' => 'invalid', 'message' => 'NPWP tidak sesuai dengan data pemohon (NIK).'], 422);
    }

    return response()->json(['status' => 'valid', 'message' => 'NPWP berhasil diverifikasi.']);
}
```

### **C. storeStep5() Validation**

```php
$rules = [
    'foto_bangunan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    'foto_ktp_selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    'no_kk' => ['required', 'digits:16'],
    'no_hp' => ['required', 'regex:/^62[0-9]{10}$/'], // Must start with 62, total 12 digits
];

$messages = [
    'no_hp.regex' => 'Nomor HP harus 12 digit dan diawali 62 (contoh: 628123456789).',
    'no_kk.digits' => 'No KK harus tepat 16 digit angka.',
    'foto_bangunan.required' => 'Foto bangunan wajib diunggah.',
    'foto_ktp_selfie.required' => 'Foto diri dengan KTP wajib diunggah.',
];
```

---

## 🧪 Test Cases

### **STEP 4 - Privacy Test**

**Test:** SLO mismatch (NIK or Name)
- Input: `SLO-REG-2026-000242` + `SLO-CERT-2026-KUDUS-12002` (Siti's SLO)  
- Login as: Budi (`pelanggan1@kudus.id`)
- **Expected:** ✅ Error: "Data SLO terdaftar atas nama dan NIK orang lain."
- **NOT showing:** ❌ NIK owner, Name owner

---

### **STEP 5 - Validation Tests**

#### **1. No KK Verification**

**Test Case 1: KK Mismatch**
- Input KK: `3319010101010002` (Siti's KK)
- Login as: Budi (NIK: `3319010101010001`, KK: `3319010101010001`)
- Click "Verifikasi"
- **Expected:** ❌ "No KK tidak sesuai dengan NIK pemohon."

**Test Case 2: KK Match**
- Input KK: `3319010101010001` (Budi's KK)
- Login as: Budi
- Click "Verifikasi"
- **Expected:** ✅ "No KK berhasil diverifikasi."

---

#### **2. No HP Format**

**Test Case 1: Wrong Format (starts with 08)**
- Input: `08123456789`
- **Expected:** ❌ Frontend blocks/validation error: "Nomor HP harus 12 digit dan diawali 62"

**Test Case 2: Correct Format**
- Input: `628123456789` (12 digits, starts with 62)
- **Expected:** ✅ Accepted

**Test Case 3: Too Long**
- Input: `6281234567890` (13 digits)
- **Expected:** ❌ Frontend limits to 13 chars max, backend rejects

---

#### **3. NPWP Verification** (if waj ibNPWP = true)

**Test Case 1: NPWP Mismatch**
- Input NPWP: `234567890123456` (Siti's NPWP)
- Login as: Budi (NPWP: `123456789012345`)
- Click "Verifikasi"
- **Expected:** ❌ "NPWP tidak sesuai dengan data pemohon (NIK)."

**Test Case 2: NPWP Match**
- Input NPWP: `123456789012345` or `12.345.678.9-012.345` (with formatting)
- Login as: Budi
- Click "Verifikasi"
- **Expected:** ✅ "NPWP berhasil diverifikasi."

---

#### **4. Photo Upload Requirements**

**Test Case 1: No Photos Uploaded**
- Leave both fields empty
- **Expected:** ❌ Submit button DISABLED (gray, cursor-not-allowed)

**Test Case 2: Only Bangunan Uploaded**
- Upload foto_bangunan only
- **Expected:** ❌ Submit button still DISABLED

**Test Case 3: Both Uploaded**
- Upload both foto_bangunan + foto_ktp_selfie
- All verifications passed (KK ✓, NPWP ✓)
- **Expected:** ✅ Submit button ENABLED (blue, clickable)

---

## 🎯 UI/UX Behavior

### **Submit Button Logic**

```javascript
const allValid = kkVerified && npwpVerified && photosBangunanUploaded && photosKTPUploaded;

if (allValid) {
    btn.enabled = true;
    btn.className = 'bg-blue-600 hover:bg-blue-700'; // Blue, clickable
} else {
    btn.disabled = true;
    btn.className = 'bg-slate-300 cursor-not-allowed'; // Gray, disabled
}
```

**Requirements for ENABLED:**
1. ✅ No KK verified
2. ✅ NPWP verified (if wajibNPWP = true, else auto-true)
3. ✅ Foto Bangunan uploaded
4. ✅ Foto KTP Selfie uploaded

---

## 📊 Data Dummy for Testing

**Login as Budi** (`pelanggan1@kudus.id` / `Password123!`):
- NIK: `3319010101010001`
- No KK: `3319010101010001` (same as NIK)
- No HP: `081234567801` → convert to `6281234567801`
- NPWP: `123456789012345`
- SLO: `SLO-REG-2026-000241` + `SLO-CERT-2026-KUDUS-12001`

**For Mismatch Tests** (Siti's Data):
- NIK: `3319010101010002`
- No KK: `3319010101010002`
- NPWP: `234567890123456`
- SLO: `SLO-REG-2026-000242` + `SLO-CERT-2026-KUDUS-12002`

---

## ✅ Final Checklist

**STEP 4:**
- [x] SLO mismatch error → No NIK/name shown
- [x] SLO mismatch → Generic message only
- [x] SLO valid → Full details shown (name + NIK + lembaga)

**STEP 5:**
- [x] No KK verification button
- [x] No KK must match NIK
- [x] No HP strict format: 62 + 10 digits
- [x] NPWP verification button
- [x] NPWP must match NIK
- [x] Foto Bangunan required
- [x] Foto KTP Selfie required
- [x] Submit disabled until all validated
- [x] Form prevents double submission

**Routes:**
- [x] `/verify-kk` endpoint added
- [x] `/verify-npwp` endpoint added

**Status:** ✅ **ALL IMPLEMENTED**
