@extends('layouts.pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <!-- Stepper -->
    <x-stepper :currentStep="1" />

    <!-- Form Panel -->
    <div class="bg-white/70 backdrop-blur-lg rounded-3xl border border-white/50 shadow-xl overflow-hidden relative">
        <!-- Shine Effect -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-400/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="p-8 md:p-12 relative z-10">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Persiapan Data Pelanggan</h2>
            <p class="text-slate-500 mb-8">Siapa yang mengajukan permohonan tambah daya ini?</p>

            <form action="{{ route('tambah-daya.step1.store') }}" method="POST">
                @csrf
                
                <!-- Radio Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <!-- Option: Self -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="for_whom" value="self" class="peer sr-only" {{ old('for_whom', $wizard['for_whom']) === 'self' ? 'checked' : '' }} onchange="toggleSection('self')">
                        <div class="p-6 rounded-2xl border-2 border-slate-200 peer-checked:border-[#2F5AA8] peer-checked:bg-blue-50/50 hover:border-blue-100 transition-all text-center h-full flex flex-col justify-center items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-[#2F5AA8] flex items-center justify-center text-xl">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="font-bold text-slate-700 peer-checked:text-[#2F5AA8]">Saya Sendiri</span>
                        </div>
                        <div class="absolute top-4 right-4 text-[#2F5AA8] opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </label>

                    <!-- Option: Other -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="for_whom" value="other" class="peer sr-only" {{ old('for_whom', $wizard['for_whom']) === 'other' ? 'checked' : '' }} onchange="toggleSection('other')">
                        <div class="p-6 rounded-2xl border-2 border-slate-200 peer-checked:border-[#2F5AA8] peer-checked:bg-blue-50/50 hover:border-blue-100 transition-all text-center h-full flex flex-col justify-center items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="font-bold text-slate-700 peer-checked:text-[#2F5AA8]">Orang Lain</span>
                        </div>
                        <div class="absolute top-4 right-4 text-[#2F5AA8] opacity-0 peer-checked:opacity-100 transition-opacity">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </label>
                </div>

                <!-- Section: Self -->
                <div id="section-self" class="hidden space-y-6 animate-fade-in-down">
                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-500 mb-1">Nama Pemohon</label>
                                <input type="text" value="{{ $userProfile->nama_lengkap ?? $user->name }}" readonly class="w-full px-4 py-2 bg-slate-200 border border-slate-300 rounded-xl text-slate-600 font-medium focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-500 mb-1">NIK / No KTP</label>
                                <input type="text" value="{{ $user->nik }}" readonly class="w-full px-4 py-2 bg-slate-200 border border-slate-300 rounded-xl text-slate-600 font-medium focus:outline-none">
                                @if(empty($user->nik))
                                    <p class="text-xs text-red-500 mt-1"><i class="fas fa-exclamation-circle"></i> Mohon lengkapi NIK pada menu Profil terlebih dahulu.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Other -->
                <div id="section-other" class="hidden space-y-6 animate-fade-in-down">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIK Pemohon (16 Digit)</label>
                        <div class="flex gap-2">
                            <input type="text" name="nik_other" id="nik_other" maxlength="16" value="{{ old('nik_other') }}"
                                class="flex-1 px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition font-medium"
                                placeholder="Masukkan NIK Pelanggan...">
                            <button type="button" onclick="verifyNik()" class="px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-700 transition shadow-lg shadow-slate-900/10 whitespace-nowrap">
                                Verifikasi
                            </button>
                        </div>
                        <div id="nik-error" class="hidden mt-2 text-sm text-red-500 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> <span>NIK tidak ditemukan / tidak valid.</span>
                        </div>
                        <div id="nik-success" class="hidden mt-3 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                            <div class="mt-0.5 text-green-600"><i class="fas fa-check-circle text-lg"></i></div>
                            <div>
                                <p class="text-xs text-green-800 font-bold uppercase tracking-wide">Data Ditemukan</p>
                                <p class="text-sm font-bold text-slate-800 mt-1" id="verified-name">Nama Pelanggan</p>
                                <p class="text-xs text-slate-500" id="verified-id">ID Pel: -</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-10 flex justify-between pt-6 border-t border-slate-100">
                    <a href="{{ route('monitoring') }}" class="px-6 py-3 text-slate-500 font-semibold hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#2F5AA8] text-white font-bold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30">
                        Lanjutkan <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSection(val) {
        document.getElementById('section-self').classList.add('hidden');
        document.getElementById('section-other').classList.add('hidden');
        
        if (val === 'self') {
            document.getElementById('section-self').classList.remove('hidden');
        } else if (val === 'other') {
            document.getElementById('section-other').classList.remove('hidden');
        }
    }

    // Init state on load (for validation errors)
    document.addEventListener("DOMContentLoaded", () => {
        const selected = document.querySelector('input[name="for_whom"]:checked');
        if (selected) toggleSection(selected.value);
    });

    async function verifyNik() {
        const nik = document.getElementById('nik_other').value;
        const errorDiv = document.getElementById('nik-error');
        const successDiv = document.getElementById('nik-success');
        
        // Reset UI
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');

        if (nik.length !== 16) {
            errorDiv.querySelector('span').innerText = 'NIK harus 16 digit.';
            errorDiv.classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch("{{ route('tambah-daya.check-nik') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nik: nik })
            });

            const result = await response.json();

            if (response.ok && result.status === 'found') {
                document.getElementById('verified-name').innerText = result.data.nama_lengkap;
                document.getElementById('verified-id').innerText = 'ID Pel: ' + (result.data.id_pelanggan_12 || '-') + ' | No Meter: ' + (result.data.no_meter || '-');
                successDiv.classList.remove('hidden');
            } else {
                // Handle mixed errors (validation or 404)
                const msg = result.message || 'NIK tidak ditemukan dalam database PLN.';
                errorDiv.querySelector('span').innerText = msg;
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Verifikasi Error:', error);
            errorDiv.querySelector('span').innerText = 'Terjadi kesalahan sistem (Cek Console).';
            errorDiv.classList.remove('hidden');
        }
    }
</script>
@endsection
