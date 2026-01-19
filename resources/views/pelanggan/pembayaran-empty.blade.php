@extends('layouts.pelanggan')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 mb-6">Pembayaran</h1>

<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-file-invoice-dollar text-3xl text-blue-400"></i>
    </div>
    <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Transaksi Pembayaran</h3>
    <p class="text-slate-500 max-w-md mx-auto mb-8 leading-relaxed">
        Anda belum memiliki tagihan pembayaran yang perlu diselesaikan. Tagihan akan muncul setelah permohonan layanan disetujui.
    </p>
    <a href="{{ route('landing') }}#permohonanLayanan" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/10">
        <i class="fas fa-plus-circle"></i>
        Ajukan Layanan Baru
    </a>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Info Card Mockup -->
    <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100">
        <h4 class="font-bold text-blue-900 mb-3">Metode Pembayaran Tersedia</h4>
        <ul class="space-y-2 text-sm text-blue-800/80">
            <li class="flex items-center gap-2"><i class="fas fa-check text-blue-500"></i> Virtual Account Bank (Mandiri, BNI, BRI, BCA)</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-blue-500"></i> E-Wallet (OVO, GoPay, Dana)</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-blue-500"></i> Minimarket (Indomaret, Alfamart)</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-blue-500"></i> Kantor Pos</li>
        </ul>
    </div>
</div>
@endsection
