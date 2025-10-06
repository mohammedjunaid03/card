<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Get public statistics for the homepage.
     */
    public function index()
    {
        $stats = [
            'users' => User::where('status', 'active')->count(),
            'hospitals' => Hospital::where('status', 'approved')->count(),
            'cards' => HealthCard::where('status', 'active')->count(),
            'savings' => PatientAvailment::sum('discount_amount') ?? 0,
        ];

        return response()->json($stats);
    }
}
