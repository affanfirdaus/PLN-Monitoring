@php
    $no_container = true;
@endphp
@extends('layouts.web')

@section('content')
    <!-- B. BANNER STRIP -->
    <div class="relative w-full h-[110px] md:h-[140px] bg-cover bg-top border-b border-[#eef2f7]" 
         style="background-image: url('{{ asset('images/Hero-section.png') }}');">
        
        <!-- Back Button Top Left -->
        <div class="absolute top-6 left-6 z-20">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 shadow-sm hover:bg-white transition-all text-slate-700 font-medium text-sm">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- C. CONTAINER KONTEN UTAMA -->
    <div class="max-w-[1200px] mx-auto px-4 md:px-6 pt-7 md:pt-9 pb-12 w-full">
        
        <!-- B. SECTION "LOGIN AREA" -->
        <div class="flex flex-col items-start text-left mb-8 md:mb-10">
            <!-- BADGE -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#eaf2ff] text-[#1d4ed8] text-xs font-semibold tracking-wide mb-4">
                <span class="w-2 h-2 rounded-full bg-[#1d4ed8]"></span>
                LOGIN AREA
            </div>
            
            <!-- JUDUL -->
            <h1 class="text-[28px] md:text-[36px] font-extrabold leading-tight">
                <span class="text-[#0f172a]">Login sebagai</span>
                <span class="text-[#1d4ed8]">pegawai unit ?</span>
            </h1>
        </div>

        <!-- C. GRID KARTU ROLE -->
        <!-- Responsive Grid: 1 col (mobile) -> 2 cols (tablet) -> 3 cols (laptop) -> 5 cols (desktop) -->
        <!-- Note: User asked for >=1280px -> 5 cols. 1024-1279 -> 3 cols. 640-1023 -> 2 cols. <640 -> 1 col. -->
        <!-- C. GRID KARTU ROLE -->
        <!-- Responsive Grid: 1 col (mobile) -> 2 cols (tablet) -> 3 cols (laptop) -> 6 cols (desktop) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
            
            <!-- Admin Pelayanan -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-blue-100">
                    <i class="fas fa-headset text-[40px] text-blue-600"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Admin Pelayanan</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'admin_pelayanan']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

            <!-- Unit Survey -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-green-100">
                    <i class="fas fa-map-marked-alt text-[40px] text-green-600"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Unit Survey</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'unit_survey']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

            <!-- Unit Perencanaan -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-yellow-100">
                    <i class="fas fa-tools text-[40px] text-yellow-600"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Unit Perencanaan</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'unit_perencanaan']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

            <!-- Unit Konstruksi -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-orange-100">
                    <i class="fas fa-hard-hat text-[40px] text-orange-600"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Unit Konstruksi</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'unit_konstruksi']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

            <!-- Unit TE -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-purple-100">
                    <i class="fas fa-bolt text-[40px] text-purple-600"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Unit TE</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'unit_te']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

            <!-- Supervisor -->
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-[0_10px_25px_rgba(15,23,42,0.06)] hover:shadow-[0_14px_30px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all min-h-[220px] flex flex-col">
                <div class="h-24 flex items-center justify-center bg-slate-100">
                    <i class="fas fa-user-tie text-[40px] text-slate-500"></i>
                </div>
                <div class="px-4 pt-4 pb-4 text-center flex-1 flex flex-col justify-between">
                    <h3 class="text-[15px] font-extrabold text-slate-900 mb-4">Supervisor</h3>
                    <a href="{{ route('pegawai.login', ['role' => 'supervisor']) }}" class="w-full h-11 rounded-xl bg-[#1f3b8a] hover:bg-[#1b3276] text-white text-sm font-bold flex items-center justify-center transition focus:outline-none focus:ring-4 focus:ring-[rgba(37,99,235,0.25)]">
                        Login
                    </a>
                </div>
            </div>

        </div>


    </div>
@endsection
