# IMPLEMENTATION PLAN: Fitur Lupa Password dengan Verifikasi Per Field

## 📋 OVERVIEW

Membuat sistem "Lupa Password" untuk pelanggan dengan:
- ✅ Verifikasi per field (Nama, Email, NIK)
- ✅ Queue request ke Admin Layanan
- ✅ Admin approval & kirim email reset
- ✅ Proteksi spam & rate limiting

---

## 🗂️ FILES YANG AKAN DIBUAT

### **1. Migration**
- `database/migrations/YYYY_MM_DD_create_password_reset_requests_table.php`

### **2. Model**
- `app/Models/PasswordResetRequest.php`

### **3. Controller**
- `app/Http/Controllers/Auth/ForgotPasswordRequestController.php`

### **4. Views**
- `resources/views/auth/pelanggan/forgot-password.blade.php` (form lupa password)
- `resources/views/admin/password-reset-requests/index.blade.php` (admin panel)
- `resources/views/admin/password-reset-requests/show.blade.php` (detail request)

### **5. Routes**
- Add to `routes/web.php`

### **6. Email Template** (Optional - use Laravel default)
- `resources/views/emails/password-reset-admin.blade.php`

---

## 📊 DATABASE SCHEMA

### **Table: `password_reset_requests`**

```sql
CREATE TABLE password_reset_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    
    -- User Reference
    user_id BIGINT UNSIGNED NULL,
    
    -- Input Data (for audit)
    nama_input VARCHAR(255) NOT NULL,
    email_input VARCHAR(255) NOT NULL,
    nik_input VARCHAR(16) NOT NULL,
    
    -- Request Status
    status ENUM('pending', 'approved', 'rejected', 'sent') DEFAULT 'pending',
    request_token VARCHAR(64) UNIQUE NOT NULL,
    
    -- Admin Processing
    admin_notes TEXT NULL,
    processed_by BIGINT UNSIGNED NULL,
    processed_at TIMESTAMP NULL,
    
    -- Security
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    
    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Indexes
    INDEX(email_input),
    INDEX(status),
    INDEX(created_at),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY(processed_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 🎯 FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────┐
│ 1. PELANGGAN - Lupa Password Form                  │
├─────────────────────────────────────────────────────┤
│ Input: Nama, Email, NIK                             │
│ ↓ Verify each field (AJAX)                          │
│ ↓ All verified → Enable Submit                      │
│ ↓ Submit Request                                     │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 2. BACKEND - Store Request                          │
├─────────────────────────────────────────────────────┤
│ Validate: Email + NIK + Nama match                  │
│ Create record in password_reset_requests            │
│ Status: PENDING                                      │
│ Generate unique request_token                       │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 3. ADMIN - Review Queue                             │
├─────────────────────────────────────────────────────┤
│ View pending requests                                │
│ Verify identity                                      │
│ Actions:                                             │
│  - Approve → Send reset email                       │
│  - Reject → Add notes                               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 4. EMAIL - Password Reset Link                      │
├─────────────────────────────────────────────────────┤
│ Laravel Password Broker generates token             │
│ Email sent to customer                               │
│ Status → SENT                                        │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│ 5. PELANGGAN - Reset Password                       │
├─────────────────────────────────────────────────────┤
│ Click link → Laravel reset password page            │
│ Enter new password                                   │
│ Reset success → Login                                │
└─────────────────────────────────────────────────────┘
```

---

## 🔒 SECURITY & VALIDATION

### **Rate Limiting:**

```php
// routes/web.php
Route::middleware(['throttle:verify'])->group(function() {
    Route::post('/pelanggan/lupa-password/verify-email', ...);
    Route::post('/pelanggan/lupa-password/verify-nik', ...);
    Route::post('/pelanggan/lupa-password/verify-nama', ...);
});

Route::middleware(['throttle:submit-forgot'])->group(function() {
    Route::post('/pelanggan/lupa-password', ...);
});

// app/Providers/RouteServiceProvider.php
RateLimiter::for('verify', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip());
});

RateLimiter::for('submit-forgot', function (Request $request) {
    return Limit::perMinute(3)->by($request->ip());
});
```

### **Input Validation:**

```php
// Email verification
'email' => 'required|email|max:255'

// NIK verification
'nik' => 'required|digits:16'

// Nama verification
'nama' => 'required|string|max:255'
```

### **Privacy Protection:**

```json
// Response untuk verify - JANGAN bocorkan data sensitif
{
    "exists": true,
    "message": "Email terdaftar"
}

// BUKAN ini (bocor data):
{
    "exists": true,
    "message": "Email terdaftar atas nama Budi Santoso"
}
```

---

## 📱 UI/UX SPEC

### **A. Link di Login Page**

**Location:** `resources/views/auth/pelanggan/login.blade.php`

```html
<!-- After login button -->
<div class="text-center mt-4">
    <a href="{{ route('pelanggan.forgot-password') }}" 
       class="text-sm text-[#2F5AA8] hover:underline">
        Lupa password?
    </a>
</div>
```

### **B. Forgot Password Form**

**Layout:** Center card (sama seperti login)

**Fields:**
1. **Nama Lengkap**
   ```html
   <input type="text" id="nama" placeholder="Masukkan nama lengkap sesuai akun">
   <button onclick="verifyNama()">Verifikasi</button>
   <div id="nama-status" class="hidden"></div>
   ```

2. **Email**
   ```html
   <input type="email" id="email" placeholder="email@example.com">
   <button onclick="verifyEmail()">Verifikasi</button>
   <div id="email-status" class="hidden"></div>
   ```

3. **NIK**
   ```html
   <input type="text" id="nik" maxlength="16" placeholder="16 digit NIK">
   <button onclick="verifyNik()">Verifikasi</button>
   <div id="nik-status" class="hidden"></div>
   ```

