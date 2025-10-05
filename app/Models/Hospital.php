<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hospital extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'address',
        'city',
        'state',
        'pincode',
        'logo_path',
        'registration_doc',
        'pan_doc',
        'gst_doc',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Mutator to hash password automatically
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Relationships
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'hospital_services')
                    ->withPivot('discount_percentage', 'description', 'is_active')
                    ->withTimestamps();
    }

    public function patientAvailments()
    {
        return $this->hasMany(PatientAvailment::class);
    }
}
