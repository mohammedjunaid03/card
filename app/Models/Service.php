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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_services')
                    ->withPivot('discount_percentage', 'description', 'is_active')
                    ->withTimestamps();
    }

    public function patientAvailments()
    {
        return $this->hasMany(PatientAvailment::class);
    }
}
