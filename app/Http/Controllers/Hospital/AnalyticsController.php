<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientAvailment;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();

        // Monthly visits trend (last 12 months)
        $monthlyVisits = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = PatientAvailment::where('hospital_id', $hospital->id)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyVisits[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Top services
        $topServices = PatientAvailment::where('hospital_id', $hospital->id)
            ->with('service')
            ->selectRaw('service_id, count(*) as count')
            ->groupBy('service_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Total statistics
        $totalPatients = PatientAvailment::where('hospital_id', $hospital->id)
            ->distinct('health_card_id')
            ->count();

        $totalRevenue = PatientAvailment::where('hospital_id', $hospital->id)
            ->sum('final_amount');

        $totalDiscount = PatientAvailment::where('hospital_id', $hospital->id)
            ->sum('discount_amount');

        return view('hospital.analytics', compact(
            'monthlyVisits',
            'topServices',
            'totalPatients',
            'totalRevenue',
            'totalDiscount'
        ));
    }
}
