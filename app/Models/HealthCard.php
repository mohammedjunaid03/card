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
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->status === 'active' && !$this->isExpired();
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
