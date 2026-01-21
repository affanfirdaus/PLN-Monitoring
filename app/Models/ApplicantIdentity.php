<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'user_id',
        'no_meter',
        'id_pelanggan_12',
        'default_provinsi',
        'default_kab_kota',
        'default_kecamatan',
        'default_kelurahan',
        'default_rt',
        'default_rw',
        'default_alamat_detail',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'applicant_id');
    }
}
