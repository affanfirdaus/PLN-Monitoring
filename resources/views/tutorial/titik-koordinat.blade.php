@extends('layouts.pelanggan')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-8 md:p-12">
            
            <!-- Header -->
            <div class="mb-8 border-b border-slate-100 pb-6">
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Cara Mendapatkan Titik Koordinat di Google Maps (Laptop)</h1>
                <p class="text-slate-500 text-lg">
                    Koordinat yang dipakai di formulir adalah format: latitude, longitude (Decimal Degrees). <br>
                    Contoh: <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700">-6.8243847, 110.9038157</span>
                </p>
            </div>

            <!-- Steps -->
            <div class="space-y-8">
                
                {{-- STEP 1 --}}
                <div class="flex gap-6">
                  <div class="shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">1</div>
                  <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-900">Buka Google Maps</h3>
                    <p class="mt-1 text-slate-600">
                      Buka Google Maps di browser laptop Anda:
                      <a class="text-blue-700 hover:underline" href="https://www.google.com/maps">
                        https://www.google.com/maps
                      </a>
                    </p>

                    <div class="mt-4">
                      <img
                        src="{{ asset('images/Tutorial-1.png') }}"
                        alt="Tutorial 1 - Buka Google Maps"
                        class="w-full h-auto rounded-2xl border border-slate-200 shadow-sm"
                        loading="lazy"
                      >
                    </div>
                  </div>
                </div>

                {{-- STEP 2 --}}
                <div class="mt-10 flex gap-6">
                  <div class="shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">2</div>
                  <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-900">Cari Lokasi</h3>
                    <p class="mt-1 text-slate-600">
                      Cari lokasi rumah atau bangunan yang ingin Anda ajukan di kolom pencarian, atau geser peta manual.
                    </p>

                    <div class="mt-4">
                      <img
                        src="{{ asset('images/Tutorial-2.png') }}"
                        alt="Tutorial 2 - Cari Lokasi"
                        class="w-full h-auto rounded-2xl border border-slate-200 shadow-sm"
                        loading="lazy"
                      >
                    </div>
                  </div>
                </div>

                {{-- STEP 3 --}}
                <div class="mt-10 flex gap-6">
                  <div class="shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">3</div>
                  <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-900">Klik Kanan &amp; Salin Koordinat</h3>
                    <p class="mt-1 text-slate-600">
                      Klik kanan tepat di titik lokasi yang Anda mau. Di menu dropdown yang muncul, klik angka koordinat paling atas.
                    </p>

                    <div class="mt-4">
                      <img
                        src="{{ asset('images/Tutorial-3.png') }}"
                        alt="Tutorial 3 - Klik kanan & salin koordinat"
                        class="w-full h-auto rounded-2xl border border-slate-200 shadow-sm"
                        loading="lazy"
                      >
                    </div>

                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-blue-800">
                      Info: Setelah diklik, koordinat otomatis tersalin ke clipboard (copied). Anda tinggal menempelnya (paste) di formulir.
                    </div>
                  </div>
                </div>

                <!-- Step 4 (Apply) -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-[#2F5AA8] flex items-center justify-center font-bold text-lg">4</div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1">Paste di Formulir</h3>
                        <p class="text-slate-600">
                             Kembali ke halaman formulir PLN, lalu Paste (Ctrl+V) di kolom "Titik Koordinat".
                        </p>
                    </div>
                </div>

            </div>

             <!-- Note for Mobile -->
             <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                <p class="text-slate-400 text-sm">
                    <i class="fas fa-mobile-alt mr-1"></i> Catatan: Di HP (Aplikasi Google Maps), tahan lama pada lokasi (long press) hingga muncul pin merah, lalu salin koordinat di kolom pencarian atas.
                </p>
            </div>

            <!-- Action Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('tambah-daya.step2') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-[#2F5AA8] text-white font-bold rounded-xl hover:bg-[#274C8E] transition shadow-lg shadow-blue-900/20">
                    <i class="fas fa-arrow-left"></i> Kembali ke Formulir
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
