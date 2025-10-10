<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to view patients.');
        }
        
        $patients = User::whereHas('availments', function($query) use ($hospital) {
            $query->where('hospital_id', $hospital->id);
        })->with(['availments' => function($query) use ($hospital) {
            $query->where('hospital_id', $hospital->id);
        }])->paginate(20);

        return view('hospital.patients.index', compact('patients'));
    }

    public function show($id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to view patient details.');
        }
        
        $patient = User::findOrFail($id);
        
        $availments = PatientAvailment::where('user_id', $id)
            ->where('hospital_id', $hospital->id)
            ->with('service')
            ->latest()
            ->get();

        return view('hospital.patients.show', compact('patient', 'availments'));
    }
}