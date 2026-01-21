<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payload_json' => 'array',
        'last_saved_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantIdentity::class, 'applicant_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
