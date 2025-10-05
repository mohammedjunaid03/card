<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\HospitalService as HospitalServiceModel;
use App\Models\Service;

class HospitalService
{
    /**
     * Search hospitals with optional filters
     */
    public function searchHospitals($filters = [])
    {
        $query = Hospital::where('status', 'approved');

        if (isset($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (isset($filters['service_id'])) {
            $query->whereHas('services', function ($q) use ($filters) {
                $q->where('service_id', $filters['service_id']);
            });
        }

        if (isset($filters['min_discount'])) {
            $query->whereHas('services', function ($q) use ($filters) {
                $q->where('discount_percentage', '>=', $filters['min_discount']);
            });
        }

        return $query->with('services')->paginate(12);
    }

    /**
     * Add a service to a hospital
     */
    public function addService($hospitalId, $serviceId, $discountPercentage, $description = null)
    {
        return HospitalServiceModel::create([
            'hospital_id' => $hospitalId,
            'service_id' => $serviceId,
            'discount_percentage' => $discountPercentage,
            'description' => $description,
            'is_active' => true,
        ]);
    }

    /**
     * Update a hospital service
     */
    public function updateService($hospitalServiceId, $data)
    {
        $hospitalService = HospitalServiceModel::findOrFail($hospitalServiceId);
        $hospitalService->update($data);

        return $hospitalService;
    }

    /**
     * Verify a health card
     */
    public function verifyHealthCard($cardNumber)
    {
        $healthCard = \App\Models\HealthCard::where('card_number', $cardNumber)
                                             ->with('user')
                                             ->first();

        if (!$healthCard) {
            return [
                'valid' => false,
                'message' => 'Invalid card number',
            ];
        }

        if ($healthCard->status !== 'active') {
            return [
                'valid' => false,
                'message' => 'Card is not active',
            ];
        }

        if ($healthCard->isExpired()) {
            return [
                'valid' => false,
                'message' => 'Card has expired',
            ];
        }

        return [
            'valid' => true,
            'user' => $healthCard->user,
            'card' => $healthCard,
        ];
    }
}
