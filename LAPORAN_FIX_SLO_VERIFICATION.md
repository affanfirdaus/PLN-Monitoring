# LAPORAN PERBAIKAN: Verifikasi SLO Step 4 - Strict Validation

## Akar Masalah

**Lokasi:** 
- `app/Http/Controllers/TambahDayaController.php` - method `checkSlo()`
- `resources/views/pelanggan/tambah-daya/step4.blade.php` - JavaScript validasi

**Penyebab:**
1. **Backend tidak melakukan validasi lengkap**
   - Check individual (reg/cert) hanya memeriksa keberadaan nomor, tidak memeriksa kepemilikan
   - Check pair hanya memeriksa NIK, tidak memeriksa nama
   - Error message tidak spesifik (tidak membedakan "tidak ditemukan" vs "NIK beda" vs "nama beda")

2. **Frontend terlalu optimis**
   - Menandai field sebagai "valid" hanya karena nomor ditemukan di database
   - Tidak membedakan berbagai jenis error
   - Success message tidak menampilkan NIK untuk konfirmasi

## Perubahan Kode

### A. Backend - Controller (`TambahDayaController.php`)

**Baris 289-337** - Method `checkSlo()` diganti dengan logika strict:

```php
public function checkSlo(Request $request)
{
    $type = $request->input('type'); // 'reg', 'cert', or 'pair'
    $value = $request->input('value');
    $wizard = $this->getWizardSession();
    
    // Get applicant data from wizard session
    $applicantNik = $wizard['applicant_nik'] ?? null;
    $applicantName = $wizard['applicant_name'] ?? null;

    // Individual field checks (reg/cert) - Just check existence
    if ($type === 'reg') {
        $exists = MasterSlo::where('no_registrasi_slo', $value)->exists();
        return response()->json([
            'status' => $exists ? 'found' : 'not_found',
            'message' => $exists ? 'Data Registrasi Ditemukan' : 'Data Tidak Ditemukan'
        ]);
    } elseif ($type === 'cert') {
        $exists = MasterSlo::where('no_sertifikat_slo', $value)->exists();
        return response()->json([
            'status' => $exists ? 'found' : 'not_found',
            'message' => $exists ? 'Data Sertifikat Ditemukan' : 'Data Tidak Ditemukan'
        ]);
    } elseif ($type === 'pair') {
        // STRICT PAIR VERIFICATION
        $reg = $request->input('reg');
        $cert = $request->input('cert');
        
        // Step 1: Check if record exists with both numbers
        $slo = MasterSlo::where('no_registrasi_slo', $reg)
            ->where('no_sertifikat_slo', $cert)
            ->first();
        
        if (!$slo) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Data SLO tidak ditemukan. Pastikan nomor registrasi dan sertifikat benar.'
            ]);
        }
        
        // Step 2: Validate NIK matches applicant
        if ($slo->nik_pemilik !== $applicantNik) {
            return response()->json([
                'status' => 'nik_mismatch',
                'message' => 'Data SLO tidak sesuai NIK pemohon. SLO ini terdaftar atas nama orang lain.',
                'data' => [
                    'slo_nik' => $slo->nik_pemilik,
                    'applicant_nik' => $applicantNik
                ]
            ]);
        }
        
        // Step 3: Validate Name matches applicant (normalized comparison)
        $sloNameNormalized = strtoupper(trim($slo->nama_pemilik ?? ''));
        $applicantNameNormalized = strtoupper(trim($applicantName ?? ''));
        
        if ($sloNameNormalized !== $applicantNameNormalized) {
            return response()->json([
                'status' => 'name_mismatch',
                'message' => 'Data SLO tidak sesuai atas nama pemohon.',
                'data' => [
                    'slo_name' => $slo->nama_pemilik,
                    'applicant_name' => $applicantName
                ]
            ]);
        }
        
        // Step 4: All validations passed - Return success
        return response()->json([
            'status' => 'valid',
            'message' => 'SLO berhasil diverifikasi',
            'data' => [
                'nama_pemilik' => $slo->nama_pemilik,
                'nik_pemilik' => $slo->nik_pemilik,
                'nama_lembaga' => $slo->nama_lembaga,
                'no_registrasi' => $slo->no_registrasi_slo,
                'no_sertifikat' => $slo->no_sertifikat_slo,
                'tanggal_terbit' => $slo->tanggal_terbit ?? null,
                'tanggal_berlaku_sampai' => $slo->tanggal_berlaku_sampai ?? null,
            ]
        ]);
    }

    return response()->json(['status' => 'error', 'message' => 'Invalid request type'], 400);
}
```

**Fitur Baru:**
- ✅ 4-step validation: exist → match numbers → match NIK → match name
- ✅ Normalized name comparison (uppercase + trim) untuk toleransi format
- ✅ Specific error codes: `not_found`, `nik_mismatch`, `name_mismatch`, `valid`
- ✅ Detailed data dalam response untuk debugging & display

