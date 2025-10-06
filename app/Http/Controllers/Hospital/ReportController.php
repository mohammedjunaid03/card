<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientAvailment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        // Total visits
        $totalVisits = PatientAvailment::where('hospital_id', $hospital->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Total discount given
        $totalDiscount = PatientAvailment::where('hospital_id', $hospital->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('discount_amount');

        // Service-wise breakdown
        $serviceBreakdown = PatientAvailment::where('hospital_id', $hospital->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('service')
            ->selectRaw('service_id, count(*) as count, sum(discount_amount) as total_discount')
            ->groupBy('service_id')
            ->get();

        return view('hospital.reports', compact(
            'totalVisits',
            'totalDiscount',
            'serviceBreakdown',
            'startDate',
            'endDate'
        ));
    }
}
