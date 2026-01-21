# Laporan Perbaikan: NIK Field Kosong di Step 1 Tambah Daya

## Akar Masalah

**Lokasi:** `database/seeders/DummyPelangganSeeder.php`

**Penyebab:** 
Seeder untuk dummy user account **TIDAK** mengisi field `nik` di tabel `users`. Ketika Step 1 wizard mencoba menampilkan `{{ $user->nik }}`, hasilnya kosong karena kolom `nik` bernilai `null`.

### Bukti Masalah

**Sebelum perbaikan (baris 22-39):**
```php
$users = [
    ['name' => 'Pelanggan Coba', 'email' => 'pelanggan@coba.com'],
    ['name' => 'Budi Santoso',   'email' => 'pelanggan0@kudus.id'],
    // ... dst, TIDAK ADA field 'nik'
];

foreach ($users as $u) {
    User::updateOrCreate(
        ['email' => $u['email']],
        [
            'name'     => $u['name'],
            // nik TIDAK diisi!
            'role'     => 'pelanggan',
            'password' => Hash::make('Password123!'),
        ]
    );
}
```

## Verifikasi Yang Dilakukan

✅ **User model sudah benar:**
   - Field `nik` sudah ada di `$fillable` (line 28)
   - Field `nik` TIDAK ada di `$hidden` (hanya password & remember_token)

✅ **Controller sudah benar:**
   - `TambahDayaController@step1()` mengambil data dari `Auth::user()` (line 40)
   - Data user langsung di-pass ke view sebagai `$user`

✅ **View sudah benar:**
   - Blade template menampilkan `{{ $user->nik }}` (step1.blade.php line 68)
   - Conditional `@if(empty($user->nik))` berfungsi dengan benar (line 73)

❌ **Seeder SALAH:**
   - Tidak mengisi field `nik` saat create user dummy

## Solusi Yang Diterapkan

**File:** `database/seeders/DummyPelangganSeeder.php`

**Perubahan:**

1. **Tambahkan NIK ke array users** (baris 23-28):
```php
$users = [
    ['name' => 'Pelanggan Coba', 'email' => 'pelanggan@coba.com', 'nik' => null], // Tetap null untuk testing edge case
    ['name' => 'Budi Santoso',   'email' => 'pelanggan0@kudus.id', 'nik' => '3319010101010001'],
    ['name' => 'Siti Aminah',    'email' => 'pelanggan1@kudus.id', 'nik' => '3319010101010002'],
    ['name' => 'Affan Sholeh Firdaus', 'email' => 'pelanggan2@kudus.id', 'nik' => '3319010101010003'],
    ['name' => 'Rina Wati',      'email' => 'pelanggan3@kudus.id', 'nik' => '3319010101010004'],
    ['name' => 'Agus Salim',     'email' => 'pelanggan4@kudus.id', 'nik' => '3319010101010005'],
];
```

2. **Update logic updateOrCreate** (baris 35):
```php
User::updateOrCreate(
    ['email' => $u['email']],
    [
        'name'     => $u['name'],
        'nik'      => $u['nik'],  // ← TAMBAHAN BARIS INI
        'role'     => 'pelanggan',
        'password' => Hash::make('Password123!'),
    ]
);
```

## NIK Mapping dengan Master Pelanggan

NIK yang dipilih **match** dengan data di `master_pelanggan`:

| Email | Nama | NIK | ID Pelanggan | No Meter |
|-------|------|-----|--------------|----------|
| pelanggan0@kudus.id | Budi Santoso | 3319010101010001 | 512345678901 | 12345678901 |
| pelanggan1@kudus.id | Siti Aminah | 3319010101010002 | 512345678902 | 12345678902 |
| pelanggan2@kudus.id | Affan Sholeh Firdaus | 3319010101010003 | 512345678903 | 12345678903 |
| pelanggan3@kudus.id | Rina Wati | 3319010101010004 | 512345678904 | 12345678904 |
| pelanggan4@kudus.id | Agus Salim | 3319010101010005 | 512345678905 | 12345678905 |

## Langkah Testing

Jalankan ulang seeder:
```bash
php artisan db:seed --class=DummyPelangganSeeder
```

Atau jika ingin fresh start:
```bash
php artisan migrate:fresh --seed
```

Kemudian login dengan salah satu akun, misal:
- Email: `pelanggan0@kudus.id`
- Password: `Password123!`

Akses `/pelanggan/tambah-daya` dan pilih "Saya Sendiri".

**Expected Result:**
- NIK field terisi otomatis dengan `3319010101010001`
- Field readonly (tidak bisa diedit)
- Tidak muncul error "Mohon lengkapi NIK pada menu Profil"

## Catatan Tambahan

- **Pelanggan Coba** (`pelanggan@coba.com`) tetap tidak punya NIK untuk testing edge case ketika user belum melengkapi profil
- Tidak ada perubahan pada UI atau alur bisnis
- Hanya perbaikan data seeder
