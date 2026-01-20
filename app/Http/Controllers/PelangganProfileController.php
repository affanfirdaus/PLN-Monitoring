<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PelangganProfile;
use App\Models\ApplicantIdentity;

class PelangganProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = PelangganProfile::where('user_id', $user->id)->first();
        
        return view('pelanggan.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|digits:16|unique:users,nik,'.$user->id,
            'provinsi' => 'nullable',
            'kab_kota' => 'nullable',
            'kecamatan' => 'nullable',
            'kelurahan' => 'nullable',
            'rt' => 'nullable',
            'rw' => 'nullable',
            'alamat_detail' => 'nullable'
        ]);

        // 1. Update User Table
        $user->update([
            'nik' => $request->nik,
            'name' => $request->nama_lengkap
        ]);

        // 2. Upsert PelangganProfile
        $profile = PelangganProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => $request->nik, // Ensure sync
                'nama_lengkap' => $request->nama_lengkap,
                'no_meter' => $request->no_meter,
                'id_pelanggan_12' => $request->id_pelanggan_12,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'alamat_detail' => $request->alamat_detail,
            ]
        );

        // 3. Linking Rule: Upsert ApplicantIdentity
        $applicant = ApplicantIdentity::where('nik', $request->nik)->first();

        if ($applicant) {
            // Jika ada: set user_id = users.id jika null
            if (is_null($applicant->user_id)) {
                $applicant->update(['user_id' => $user->id]);
            }
            // isi nama_lengkap jika kosong (edge case)
            if (empty($applicant->nama_lengkap)) {
                $applicant->update(['nama_lengkap' => $request->nama_lengkap]);
            }
        } else {
            // Jika tidak ada: create applicant_identities
            ApplicantIdentity::create([
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'user_id' => $user->id,
                // Copy address defaults from profile
                'default_provinsi' => $request->provinsi,
                'default_kab_kota' => $request->kab_kota,
                'default_kecamatan' => $request->kecamatan,
                'default_kelurahan' => $request->kelurahan,
                'default_rt' => $request->rt,
                'default_rw' => $request->rw,
                'default_alamat_detail' => $request->alamat_detail,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui dan disinkronisasi.');
    }
}
