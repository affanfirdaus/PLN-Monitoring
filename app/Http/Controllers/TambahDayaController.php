<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\MasterPelanggan;
use App\Models\ApplicantIdentity;
use App\Models\ServiceRequest;
use App\Models\PelangganProfile;

class TambahDayaController extends Controller
{
    /**
     * Helper to get/init session wizard state
     */
    private function getWizardSession()
    {
        return Session::get('tambah_daya', [
            'current_step' => 1,
            'completed' => [1 => false, 2 => false, 3 => false],
            'for_whom' => null,
            'applicant_id' => null,
            'applicant_nik' => null,
            'submitter_user_id' => null,
            'lokasi' => [],
            'layanan' => [],
        ]);
    }

    /**
     * STEP 1: Pilih Pemohon
     */
    public function step1()
    {
        $wizard = $this->getWizardSession();
        $user = Auth::user();
        
        // Prepare view data
        $userProfile = null;
        if ($user->nik) {
             $userProfile = PelangganProfile::where('user_id', $user->id)->first();
             if(!$userProfile) {
                 // Fallback if profile not created yet but user has nik
                 $userProfile = new PelangganProfile();
                 $userProfile->nama_lengkap = $user->name;
                 $userProfile->nik = $user->nik;
             }
        }

        return view('pelanggan.tambah-daya.step1', compact('wizard', 'user', 'userProfile'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'for_whom' => 'required|in:self,other',
            'nik_other' => 'required_if:for_whom,other|nullable|digits:16',
        ]);

        $user = Auth::user();
        $wizard = $this->getWizardSession();

        if ($request->for_whom === 'self') {
            // Validation: User must have NIK
            if (empty($user->nik)) {
                return back()->withErrors(['nik' => 'Akun Anda belum memiliki NIK. Silakan lengkapi profil terlebih dahulu.']);
            }

            // Upsert Applicant Identity for Self
            $applicant = ApplicantIdentity::updateOrCreate(
                ['nik' => $user->nik],
                [
                    'nama_lengkap' => $user->name, // Or from profile if available
                    'user_id' => $user->id,
                ]
            );

            // Also ensure PelangganProfile exists (per requirements)
            PelangganProfile::updateOrCreate(
                ['user_id' => $user->id, 'nik' => $user->nik],
                ['nama_lengkap' => $user->name]
            );

            $wizard['for_whom'] = 'self';
            $wizard['applicant_id'] = $applicant->id;
            $wizard['applicant_nik'] = $applicant->nik;
            $wizard['submitter_user_id'] = $user->id;

        } else {
            // Validation: Check Master Pelanggan
            $master = MasterPelanggan::where('nik', $request->nik_other)->first();
            if (!$master) {
                return back()->withErrors(['nik_other' => 'NIK tidak ditemukan dalam data pelanggan PLN.']);
            }

            // Upsert Applicant Identity for Other
            $applicant = ApplicantIdentity::updateOrCreate(
                ['nik' => $master->nik],
                [
                    'nama_lengkap' => $master->nama_lengkap,
                    'no_meter' => $master->no_meter,
                    'id_pelanggan_12' => $master->id_pelanggan_12,
                    // user_id stays null or whatever it was
                ]
            );

            $wizard['for_whom'] = 'other';
            $wizard['applicant_id'] = $applicant->id;
            $wizard['applicant_nik'] = $applicant->nik;
            $wizard['submitter_user_id'] = $user->id;
        }

        // Update Session
        $wizard['completed'][1] = true;
        if ($wizard['current_step'] < 2) {
            $wizard['current_step'] = 2;
        }
        Session::put('tambah_daya', $wizard);

        return redirect()->route('tambah-daya.step2');
    }

    /**
     * STEP 2: Detail Lokasi
     */
    public function step2()
    {
        $wizard = $this->getWizardSession();
        if (empty($wizard['applicant_id'])) {
            return redirect()->route('tambah-daya.step1');
        }

        // Config Data
        $wilayah = config('wilayah_kudus', []);
        
        // Pre-fill data logic
        $prefill = [];
        if ($wizard['for_whom'] === 'self') {
            $profile = PelangganProfile::where('user_id', Auth::id())->first();
            if ($profile) {
                // Use profile data if session is empty (first time visiting step 2)
                $prefill = empty($wizard['lokasi']) ? [
                    'provinsi' => $profile->provinsi ?? 'Jawa Tengah',
                    'kab_kota' => $profile->kab_kota ?? 'Kudus',
                    'kecamatan' => $profile->kecamatan,
                    'kelurahan' => $profile->kelurahan,
                    'rt' => $profile->rt,
                    'rw' => $profile->rw,
                    'alamat_detail' => $profile->alamat_detail,
                ] : $wizard['lokasi'];
            }
        } else {
            $prefill = $wizard['lokasi'];
        }

        return view('pelanggan.tambah-daya.step2', compact('wizard', 'wilayah', 'prefill'));
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'koordinat' => [
                'required',
                'regex:/^-?\d{1,2}\.\d{4,},\s?-?\d{1,3}\.\d{4,}$/'
            ],
            'provinsi' => 'required',
            'kab_kota' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'alamat_detail' => 'nullable|string',
        ], [
            'koordinat.required' => 'Titik koordinat wajib diisi.',
            'koordinat.regex' => 'Format tidak sesuai.',
        ]);

        // Parse Coordinates
        $parts = explode(',', $request->koordinat);
        $lat = trim($parts[0]);
        $lng = trim($parts[1]);

        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return back()->withErrors(['koordinat' => 'Koordinat tidak valid.']);
        }

        $wizard = $this->getWizardSession();
        $wizard['lokasi'] = $request->only(['provinsi', 'kab_kota', 'kecamatan', 'kelurahan', 'rt', 'rw', 'alamat_detail']);
        $wizard['lokasi']['lat'] = $lat;
        $wizard['lokasi']['lng'] = $lng;
        // Keep the string for display
        $wizard['lokasi']['koordinat_display'] = $request->koordinat;

        $wizard['completed'][2] = true;
        if ($wizard['current_step'] < 3) {
            $wizard['current_step'] = 3;
        }
        Session::put('tambah_daya', $wizard);

        return redirect()->route('tambah-daya.step3');
    }

    /**
     * STEP 3: Pilih Layanan & Daya
     */
    public function step3()
    {
        $wizard = $this->getWizardSession();
        if (empty($wizard['completed'][2])) {
            return redirect()->route('tambah-daya.step2');
        }

        $dayaOptions = config('daya_pln', []);

        return view('pelanggan.tambah-daya.step3', compact('wizard', 'dayaOptions'));
    }

    public function storeStep3(Request $request)
    {
        $dayaOptions = config('daya_pln', []);
        
        $request->validate([
            'daya_baru' => 'required|integer|in:' . implode(',', $dayaOptions),
            'jenis_produk' => 'required|in:PASCABAYAR,PRABAYAR',
            'peruntukan_koneksi' => 'required|in:RUMAH_TANGGA,BISNIS,INDUSTRI,SOSIAL,PEMERINTAH,RUMAH_IBADAH',
        ]);

        $wizard = $this->getWizardSession();

        // Create Service Request
        $serviceRequest = ServiceRequest::create([
            'jenis_layanan' => 'TAMBAH_DAYA',
            'status' => 'DRAFT',
            'submitter_user_id' => Auth::id(),
            'applicant_id' => $wizard['applicant_id'],
            'applicant_nik' => $wizard['applicant_nik'],
            
            // Lokasi
            'lokasi_provinsi' => $wizard['lokasi']['provinsi'],
            'lokasi_kab_kota' => $wizard['lokasi']['kab_kota'],
            'lokasi_kecamatan' => $wizard['lokasi']['kecamatan'],
            'lokasi_kelurahan' => $wizard['lokasi']['kelurahan'],
            'lokasi_rt' => $wizard['lokasi']['rt'],
            'lokasi_rw' => $wizard['lokasi']['rw'],
            'lokasi_detail_tambahan' => $wizard['lokasi']['alamat_detail'] ?? null,
            'koordinat_lat' => $wizard['lokasi']['lat'],
            'koordinat_lng' => $wizard['lokasi']['lng'],

            // Layanan
            'daya_baru' => $request->daya_baru,
            'jenis_produk' => $request->jenis_produk,
            'peruntukan_koneksi' => $request->peruntukan_koneksi,
        ]);

        // Generate No Permohonan (Simple format for now: REQ-YYYYMMDD-ID)
        $serviceRequest->update([
            'nomor_permohonan' => 'REQ-' . date('Ymd') . '-' . str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT)
        ]);

        // Update Session
        $wizard['completed'][3] = true;
        $wizard['current_step'] = 4;
        $wizard['service_request_id'] = $serviceRequest->id;
        Session::put('tambah_daya', $wizard);

        // Redirect to Monitoring (as Step 4 disabled)
        return redirect()->route('monitoring')->with('success', 'Permohonan Tambah Daya berhasil dibuat (Draft). Silakan pantau statusnya di sini.');
    }
    public function checkNik(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|numeric|digits:16'
            ]);

            $master = MasterPelanggan::where('nik', $request->nik)->first();
            
            if ($master) {
                return response()->json([
                    'status' => 'found',
                    'data' => [
                        'nama_lengkap' => $master->nama_lengkap,
                        'id_pelanggan_12' => $master->id_pelanggan_12,
                        'no_meter' => $master->no_meter
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'not_found',
                'message' => 'NIK tidak ditemukan dalam database PLN.'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format NIK tidak valid (Harus 16 digit angka).',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Check NIK Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }
}
