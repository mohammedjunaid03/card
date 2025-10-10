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
        // Get real statistics from database
        $users = User::where('status', 'active')->count();
        $hospitals = Hospital::where('status', 'active')->count();
        $cards = HealthCard::where('status', 'active')->count();
        $savings = PatientAvailment::sum('discount_amount') ?? 0;
        
        // Ensure all values are positive integers
        $stats = [
            'users' => max(0, (int) $users),
            'hospitals' => max(0, (int) $hospitals),
            'cards' => max(0, (int) $cards),
            'savings' => max(0, (int) $savings),
        ];

        return response()->json($stats);
    }
}
