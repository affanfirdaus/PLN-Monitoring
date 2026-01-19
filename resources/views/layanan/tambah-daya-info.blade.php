@extends('layouts.web')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('landing') }}#permohonanLayanan" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-2 inline-flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900">Informasi Permohonan Tambah Daya</h1>
        <p class="text-slate-600 mt-2 text-lg">Panduan lengkap pengajuan penambahan daya listrik untuk pelanggan PLN.</p>
    </div>

    <!-- Step by Step Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-list-ol text-blue-500"></i> Tahapan Pengajuan
            </h2>
            
            <div class="space-y-8 relative">
                <!-- Vertical Line -->
                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>

                <!-- Step 1 -->
                <div class="relative pl-12">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-blue-100 border-2 border-blue-500 text-blue-700 font-bold flex items-center justify-center text-sm z-10">1</div>
                    <h3 class="font-bold text-slate-900 text-lg">Persiapan Data Pelanggan</h3>
                    <p class="text-slate-500 mt-1">Siapkan ID Pelanggan (12 digit) atau Nomor Meter Anda. Pastikan nama pemohon sesuai dengan identitas (KTP).</p>
                </div>

                <!-- Step 2 -->
                <div class="relative pl-12">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 text-slate-500 font-bold flex items-center justify-center text-sm z-10">2</div>
                    <h3 class="font-bold text-slate-900 text-lg">Pilih Layanan & Daya Baru</h3>
                    <p class="text-slate-500 mt-1">Tentukan besaran daya baru yang diinginkan (misal: 900VA menjadi 1300VA) dan jenis peruntukan (Rumah Tangga/Bisnis).</p>
                </div>

                <!-- Step 3 -->
                <div class="relative pl-12">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 text-slate-500 font-bold flex items-center justify-center text-sm z-10">3</div>
                    <h3 class="font-bold text-slate-900 text-lg">Detail Lokasi</h3>
                    <p class="text-slate-500 mt-1">Konfirmasi alamat lokasi instalasi. Pastikan titik koordinat lokasi sudah sesuai untuk memudahkan petugas.</p>
                </div>

                <!-- Step 4 -->
                <div class="relative pl-12">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 text-slate-500 font-bold flex items-center justify-center text-sm z-10">4</div>
                    <h3 class="font-bold text-slate-900 text-lg">Data SLO (Sertifikat Laik Operasi)</h3>
                    <p class="text-slate-500 mt-1">Jika ada perubahan instalasi internal yang signifikan, Anda mungkin diminta memperbarui SLO.</p>
                </div>

                <!-- Step 5 -->
                <div class="relative pl-12">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 text-slate-500 font-bold flex items-center justify-center text-sm z-10">5</div>
                    <h3 class="font-bold text-slate-900 text-lg">Ringkasan & Biaya</h3>
                    <p class="text-slate-500 mt-1">Review ringkasan permohonan. Sistem akan mengestimasi Biaya Penyambungan (BP) yang harus dibayar.</p>
                </div>
            </div>
        </div>
        
        <!-- Action Footer -->
        <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            @guest
                <div class="flex-1">
                    <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800">
                        <i class="fas fa-lock mt-0.5"></i>
                        <div>
                            <strong>Login Diperlukan</strong><br>
                            Untuk melanjutkan proses permohonan ke tahap pengisian formulir, silakan login ke akun pelanggan Anda terlebih dahulu.
                        </div>
                    </div>
                </div>
                <!-- Login CTA for Guest -->
                <a href="{{ route('landing', ['need_login' => 1, 'focus' => 'hero_login', 'from' => 'layanan_info']) }}" 
                   class="whitespace-nowrap px-8 py-3 bg-[#2F5AA8] text-white font-bold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20 w-full md:w-auto text-center">
                    Login Sekarang
                </a>
            @else
                <!-- Logged In User CTA -->
                <p class="text-slate-600 text-sm hidden md:block">Akun Anda siap. Silakan lanjut ke formulir.</p>
                <a href="{{ route('permohonan.tambah-daya') }}" 
                   class="whitespace-nowrap px-8 py-3 bg-[#2F5AA8] text-white font-bold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20 w-full md:w-auto text-center flex items-center justify-center gap-2">
                    Lanjutkan Permohonan <i class="fas fa-arrow-right"></i>
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection
