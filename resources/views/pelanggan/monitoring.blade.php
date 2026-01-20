@extends('layouts.pelanggan')

@section('content')
<div class="max-w-6xl mx-auto py-10" x-data="{ tab: 'self' }">
    
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

    <!-- Tabs -->
    <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl mb-8 w-fit">
        <button @click="tab = 'self'" 
            :class="tab === 'self' ? 'bg-white text-[#2F5AA8] shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200">
            Permohonan Saya
        </button>
        <button @click="tab = 'submitted'" 
            :class="tab === 'submitted' ? 'bg-white text-[#2F5AA8] shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200">
            Yang Saya Ajukan
        </button>
    </div>

    <!-- Tab Content: Self -->
    <div x-show="tab === 'self'" x-transition.opacity class="space-y-4">
        @forelse($myRequests as $req)
            @include('pelanggan.partials.request-card', ['req' => $req])
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200 border-dashed">
                <i class="fas fa-folder-open text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium">Belum ada permohonan atas nama Anda (NIK {{ Auth::user()->nik ?? '-' }}).</p>
            </div>
        @endforelse
    </div>

    <!-- Tab Content: Submitted -->
    <div x-show="tab === 'submitted'" x-transition.opacity class="space-y-4" style="display: none;">
        @forelse($submittedRequests as $req)
             @include('pelanggan.partials.request-card', ['req' => $req])
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200 border-dashed">
                <i class="fas fa-paper-plane text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium">Anda belum pernah mengajukan permohonan untuk siapapun.</p>
            </div>
        @endforelse
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
