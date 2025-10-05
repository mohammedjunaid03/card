<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAvailment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospital_id',
        'service_id',
        'original_amount',
        'discount_percentage',
        'discount_amount',
        'final_amount',
        'availment_date',
        'notes',
    ];

    protected $casts = [
        'availment_date' => 'date',
        'original_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
