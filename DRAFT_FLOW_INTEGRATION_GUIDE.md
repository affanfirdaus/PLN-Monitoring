# TambahDayaController - Draft Flow Integration Guide

## Quick Reference: What Needs to be Added

### Location: `app/Http/Controllers/TambahDayaController.php`

---

## 1. In `storeStep1()` method (around line 140-160)

**BEFORE** (current - needs update):
```php
// Current logic creates/updates service request
// But doesn't use session-based draft ID
```

**AFTER** (add this logic):
```php
use App\Enums\PermohonanStatus;

// After validation passes and applicant identity is created/updated

$draftId = session('td_draft_id');

if ($draftId && ServiceRequest::find($draftId)) {
    // Update existing draft
    $sr = ServiceRequest::find($draftId);
    $sr->update([
        'jenis_layanan' => 'TAMBAH_DAYA',
        'last_step' => 1,
        'applicant_id' => $applicantIdentity->id,
        'applicant_nik' => $applicantNik,
        'daya_baru' => $request->daya_baru,
        'jenis_produk' => $request->jenis_produk,
        'peruntukan_koneksi' => $request->peruntukan_koneksi,
        'last_saved_at' => now(),
    ]);
} else {
    // Create new draft
    $sr = ServiceRequest::create([
        'submitter_user_id' => Auth::id(),
        'jenis_layanan' => 'TAMBAH_DAYA',
        'status' => PermohonanStatus::DRAFT,
        'is_draft' => true,
        'last_step' => 1,
        'applicant_id' => $applicantIdentity->id,
        'applicant_nik' => $applicantNik,
        'daya_baru' => $request->daya_baru,
        'jenis_produk' => $request->jenis_produk,
        'peruntukan_koneksi' => $request->peruntukan_koneksi,
        'last_saved_at' => now(),
    ]);
    
    // Store draft ID in session
    session(['td_draft_id' => $sr->id]);
}

// Continue to step 2
return redirect()->route('tambah-daya.step2');
```

---

## 2. In `storeStep2()` method

**Add at the beginning**:
```php
$draftId = session('td_draft_id');
if (!$draftId) {
    return redirect()->route('tambah-daya.step1')
        ->withErrors(['global' => 'Session expired. Please start again.']);
}

$sr = ServiceRequest::findOrFail($draftId);
```

**Add before redirect to step3**:
```php
$sr->update([
    'last_step' => 2,
    'lokasi_provinsi' => $request->provinsi,
    'lokasi_kab_kota' => $request->kab_kota,
    'lokasi_kecamatan' => $request->kecamatan,
    'lokasi_kelurahan' => $request->kelurahan,
    'lokasi_rt' => $request->rt,
    'lokasi_rw' => $request->rw,
    'lokasi_detail_tambahan' => $request->alamat_detail,
    'koordinat_lat' => $request->latitude,
    'koordinat_lng' => $request->longitude,
    'last_saved_at' => now(),
]);
```

---

## 3. In `storeStep3()` method

**Add at the beginning**:
```php
$draftId = session('td_draft_id');
if (!$draftId) {
    return redirect()->route('tambah-daya.step1')
        ->withErrors(['global' => 'Session expired. Please start again.']);
}

$sr = ServiceRequest::findOrFail($draftId);
```

**Add before redirect to step4**:
```php
$sr->update([
    'last_step' => 3,
    // ... other step 3 data
    'last_saved_at' => now(),
]);
```

---

## 4. In `storeStep4()` method

**Add at the beginning**:
```php
$draftId = session('td_draft_id');
if (!$draftId) {
    return redirect()->route('tambah-daya.step1')
        ->withErrors(['global' => 'Session expired. Please start again.']);
}

$sr = ServiceRequest::findOrFail($draftId);
```

**Add before redirect to step5**:
```php
$sr->update([
    'last_step' => 4,
    'slo_no_registrasi' => $request->no_registrasi_slo,
    'slo_no_sertifikat' => $request->no_sertifikat_slo,
    'last_saved_at' => now(),
]);
```

---

## 5. In `storeStep5()` method (FINAL SUBMISSION)

**Add at the beginning**:
```php
use App\Enums\PermohonanStatus;

$draftId = session('td_draft_id');
if (!$draftId) {
    return redirect()->route('tambah-daya.step1')
        ->withErrors(['global' => 'Session expired. Please start again.']);
}

$sr = ServiceRequest::findOrFail($draftId);
```

**REPLACE** the final update (after all validations):
```php
// After saving photos and all data

$sr->update([
    'is_draft' => false,
    'status' => PermohonanStatus::DITERIMA_PLN, // First processing status
    'submitted_at' => now(),
    'nomor_permohonan' => 'REQ-' . date('Ymd') . '-' . str_pad($sr->id, 6, '0', STR_PAD_LEFT),
    'last_step' => 5,
]);

// Clear draft session
session()->forget('td_draft_id');

// Redirect to success page
return redirect()->route('monitoring')
    ->with('success', 'Permohonan berhasil diajukan! Nomor permohonan: ' . $sr->nomor_permohonan);
```

---

## 6. Resume Draft Route (if not exists)

**In `routes/web.php`**, add:
```php
Route::get('/pelanggan/permohonan/{id}/resume', [TambahDayaController::class, 'resume'])
    ->name('tambah-daya.resume');
```

**In `TambahDayaController.php`**, add method:
```php
public function resume($id)
{
    $sr = ServiceRequest::where('submitter_user_id', Auth::id())
        ->where('is_draft', true)
        ->findOrFail($id);
    
    // Store draft ID in session
    session(['td_draft_id' => $sr->id]);
    
    // Redirect to last step + 1 (or last step if it's step 5)
    $nextStep = min($sr->last_step + 1, 5);
    return redirect()->route('tambah-daya.step' . $nextStep);
}
```

---

## Testing Checklist

After implementing:

1. **Create Draft**:
   - Start new permohonan
   - Fill step 1, click "Simpan & Lanjutkan"
   - Check `/monitoring?tab=waiting` - draft should appear

2. **Resume Draft**:
   - Close browser or logout
   - Login again
   - Go to `/monitoring?tab=waiting`
   - Click "Lanjutkan" on draft
   - Should resume at correct step

3. **Complete Submission**:
   - Complete all 5 steps
   - Check `/monitoring?tab=processing` - should appear there
   - Draft should disappear from waiting tab

4. **Multiple Drafts**:
   - Create draft A, complete step 1-2
   - Start new permohonan (draft B)
   - Both should appear in waiting tab
   - Resuming each should work independently

---

## Import Statement to Add

At the top of `TambahDayaController.php`:
```php
use App\Enums\PermohonanStatus;
```
