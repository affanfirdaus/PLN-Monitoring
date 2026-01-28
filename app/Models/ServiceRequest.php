<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Enums\PermohonanStatus;
use App\Enums\PermohonanDetailStatus;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'jenis_layanan'];

    protected $casts = [
        'payload_json' => 'array',
        'last_saved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'status_changed_at' => 'datetime',
        'is_draft' => 'boolean',
        'status' => PermohonanStatus::class,
        'status_detail' => PermohonanDetailStatus::class,
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
            ->whereIn('status', array_map(fn($s) => $s->value, PermohonanStatus::processing()))
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
                PermohonanStatus::SELESAI->value,
                PermohonanStatus::DIBATALKAN_ADMIN->value
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

    /**
     * Transition status safely with validation and audit
     */
    public function transitionTo(
        PermohonanStatus $status,
        ?PermohonanDetailStatus $detail = null
    ) {
        // Validate detail against status
        if ($detail && !in_array($detail, $status->allowedDetails())) {
            throw new \DomainException("Invalid status detail '{$detail->value}' for status '{$status->value}'");
        }

        DB::transaction(function () use ($status, $detail) {
            $data = [
                'status' => $status,
                'status_detail' => $detail,
                'status_changed_at' => now(),
                'status_changed_by' => auth()->id(),
            ];

            // If status is SELESAI, set completed_at
            if ($status === PermohonanStatus::SELESAI && !$this->completed_at) {
                $data['completed_at'] = now();
            }

            $this->update($data);
        });
    }
}