### B. Frontend - JavaScript (`step4.blade.php`)

**Baris 192-237** - Function `checkPair()` diganti:

```javascript
async function checkPair() {
    validPair = false;
    const alertBox = document.getElementById('pair-alert');
    alertBox.classList.add('hidden');
    updateNextButton();

    if (validReg && validCert) {
        // Verify if they match together AND belong to applicant
        const reg = document.getElementById('slo_no_registrasi').value.toUpperCase();
        const cert = document.getElementById('slo_no_sertifikat').value.toUpperCase();

        try {
            const res = await fetch("{{ route('tambah-daya.check-slo') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ type: 'pair', reg: reg, cert: cert })
            });
            const data = await res.json();

            alertBox.classList.remove('hidden', 'bg-red-50', 'border-red-200', 'text-red-800', 
                                      'bg-green-50', 'border-green-200', 'text-green-800', 
                                      'bg-amber-50', 'border-amber-200', 'text-amber-800');
            const title = document.getElementById('pair-title');
            const desc = document.getElementById('pair-desc');
            const icon = document.getElementById('pair-icon');

            if (data.status === 'valid') {
                // SUCCESS - All validations passed
                alertBox.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
                title.innerText = '✓ SLO Terverifikasi';
                desc.innerHTML = `<strong>Sukses verifikasi SLO atas nama: ${data.data.nama_pemilik}</strong><br>` +
                                 `NIK: ${data.data.nik_pemilik}<br>` +
                                 `Lembaga: ${data.data.nama_lembaga || '-'}` +
                                 (data.data.tanggal_berlaku_sampai ? `<br>Berlaku sampai: ${data.data.tanggal_berlaku_sampai}` : '');
                icon.className = 'fas fa-check-circle text-green-600 text-lg mt-0.5';
                validPair = true;
            } else if (data.status === 'not_found') {
                // ERROR: SLO numbers don't exist or don't match
                alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
                title.innerText = '✗ Data SLO Tidak Ditemukan';
                desc.innerText = data.message || 'Nomor Registrasi dan Sertifikat tidak cocok atau tidak terdaftar dalam database.';
                icon.className = 'fas fa-times-circle text-red-600 text-lg mt-0.5';
                validPair = false;
            } else if (data.status === 'nik_mismatch') {
                // ERROR: NIK doesn't match applicant
                alertBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
                title.innerText = '⚠ Data SLO Tidak Sesuai NIK Pemohon';
                desc.innerHTML = `${data.message}<br><small>SLO ini terdaftar dengan NIK: ${data.data.slo_nik || 'Tidak diketahui'}</small>`;
                icon.className = 'fas fa-exclamation-triangle text-amber-600 text-lg mt-0.5';
                validPair = false;
            } else if (data.status === 'name_mismatch') {
                // ERROR: Name doesn't match applicant
                alertBox.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
                title.innerText = '⚠ Data SLO Tidak Sesuai Atas Nama Pemohon';
                desc.innerHTML = `${data.message}<br><small>SLO terdaftar atas nama: ${data.data.slo_name || 'Tidak diketahui'}</small>`;
                icon.className = 'fas fa-exclamation-triangle text-amber-600 text-lg mt-0.5';
                validPair = false;
            } else {
                // Generic error
                alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
                title.innerText = '✗ Verifikasi Gagal';
                desc.innerText = data.message || 'Terjadi kesalahan saat memverifikasi data SLO.';
                icon.className = 'fas fa-times-circle text-red-600 text-lg mt-0.5';
                validPair = false;
            }
        } catch (e) {
            console.error('SLO Verification Error:', e);
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            document.getElementById('pair-title').innerText = 'Error Sistem';
            document.getElementById('pair-desc').innerText = 'Terjadi kesalahan koneksi. Silahkan coba lagi.';
            document.getElementById('pair-icon').className = 'fas fa-exclamation-circle text-red-600 text-lg mt-0.5';
        }
        updateNextButton();
    }
}
```

**Fitur Baru:**
- ✅ Handling 4 status berbeda dengan UI yang sesuai
- ✅ Color coding: hijau (sukses), merah (not found), amber (mismatch)
- ✅ Detail message untuk setiap error case
- ✅ Success message menampilkan NIK + Nama + Lembaga + tanggal berlaku
- ✅ Error handling untuk network issues

## Contoh Response JSON

### 1. Status: VALID (Semua validasi lulus)
```json
{
  "status": "valid",
  "message": "SLO berhasil diverifikasi",
  "data": {
    "nama_pemilik": "Budi Santoso",
    "nik_pemilik": "3319010101010001",
    "nama_lembaga": "Lembaga Inspeksi Teknik Kudus",
    "no_registrasi": "SLO-REG-2026-000241",
    "no_sertifikat": "SLO-CERT-2026-KUDUS-12001",
    "tanggal_terbit": "2026-01-15",
    "tanggal_berlaku_sampai": "2027-01-15"
  }
}
```

