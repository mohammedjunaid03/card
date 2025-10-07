<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $staff = auth()->guard('staff')->user();
        
        // Get all availments for staff reports
        $query = PatientAvailment::with(['user', 'hospital', 'service']);

        if ($request->filled('from_date')) {
            $query->whereDate('availment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('availment_date', '<=', $request->to_date);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        $availments = $query->orderBy('created_at', 'desc')->paginate(20);

        $summary = [
            'total_patients' => PatientAvailment::distinct('user_id')->count(),
            'total_availments' => PatientAvailment::count(),
            'total_discount' => PatientAvailment::sum('discount_amount'),
            'total_hospitals' => \App\Models\Hospital::where('status', 'approved')->count(),
        ];

        $hospitals = \App\Models\Hospital::where('status', 'approved')->orderBy('name')->get();

        return view('staff.reports', compact('availments', 'summary', 'hospitals'));
    }
}