**Submit Button:**
```html
<button id="btn-submit" disabled>
    Kirim Permintaan Reset
</button>

<!-- JS Logic -->
<script>
let namaVerified = false;
let emailVerified = false;
let nikVerified = false;

function updateSubmitButton() {
    const btn = document.getElementById('btn-submit');
    if (namaVerified && emailVerified && nikVerified) {
        btn.disabled = false;
        btn.classList.add('bg-blue-600');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-300');
    }
}
</script>
```

---

## 🔧 BACKEND IMPLEMENTATION

### **Controller Methods:**

```php
class ForgotPasswordRequestController extends Controller
{
    // Show form
    public function show()
    {
        return view('auth.pelanggan.forgot-password');
    }
    
    // Verify email
    public function verifyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $exists = User::where('email', $request->email)
            ->where('role', 'pelanggan')
            ->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email terdaftar' : 'Email tidak ditemukan'
        ]);
    }
    
    // Verify NIK
    public function verifyNik(Request $request)
    {
        $request->validate(['nik' => 'required|digits:16']);
        
        $exists = User::where('nik', $request->nik)
            ->where('role', 'pelanggan')
            ->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK terdaftar' : 'NIK tidak ditemukan'
        ]);
    }
    
    // Verify Nama
    public function verifyNama(Request $request)
    {
        $request->validate(['nama' => 'required|string']);
        
        $nama = trim($request->nama);
        
        $exists = User::where('role', 'pelanggan')
            ->whereRaw('UPPER(TRIM(name)) = ?', [strtoupper($nama)])
            ->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nama terdaftar' : 'Nama tidak ditemukan'
        ]);
    }
    
    // Submit request
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nik' => 'required|digits:16',
        ]);
        
        // Verify data gabungan
        $user = User::where('email', $request->email)
            ->where('nik', $request->nik)
            ->where('role', 'pelanggan')
            ->whereRaw('UPPER(TRIM(name)) = ?', [strtoupper(trim($request->nama))])
            ->first();
        
        if (!$user) {
            return back()->withErrors([
                'global' => 'Data akun tidak sesuai. Pastikan Nama, Email, dan NIK benar.'
            ])->withInput();
        }
        
        // Create request
        PasswordResetRequest::create([
            'user_id' => $user->id,
            'nama_input' => $request->nama,
            'email_input' => $request->email,
            'nik_input' => $request->nik,
            'status' => 'pending',
            'request_token' => Str::uuid(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', 
            'Permintaan reset password telah dikirim ke Admin Layanan. Silakan tunggu instruksi melalui email terdaftar.');
    }
}
```

---

## 👨‍💼 ADMIN PANEL

### **Routes:**

```php
Route::middleware(['auth', 'role:admin_layanan'])->prefix('admin')->group(function() {
    Route::get('/password-reset-requests', [AdminPasswordResetController::class, 'index'])
        ->name('admin.password-reset-requests.index');
    
    Route::get('/password-reset-requests/{id}', [AdminPasswordResetController::class, 'show'])
        ->name('admin.password-reset-requests.show');
    
    Route::post('/password-reset-requests/{id}/approve', [AdminPasswordResetController::class, 'approve'])
        ->name('admin.password-reset-requests.approve');
    
    Route::post('/password-reset-requests/{id}/reject', [AdminPasswordResetController::class, 'reject'])
        ->name('admin.password-reset-requests.reject');
});
```

### **Admin Controller:**

```php
public function approve($id)
{
    $request = PasswordResetRequest::findOrFail($id);
    
    // Generate reset token using Laravel Password Broker
    $token = Password::broker()->createToken($request->user);
    
    // Send email
    $request->user->sendPasswordResetNotification($token);
    
    // Update status
    $request->update([
        'status' => 'sent',
        'processed_by' => Auth::id(),
        'processed_at' => now(),
    ]);
    
    return back()->with('success', 'Email reset password telah dikirim ke pelanggan.');
}
```

---

## ✅ CHECKLIST IMPLEMENTASI

### **Phase 1: Database & Model**
- [ ] Create migration `password_reset_requests`
- [ ] Run migration
- [ ] Create model `PasswordResetRequest`

### **Phase 2: Routes & Controller**
- [ ] Add routes untuk forgot password
- [ ] Create `ForgotPasswordRequestController`
- [ ] Implement verify methods (email, nik, nama)
- [ ] Implement store method

### **Phase 3: Frontend - Pelanggan**
- [ ] Update login page dengan link "Lupa password?"
- [ ] Create view `forgot-password.blade.php`
- [ ] Implement AJAX verification
- [ ] Implement form submission

### **Phase 4: Admin Panel**
- [ ] Create admin routes
- [ ] Create `AdminPasswordResetController`
- [ ] Create view `index.blade.php` (list requests)
- [ ] Create view `show.blade.php` (detail & actions)

### **Phase 5: Testing**
- [ ] Test verify email (exists/not exists)
- [ ] Test verify NIK (exists/not exists)
- [ ] Test verify nama (exists/not exists)
- [ ] Test submit request (valid data)
- [ ] Test submit request (invalid data)
- [ ] Test admin approve → email sent
- [ ] Test pelanggan reset password
- [ ] Test rate limiting

---

## 🚀 NEXT STEPS

Saya akan mulai implement dengan urutan:

1. ✅ Create migration file
2. ✅ Create model
3. ✅ Create controller with all methods
4. ✅ Create views (form + admin)
5. ✅ Add routes
6. ✅ Update login page

**Estimated Time:** Sekitar 30-45 menit untuk complete implementation

**Ready to proceed?** Konfirmasi jika implementation plan ini sudah sesuai kebutuhan.
