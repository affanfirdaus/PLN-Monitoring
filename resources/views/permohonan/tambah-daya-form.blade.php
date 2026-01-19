@extends('layouts.pelanggan')

@section('content')
<div class="max-w-2xl mx-auto text-center py-20">
    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-tools text-2xl"></i>
    </div>
    <h1 class="text-2xl font-bold text-slate-900 mb-2">Formulir Tambah Daya</h1>
    <p class="text-slate-500 mb-8">Halaman formulir pengajuan tambah daya sedang dalam pengembangan. Silakan kembali lagi nanti.</p>
    
    <a href="{{ route('monitoring') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition">
        Lihat Monitoring
    </a>
</div>
@endsection
