@extends('layouts.pelanggan')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Monitoring Layanan</h1>
            <p class="text-slate-500 mt-1">Pantau status permohonan layanan listrik Anda</p>
        </div>
        <a href="{{ route('tambah-daya.step1') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20">
            <i class="fas fa-plus"></i> Ajukan Layanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3 animate-fade-in-down">
            <i class="fas fa-check-circle text-xl"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($requests as $req)
             @include('pelanggan.partials.request-card', ['req' => $req])
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200 border-dashed">
                <i class="fas fa-folder-open text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium">Belum ada permohonan layanan.</p>
                <a href="{{ route('tambah-daya.step1') }}" class="mt-4 inline-block text-[#2F5AA8] font-bold hover:underline">Mulai Permohonan Baru</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
