<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StaffExport implements FromCollection, WithHeadings, WithMapping
{
    protected $staff;

    public function __construct(Collection $staff)
    {
        $this->staff = $staff;
    }

    public function collection()
    {
        return $this->staff;
    }
    
    public function map($staffMember): array
    {
        return [
            $staffMember->id,
            $staffMember->name,
            $staffMember->email,
            $staffMember->mobile,
            $staffMember->role,
            $staffMember->hospital ? $staffMember->hospital->name : 'N/A',
            $staffMember->status,
            $staffMember->created_at->format('Y-m-d H:i:s'),
        ];
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Mobile',
            'Role',
            'Hospital',
            'Status',
            'Created Date',
        ];
    }
}
