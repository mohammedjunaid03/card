<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HospitalsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $hospitals;

    public function __construct(Collection $hospitals)
    {
        $this->hospitals = $hospitals;
    }

    public function collection()
    {
        return $this->hospitals;
    }
    
    public function map($hospital): array
    {
        return [
            $hospital->id,
            $hospital->name,
            $hospital->email,
            $hospital->mobile,
            $hospital->address,
            $hospital->city,
            $hospital->state,
            $hospital->pincode,
            $hospital->license_number,
            $hospital->status,
            $hospital->contract_status,
            $hospital->created_at->format('Y-m-d H:i:s'),
        ];
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Hospital Name',
            'Email',
            'Mobile',
            'Address',
            'City',
            'State',
            'Pincode',
            'License Number',
            'Status',
            'Contract Status',
            'Registration Date',
        ];
    }
}
