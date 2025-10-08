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
        'license_number',
        'logo_path',
        'registration_doc',
        'pan_doc',
        'gst_doc',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'contract_start_date',
        'contract_end_date',
        'contract_document_path',
        'contract_status',
        'contract_notes',
        'contract_created_at',
        'contract_updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'contract_created_at' => 'datetime',
        'contract_updated_at' => 'datetime',
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

    public function ownedServices()
    {
        return $this->hasMany(Service::class);
    }

    public function patientAvailments()
    {
        return $this->hasMany(PatientAvailment::class);
    }

    public function availments()
    {
        return $this->hasMany(PatientAvailment::class);
    }

    /**
     * Contract Management Methods
     */
    public function isContractActive()
    {
        if (!$this->contract_start_date || !$this->contract_end_date) {
            return false;
        }
        
        $today = now()->toDateString();
        return $this->contract_start_date <= $today && $this->contract_end_date >= $today;
    }

    public function isContractExpired()
    {
        if (!$this->contract_end_date) {
            return false;
        }
        
        return $this->contract_end_date < now()->toDateString();
    }

    public function getContractDaysRemaining()
    {
        if (!$this->contract_end_date) {
            return null;
        }
        
        $endDate = \Carbon\Carbon::parse($this->contract_end_date);
        $today = \Carbon\Carbon::now();
        
        return $today->diffInDays($endDate, false);
    }

    public function getContractStatusBadge()
    {
        if ($this->isContractExpired()) {
            return '<span class="badge bg-danger">Expired</span>';
        } elseif ($this->isContractActive()) {
            $daysRemaining = $this->getContractDaysRemaining();
            if ($daysRemaining <= 30) {
                return '<span class="badge bg-warning">Expires Soon (' . $daysRemaining . ' days)</span>';
            }
            return '<span class="badge bg-success">Active</span>';
        } else {
            return '<span class="badge bg-secondary">Not Started</span>';
        }
    }

    public function updateContractStatus()
    {
        if ($this->isContractExpired()) {
            $this->contract_status = 'expired';
        } elseif ($this->isContractActive()) {
            $this->contract_status = 'active';
        } else {
            $this->contract_status = 'pending_renewal';
        }
        
        $this->contract_updated_at = now();
        $this->save();
    }
}