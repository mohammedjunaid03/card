<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'is_active',
        'hospital_id',
        'discount_percentage',
        'price',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_services')
                    ->withPivot('discount_percentage', 'description', 'is_active')
                    ->withTimestamps();
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function patientAvailments()
    {
        return $this->hasMany(PatientAvailment::class);
    }

    public function availments()
    {
        return $this->hasMany(PatientAvailment::class);
    }
}
