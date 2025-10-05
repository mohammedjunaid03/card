<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $hospital = auth()->guard('hospital')->user();
        $services = $hospital->services;

        $query = PatientAvailment::where('hospital_id', $hospital->id)->with(['user', 'service']);

        if ($request->has('from_date')) {
            $query->whereDate('availment_date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('availment_date', '<=', $request->to_date);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $availments = $query->get();

        $summary = [
            'total_patients' => $availments->unique('user_id')->count(),
            'total_availments' => $availments->count(),
            'total_revenue' => $availments->sum('final_amount'),
            'total_discount' => $availments->sum('discount_amount'),
        ];

        return view('hospital.reports', compact('availments', 'summary', 'services'));
    }
}
