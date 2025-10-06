<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientAvailment;

class PatientController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();

        $patients = PatientAvailment::where('hospital_id', $hospital->id)
            ->with(['healthCard.user'])
            ->select('health_card_id')
            ->distinct()
            ->paginate(15);

        return view('hospital.patients.index', compact('patients'));
    }

    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $patient = PatientAvailment::where('hospital_id', $hospital->id)
            ->where('health_card_id', $id)
            ->with(['healthCard.user', 'service'])
            ->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.');
        }

        $visits = PatientAvailment::where('hospital_id', $hospital->id)
            ->where('health_card_id', $id)
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hospital.patients.show', compact('patient', 'visits'));
    }
}
