@props(['req'])

@php
    $statusColors = [
        'DRAFT' => 'bg-slate-100 text-slate-600 border-slate-200',
        'SUBMITTED' => 'bg-blue-50 text-[#2F5AA8] border-blue-200',
        'APPROVED' => 'bg-green-50 text-green-700 border-green-200',
        'REJECTED' => 'bg-red-50 text-red-700 border-red-200',
    ];
    $colorClass = $statusColors[$req->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition group">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        
        <!-- Left Section: Basic Info -->
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-3 mb-1">
                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold border {{ $colorClass }}">
                    {{ $req->status }}
                </span>
                <span class="text-xs text-slate-400 font-medium">
                    {{ $req->nomor_permohonan ?? 'DRAFT' }}
                </span>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">
                {{ str_replace('_', ' ', $req->jenis_layanan) }}
            </h3>
            
            <div class="flex items-center gap-4 text-sm text-slate-500 mt-1">
                <div class="flex items-center gap-1.5" title="Nama Pemohon">
                    <i class="fas fa-user-circle text-slate-400"></i>
                    <!-- Use optional chaining or check constraints -->
                    {{ $req->applicant->nama_lengkap ?? $req->applicant_nik }}
                </div>
                <div class="flex items-center gap-1.5" title="Tanggal Pengajuan">
                    <i class="fas fa-calendar-alt text-slate-400"></i>
                    {{ $req->created_at->format('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Middle Section: Details -->
        <div class="flex flex-col gap-1 text-sm md:text-right">
             <div class="font-semibold text-slate-700">
                {{ number_format($req->daya_baru, 0, ',', '.') }} VA
             </div>
             <div class="text-slate-500 text-xs">
                {{ $req->lokasi_kecamatan }}, {{ $req->lokasi_kab_kota }}
             </div>
        </div>

        <!-- Right Section: Action -->
        <div class="md:pl-4 md:border-l md:border-slate-100 flex items-center">
            <button class="px-4 py-2 bg-slate-50 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-100 transition border border-slate-200 group-hover:border-slate-300">
                Detail
            </button>
        </div>
    </div>
</div>
