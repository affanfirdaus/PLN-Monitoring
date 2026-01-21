@extends('layouts.pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white/70 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-white/50">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Profil Saya</h2>
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-500 mb-1">Nama Lengkap</label>
                <div class="text-lg font-bold text-slate-800">{{ $profile->nama_lengkap ?? $user->name }}</div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-500 mb-1">NIK</label>
                <div class="text-lg font-bold text-slate-800">{{ $profile->nik ?? $user->nik ?? '-' }}</div>
                @if(empty($user->nik))
                    <p class="text-sm text-red-500 mt-1">Mohon lengkapi NIK Anda untuk melakukan permohonan.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-500 mb-1">Email</label>
                <div class="text-lg font-bold text-slate-800">{{ $user->email }}</div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('pelanggan.profile') }}" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
                Edit Profil
            </a>
        </div>
    </div>
</div>
@endsection
