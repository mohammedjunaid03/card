<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'date_of_birth',
        'age',
        'gender',
        'address',
        'blood_group',
        'email',
        'mobile',
        'password',
        'aadhaar_path',
        'photo_path',
        'email_verified',
        'mobile_verified',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'email_verified' => 'boolean',
        'mobile_verified' => 'boolean',
    ];

    // Relationships
    public function healthCard()
    {
        return $this->hasOne(HealthCard::class);
    }

    public function availments()
    {
        return $this->hasMany(PatientAvailment::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'recipient');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    // Accessors & Mutators
    // Removed setPasswordAttribute mutator to prevent double encryption

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isBlocked()
    {
        return $this->status === 'blocked';
    }
}