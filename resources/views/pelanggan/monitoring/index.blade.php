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

    {{-- Tabs Navigation --}}
    <x-monitoring.tabs :activeTab="$tab" :counts="$counts" />

    {{-- Request List --}}
    <div class="space-y-4">
        @forelse($requests as $req)
            @include('pelanggan.partials.request-card', ['req' => $req, 'tab' => $tab])
        @empty
            @if($tab === 'waiting')
                <div class="text-center py-20 bg-yellow-50 rounded-2xl border border-yellow-200 border-dashed">
                    <i class="fas fa-edit text-4xl text-yellow-300 mb-4"></i>
                    <p class="text-yellow-700 font-medium">Tidak ada draft permohonan.</p>
                    <p class="text-yellow-600 text-sm mt-2">Draft akan muncul saat Anda mulai mengisi permohonan baru dan belum menyelesaikannya.</p>
                </div>
            @elseif($tab === 'processing')
                <div class="text-center py-20 bg-blue-50 rounded-2xl border border-blue-200 border-dashed">
                    <i class="fas fa-spinner text-4xl text-blue-300 mb-4"></i>
                    <p class="text-blue-700 font-medium">Tidak ada permohonan yang sedang diproses.</p>
                    <a href="{{ route('tambah-daya.step1') }}" class="mt-4 inline-block text-[#2F5AA8] font-bold hover:underline">Ajukan Permohonan Baru</a>
                </div>
            @else
                <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200 border-dashed">
                    <i class="fas fa-check-circle text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 font-medium">Belum ada permohonan selesai.</p>
                </div>
            @endif
        @endforelse
    </div>
</div>
@endsection
