<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - PLN UP3 Kudus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4">

    <!-- Back Button -->
    <div class="absolute top-8 left-4 md:left-8 z-20">
        <a href="{{ route('pelanggan.login') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 backdrop-blur border border-white/60 shadow-sm hover:bg-white/90 transition text-slate-700 font-medium text-sm">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali Login
        </a>
    </div>

    <div class="w-full max-w-lg bg-white p-8 rounded-3xl shadow-xl border border-slate-100 relative">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-[#2F5AA8] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-unlock-alt"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Lupa Password</h1>
            <p class="text-slate-500 text-sm mt-1">Verifikasi identitas untuk reset password</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-green-800 text-sm">Permintaan Terkirim!</h4>
                    <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @error('global')
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                <p class="text-red-700 text-sm">{{ $message }}</p>
            </div>
        @enderror

        <form action="{{ route('pelanggan.forgot-password.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. NAMA LENGKAP -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <div class="flex gap-2">
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none text-sm"
                           placeholder="Sesuai nama di akun">
                    <button type="button" onclick="verifyField('nama')" 
                            class="px-4 py-2 bg-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-300 transition text-sm">
                        Verifikasi
                    </button>
                </div>
                <div id="status-nama" class="hidden mt-2 text-xs flex items-center gap-1 font-medium"></div>
            </div>

            <!-- 2. EMAIL -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Terdaftar</label>
                <div class="flex gap-2">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none text-sm"
                           placeholder="Contoh: nama@domain.com">
                    <button type="button" onclick="verifyField('email')" 
                            class="px-4 py-2 bg-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-300 transition text-sm">
                        Verifikasi
                    </button>
                </div>
                <div id="status-email" class="hidden mt-2 text-xs flex items-center gap-1 font-medium"></div>
            </div>

            <!-- 3. NIK -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">NIK (16 Digit)</label>
                <div class="flex gap-2">
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16"
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none text-sm"
                           placeholder="16 Digit Angka">
                    <button type="button" onclick="verifyField('nik')" 
                            class="px-4 py-2 bg-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-300 transition text-sm">
                        Verifikasi
                    </button>
                </div>
                <div id="status-nik" class="hidden mt-2 text-xs flex items-center gap-1 font-medium"></div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="pt-4">
                <button type="submit" id="btn-submit" disabled
                        class="w-full py-3 px-4 bg-slate-300 text-white font-bold rounded-xl cursor-not-allowed transition-all shadow-none">
                    Kirim Permintaan Reset
                </button>
                <p class="text-xs text-center text-slate-400 mt-2">Tombol aktif jika semua data terverifikasi.</p>
            </div>
        </form>
    </div>

    <script>
        const verificationState = {
            nama: false,
            email: false,
            nik: false
        };

        const routes = {
            nama: "{{ route('pelanggan.forgot-password.verify-nama') }}",
            email: "{{ route('pelanggan.forgot-password.verify-email') }}",
            nik: "{{ route('pelanggan.forgot-password.verify-nik') }}"
        };

        async function verifyField(field) {
            const input = document.getElementById(field);
            const statusDiv = document.getElementById('status-' + field);
            const value = input.value;

            // Basic client validation
            if (!value) {
                showStatus(field, false, 'Field tidak boleh kosong');
                return;
            }

            // UI Loading
            statusDiv.classList.remove('hidden');
            statusDiv.className = 'mt-2 text-xs flex items-center gap-1 font-medium text-slate-500';
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';

            try {
                const response = await fetch(routes[field], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ [field]: value })
                });

                const data = await response.json();

                if (response.ok && data.exists) {
                    showStatus(field, true, data.message);
                    verificationState[field] = true;
                } else {
                    const msg = data.message || 'Data tidak ditemukan';
                    showStatus(field, false, msg);
                    verificationState[field] = false;
                }
            } catch (error) {
                console.error(error);
                showStatus(field, false, 'Gagal memverifikasi. Coba lagi.');
                verificationState[field] = false;
            }

            updateSubmitButton();
        }

        function showStatus(field, success, message) {
            const statusDiv = document.getElementById('status-' + field);
            statusDiv.classList.remove('hidden');
            
            if (success) {
                statusDiv.className = 'mt-2 text-xs flex items-center gap-1 font-medium text-green-600';
                statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
                
                // Readonly verified input
                // document.getElementById(field).setAttribute('readonly', true);
            } else {
                statusDiv.className = 'mt-2 text-xs flex items-center gap-1 font-medium text-red-500';
                statusDiv.innerHTML = '<i class="fas fa-times-circle"></i> ' + message;
            }
        }

        function updateSubmitButton() {
            const btn = document.getElementById('btn-submit');
            if (verificationState.nama && verificationState.email && verificationState.nik) {
                btn.disabled = false;
                btn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                btn.classList.add('bg-[#2F5AA8]', 'hover:bg-[#274C8E]', 'shadow-md', 'hover:shadow-lg');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                btn.classList.remove('bg-[#2F5AA8]', 'hover:bg-[#274C8E]', 'shadow-md', 'hover:shadow-lg');
            }
        }

        // Input listeners to reset verification if changed
        ['nama', 'email', 'nik'].forEach(field => {
            document.getElementById(field).addEventListener('input', () => {
                if (verificationState[field]) {
                    verificationState[field] = false;
                    document.getElementById('status-' + field).classList.add('hidden');
                    updateSubmitButton();
                }
            });
        });
    </script>
</body>
</html>
