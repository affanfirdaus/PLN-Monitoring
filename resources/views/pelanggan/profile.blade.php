@extends('layouts.pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Profil Saya</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pelanggan.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- User Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data Akun</h3>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap ?? $user->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                    @error('nama_lengkap') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">NIK (16 Digit)</label>
                    <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                    @error('nik') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                 <!-- Address Info -->
                 <div class="col-span-1 md:col-span-2">
                    <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data Alamat</h3>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $profile->provinsi ?? 'Jawa Tengah') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-500" readonly>
                </div>
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-2">Kabupaten/Kota</label>
                     <input type="text" name="kab_kota" value="{{ old('kab_kota', $profile->kab_kota ?? 'Kudus') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-500" readonly>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $profile->kecamatan ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kelurahan/Desa</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $profile->kelurahan ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $profile->rt ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $profile->rw ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Detail Alamat</label>
                    <textarea name="alamat_detail" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-blue-100 focus:border-[#2F5AA8] outline-none transition">{{ old('alamat_detail', $profile->alamat_detail ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-100">
                <button type="submit" class="px-8 py-3 bg-[#2F5AA8] text-white font-bold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
