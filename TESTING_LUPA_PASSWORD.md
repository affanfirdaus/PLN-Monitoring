# INSTRUKSI TESTING LUPA PASSWORD

## 1. Persiapan Data
Pastikan data dummy sudah ada (Budi, Siti, dll).
```bash
php artisan migrate:fresh --seed --class=DummyPelangganSeeder
```

## 2. Testing Flow Pelanggan

1. Buka Login Pelanggan: `/pelanggan/login` (atau klik "Masuk" dari landing page).
2. Lihat di bawah tombol "Login", harus ada link **"Lupa password?"**.
3. Klik link tersebut, verify masuk ke halaman `/pelanggan/lupa-password`.

**Skenario Verifikasi:**

A. **Data Ngawur**
   - Isi Nama: "Ngawur" → Klik Verifikasi → ❌ Merah "Nama tidak ditemukan" (AJAX)
   - Isi Email: "ngawur@mail.com" → Klik Verifikasi → ❌ Merah "Email tidak ditemukan"
   - Isi NIK: "123" → Klik Verifikasi → ❌ Merah "NIK tidak ditemukan"
   - Tombol "Kirim Permintaan" **DISABLED** (abu-abu).

B. **Data Valid (Contoh: Siti Aminah)**
   - Isi Nama: "Siti Aminah" (case insensitive) → Klik Verifikasi → ✅ Hijau "Nama terdaftar"
   - Isi Email: `pelanggan2@kudus.id` → Klik Verifikasi → ✅ Hijau "Email terdaftar"
   - Isi NIK: `3319010101010002` → Klik Verifikasi → ✅ Hijau "NIK terdaftar"
   - Tombol "Kirim Permintaan" **ENABLED** (biru).

C. **Submit Request**
   - Klik tombol "Kirim Permintaan Reset".
   - Loading sebentar...
   - Muncul alert sukses hijau: "Permintaan reset password telah dikirim..."

## 3. Testing Flow Admin

1. Login sebagai Admin (jika ada interface login) atau akses langsung route jika testing (middleware auth sudah dipasang, jadi harus login dulu).
   *Jika belum ada akun admin, create manual via tinker atau matikan middleware sementara untuk test.*
   
   Route: `GET /admin/password-reset-requests`
   
2. Anda akan melihat (JSON response jika view belum dibuat, atau Table jika view ada):
   - List request dengan status `pending` dari Siti Aminah.
   
3. **Approve Request**
   - Note ID request dari list (misal: ID 1).
   - Kirim POST request (bisa pakai Postman atau button form dummy):
     `POST /admin/password-reset-requests/1/approve`
   
4. **Cek Log Email**
   - Karena mail driver biasanya `log` di local, cek file:
     `storage/logs/laravel.log`
   - Cari "Reset Password Notification".
   - Copy link reset password dari log.

## 4. Testing Reset Password
1. Buka link reset password yang didapat dari log.
2. Link akan mengarah ke halaman reset password bawaan Laravel.
3. Masukkan email (`pelanggan2@kudus.id`) dan password baru.
4. Submit → Sukses reset.
5. Coba login ulang dengan password baru.

---

**Troubleshooting:**
- Jika tombol verifikasi tidak merespon: Cek Console Browser (F12) untuk error JS/Network.
- Jika error 419 Page Expired: Pastikan CSRF token ter-load.
- Jika error 429 Too Many Requests: Tunggu 1 menit (rate limit 10/menit).
