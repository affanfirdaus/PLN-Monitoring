@extends('layouts.pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
           <a href="{{ route('monitoring') }}" class="text-slate-500 hover:text-slate-800 font-semibold mb-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Detail Permohonan</h1>
            <p class="text-slate-500 text-sm">No: {{ $req->nomor_permohonan }}</p>
        </div>
        <span class="px-4 py-2 rounded-full border bg-blue-50 text-[#2F5AA8] border-blue-200 font-bold text-sm">
            {{ $req->status === 'SUBMITTED' ? 'Diajukan' : $req->status }}
        </span>
    </div>

    <!-- Data Sections -->
    <div class="space-y-6">
        <!-- 1. Data Pemohon -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data Pemohon</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="block text-slate-500 mb-1">Nama Lengkap</label>
                    <div class="font-semibold">{{ $req->applicant?->nama_lengkap ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">NIK</label>
                    <div class="font-semibold">{{ $req->applicant_nik }}</div>
                </div>
            </div>
        </div>

        <!-- 2. Lokasi -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Lokasi</h3>
            <div class="text-sm">
                <p class="font-semibold">{{ $req->lokasi_detail_tambahan }}</p>
                <p class="text-slate-600 mt-1">
                    RT {{ $req->lokasi_rt }} / RW {{ $req->lokasi_rw }},
                    {{ $req->lokasi_kelurahan }}, {{ $req->lokasi_kecamatan }},
                    {{ $req->lokasi_kab_kota }}, {{ $req->lokasi_provinsi }}
                </p>
                <div class="mt-4 p-3 bg-slate-50 rounded-lg text-xs text-slate-500">
                    Koordinat: {{ $req->koordinat_lat }}, {{ $req->koordinat_lng }}
                </div>
            </div>
        </div>
        
        <!-- 3. Layanan -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data Layanan</h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="block text-slate-500 mb-1">Jenis Layanan</label>
                    <div class="font-semibold">{{ $req->jenis_layanan }}</div>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Daya Baru</label>
                    <div class="font-semibold text-lg text-[#2F5AA8]">{{ number_format($req->daya_baru, 0, ',', '.') }} VA</div>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Produk</label>
                    <div class="font-semibold">{{ $req->jenis_produk }}</div>
                </div>
                <div>
                    <label class="block text-slate-500 mb-1">Peruntukan</label>
                    <div class="font-semibold">{{ $req->peruntukan_koneksi }}</div>
                </div>
            </div>
        </div>
        
        <!-- SLO Info (If Available) -->
        @if($req->slo_no_registrasi)
         <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data SLO</h3>
            <div class="text-sm">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div>
                        <label class="block text-slate-500 mb-1">No Registrasi</label>
                        <div class="font-mono text-slate-700">{{ $req->slo_no_registrasi }}</div>
                     </div>
                     <div>
                        <label class="block text-slate-500 mb-1">No Sertifikat</label>
                        <div class="font-mono text-slate-700">{{ $req->slo_no_sertifikat }}</div>
                     </div>
                 </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
