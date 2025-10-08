<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HealthCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_number',
        'qr_code_path',
        'pdf_path',
        'issued_date',
        'expiry_date',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Helper Methods
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->approval_status === 'approved' && !$this->isExpired();
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function daysUntilExpiry(): ?int
    {
        return $this->expiry_date
            ? now()->diffInDays($this->expiry_date, false)
            : null;
    }

    /**
     * Automatically mark card as expired if date passed
     */
    protected static function booted()
    {
        static::saving(function ($card) {
            if ($card->expiry_date && $card->expiry_date->isPast()) {
                $card->status = 'expired';
            }
        });
    }
}
