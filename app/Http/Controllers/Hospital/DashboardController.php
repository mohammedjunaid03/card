<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientAvailment;
use App\Models\Service;

class DashboardController extends Controller
{
    /**
     * Display the hospital dashboard.
     */
    public function index()
    {
        $hospital = auth()->guard('hospital')->user();
        
        // If no hospital is authenticated, show a basic dashboard or redirect to login
        if (!$hospital) {
            return view('hospital.dashboard', [
                'hospital' => null,
                'analytics' => [
                    'total_patients' => 0,
                    'total_availments' => 0,
                    'total_services' => 0,
                    'total_discount_given' => 0,
                    'recent_availments' => 0,
                    'monthly_availments' => 0,
                    'service_wise_data' => collect()
                ],
                'recentPatients' => collect()
            ]);
        }
        
        // Gather dashboard analytics
        $analytics = [
            'total_patients' => PatientAvailment::where('hospital_id', $hospital->id)
                ->distinct('user_id')
                ->count(),
            'total_availments' => PatientAvailment::where('hospital_id', $hospital->id)->count(),
            'total_services' => Service::where('hospital_id', $hospital->id)->count(),
            'total_discount_given' => PatientAvailment::where('hospital_id', $hospital->id)
                ->sum('discount_amount'),
            'recent_availments' => PatientAvailment::where('hospital_id', $hospital->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'monthly_availments' => PatientAvailment::where('hospital_id', $hospital->id)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        // Get recent patients (last 10 availments)
        $recentPatients = PatientAvailment::where('hospital_id', $hospital->id)
            ->with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get service-wise performance data
        $analytics['service_wise_data'] = PatientAvailment::where('hospital_id', $hospital->id)
            ->selectRaw('service_id, COUNT(*) as count, SUM(discount_amount) as total_discount')
            ->with('service')
            ->groupBy('service_id')
            ->orderBy('count', 'desc')
            ->get();
        
        return view('hospital.dashboard', compact('hospital', 'analytics', 'recentPatients'));
    }
}