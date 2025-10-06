<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientAvailment;

class DashboardController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();

        // Get statistics
        $totalPatients = PatientAvailment::where('hospital_id', $hospital->id)
            ->distinct('health_card_id')
            ->count();

        $totalVisits = PatientAvailment::where('hospital_id', $hospital->id)->count();

        $totalDiscountGiven = PatientAvailment::where('hospital_id', $hospital->id)
            ->sum('discount_amount');

        $recentVisits = PatientAvailment::where('hospital_id', $hospital->id)
            ->with(['healthCard.user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('hospital.dashboard', compact(
            'hospital',
            'totalPatients',
            'totalVisits',
            'totalDiscountGiven',
            'recentVisits'
        ));
    }
}