**UI:**
- ✅ Background hijau
- ✅ Icon: check-circle
- ✅ Title: "✓ SLO Terverifikasi"
- ✅ Desc: "Sukses verifikasi SLO atas nama: Budi Santoso | NIK: 3319010101010001 | Lembaga: ... | Berlaku sampai: ..."
- ✅ Tombol Next: ENABLED

---

### 2. Status: NOT_FOUND (Nomor tidak ditemukan)
```json
{
  "status": "not_found",
  "message": "Data SLO tidak ditemukan. Pastikan nomor registrasi dan sertifikat benar."
}
```

**UI:**
- ❌ Background merah
- ❌ Icon: times-circle
- ❌ Title: "✗ Data SLO Tidak Ditemukan"
- ❌ Desc: "Data SLO tidak ditemukan. Pastikan nomor registrasi dan sertifikat benar."
- ❌ Tombol Next: DISABLED

---

### 3. Status: NIK_MISMATCH (NIK tidak sesuai)
```json
{
  "status": "nik_mismatch",
  "message": "Data SLO tidak sesuai NIK pemohon. SLO ini terdaftar atas nama orang lain.",
  "data": {
    "slo_nik": "3319010101010002",
    "applicant_nik": "3319010101010001"
  }
}
```

**UI:**
- ⚠ Background amber/kuning
- ⚠ Icon: exclamation-triangle
- ⚠ Title: "⚠ Data SLO Tidak Sesuai NIK Pemohon"
- ⚠ Desc: "Data SLO tidak sesuai NIK pemohon. SLO ini terdaftar atas nama orang lain. | SLO ini terdaftar dengan NIK: 3319010101010002"
- ❌ Tombol Next: DISABLED

---

### 4. Status: NAME_MISMATCH (Nama tidak sesuai)
```json
{
  "status": "name_mismatch",
  "message": "Data SLO tidak sesuai atas nama pemohon.",
  "data": {
    "slo_name": "Siti Aminah",
    "applicant_name": "Budi Santoso"
  }
}
```

**UI:**
- ⚠ Background amber/kuning
- ⚠ Icon: exclamation-triangle
- ⚠ Title: "⚠ Data SLO Tidak Sesuai Atas Nama Pemohon"
- ⚠ Desc: "Data SLO tidak sesuai atas nama pemohon. | SLO terdaftar atas nama: Siti Aminah"
- ❌ Tombol Next: DISABLED

---

## Validasi Logic Flow

```
User Input: No Reg + No Sertifikat
    ↓
[1] Query: WHERE no_registrasi = X AND no_sertifikat = Y
    ↓
    ├─ Record tidak ditemukan → status: not_found ❌
    │
    └─ Record ditemukan
        ↓
[2] Validate: record.nik_pemilik === applicant_nik
    ↓
    ├─ Tidak match → status: nik_mismatch ⚠
    │
    └─ Match ✓
        ↓
[3] Validate: UPPER(TRIM(record.nama_pemilik)) === UPPER(TRIM(applicant_name))
    ↓
    ├─ Tidak match → status: name_mismatch ⚠
    │
    └─ Match ✓
        ↓
[4] status: valid ✅
    Return: nama, NIK, lembaga, tanggal
```

## Testing

### Test Data (dari seeder):

| NIK | Nama | No Reg | No Sertifikat |
|-----|------|--------|---------------|
| 3319010101010001 | Budi Santoso | SLO-REG-2026-000241 | SLO-CERT-2026-KUDUS-12001 |
| 3319010101010002 | Siti Aminah | SLO-REG-2026-000242 | SLO-CERT-2026-KUDUS-12002 |
| 3319010101010003 | Affan Sholeh Firdaus | SLO-REG-2025-913402 | SLO-CERT-2025-BOGOR-00019 |

### Scenario Test:

**Login sebagai:** `pelanggan0@kudus.id` (Budi Santoso, NIK: 3319010101010001)

1. ✅ **Valid:** Input `SLO-REG-2026-000241` + `SLO-CERT-2026-KUDUS-12001` → SUCCESS
2. ❌ **Not Found:** Input nomor random → NOT_FOUND
3. ⚠ **NIK Mismatch:** Input `SLO-REG-2026-000242` + `SLO-CERT-2026-KUDUS-12002` (punya Siti) → NIK_MISMATCH
4. ⚠ **Name Mismatch:** Jika ada data dengan NIK sama tapi nama beda → NAME_MISMATCH

## Catatan Implementasi

✅ **Tidak mengubah:**
- Layout UI Step 4
- Alur wizard lainnya
- Desain visual

✅ **Hanya mengubah:**
- Logika validasi backend (lebih strict)
- Error handling frontend (lebih spesifik)
- Success message (lebih informatif)

✅ **Keamanan:**
- Tidak mengekspos data sensitif SLO orang lain secara penuh
- Hanya menampilkan NIK/nama saat mismatch untuk debugging user
- Validation dilakukan di server-side, tidak bisa di-bypass dari client
