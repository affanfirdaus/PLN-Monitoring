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
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_draft' => 'boolean',
        'status' => \App\Enums\PermohonanStatus::class,
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

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('is_draft', true);
    }

    public function scopeProcessing($query)
    {
        return $query->where('is_draft', false)
            ->whereIn('status', array_map(fn($s) => $s->value, \App\Enums\PermohonanStatus::processing()))
            ->whereNull('cancelled_at')
            ->whereNull('completed_at');
    }

    public function scopeDone($query)
    {
        // Prioritize status enum for consistency
        // Note: Ideally, when completed_at is set, status should be SELESAI
        //       when cancelled_at is set, status should be DIBATALKAN_ADMIN
        return $query->where(function($q) {
            $q->whereIn('status', [
                \App\Enums\PermohonanStatus::SELESAI->value,
                \App\Enums\PermohonanStatus::DIBATALKAN_ADMIN->value
            ])
            // Fallback for legacy data during transition
            ->orWhereNotNull('completed_at')
            ->orWhereNotNull('cancelled_at');
        });
    }

    // Helper methods
    public function isDraft(): bool
    {
        return $this->is_draft === true;
    }

    public function isProcessing(): bool
    {
        return !$this->is_draft && $this->status->isProcessing();
    }

    public function isDone(): bool
    {
        return $this->completed_at !== null || $this->cancelled_at !== null;
    }
}
