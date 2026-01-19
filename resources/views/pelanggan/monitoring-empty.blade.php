@extends('layouts.pelanggan')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 mb-6">Monitoring Layanan</h1>

<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <!-- Icon Placeholder -->
        <i class="fas fa-folder-open text-3xl text-blue-400"></i>
    </div>
    <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Data Monitoring</h3>
    <p class="text-slate-500 max-w-md mx-auto mb-8 leading-relaxed">
        Saat ini Anda belum memiliki permohonan layanan yang dapat dipantau. Silakan ajukan layanan terlebih dahulu untuk melihat status monitoring.
    </p>
    <a href="{{ route('landing') }}#permohonanLayanan" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/10">
        <i class="fas fa-plus-circle"></i>
        Ajukan Layanan Baru
    </a>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Info Card Mockup -->
    <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100">
        <h4 class="font-bold text-blue-900 mb-3">Bagaimana cara memantau permohonan?</h4>
        <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800/80">
            <li>Ajukan permohonan layanan baru (Pasang Baru / Tambah Daya).</li>
            <li>Selesaikan proses administrasi awal.</li>
            <li>Status permohonan akan muncul otomatis di halaman ini.</li>
            <li>Anda akan mendapatkan notifikasi update status secara berkala.</li>
        </ol>
    </div>
</div>
@endsection
