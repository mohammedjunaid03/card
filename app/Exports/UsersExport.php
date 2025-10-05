<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    /**
     * Requires a collection of User models, typically with the healthCard relationship loaded.
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
    * Return the data collection to be exported.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->users;
    }
    
    /**
     * Transform the data into the desired row format.
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->mobile,
            $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : 'N/A',
            $user->age,
            $user->gender,
            $user->blood_group,
            $user->status,
            $user->healthCard ? $user->healthCard->card_number : 'N/A',
            $user->healthCard && $user->healthCard->isActive() ? 'Yes' : 'No',
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Define the column headers for the report.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Mobile',
            'DOB',
            'Age',
            'Gender',
            'Blood Group',
            'Account Status',
            'Health Card ID',
            'Card Active',
            'Registration Date',
        ];
    }
}