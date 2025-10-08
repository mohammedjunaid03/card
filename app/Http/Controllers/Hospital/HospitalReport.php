<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use App\Models\Service;
use Illuminate\Http\Request;

class HospitalReport extends Controller
{
    public function index()
    {
        $hospitalId = auth('hospital')->id();
        
        $totalAvailments = PatientAvailment::where('hospital_id', $hospitalId)->count();
        $totalDiscount = PatientAvailment::where('hospital_id', $hospitalId)->sum('discount_amount');
        $totalRevenue = PatientAvailment::where('hospital_id', $hospitalId)->sum('final_amount');
        
        $serviceStats = Service::where('hospital_id', $hospitalId)
            ->withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->get();

        $monthlyStats = PatientAvailment::where('hospital_id', $hospitalId)
            ->selectRaw('MONTH(availment_date) as month, COUNT(*) as count, SUM(discount_amount) as total_discount')
            ->whereYear('availment_date', date('Y'))
            ->groupBy('month')
            ->get();

        return view('hospital.reports.index', compact(
            'totalAvailments',
            'totalDiscount', 
            'totalRevenue',
            'serviceStats',
            'monthlyStats'
        ));
    }
}