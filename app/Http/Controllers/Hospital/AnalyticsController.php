<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use App\Models\Service;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $hospitalId = auth('hospital')->id();
        
        // Basic stats
        $totalPatients = PatientAvailment::where('hospital_id', $hospitalId)
            ->distinct('user_id')
            ->count('user_id');
            
        $totalServices = Service::where('hospital_id', $hospitalId)->count();
        
        $totalAvailments = PatientAvailment::where('hospital_id', $hospitalId)->count();
        
        $totalDiscount = PatientAvailment::where('hospital_id', $hospitalId)->sum('discount_amount');
        
        // Monthly trends
        $monthlyData = PatientAvailment::where('hospital_id', $hospitalId)
            ->selectRaw('MONTH(availment_date) as month, COUNT(*) as availments, SUM(discount_amount) as discounts')
            ->whereYear('availment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Service popularity
        $serviceStats = Service::where('hospital_id', $hospitalId)
            ->withCount('availments')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        return view('hospital.analytics.index', compact(
            'totalPatients',
            'totalServices', 
            'totalAvailments',
            'totalDiscount',
            'monthlyData',
            'serviceStats'
        ));
    }
}
