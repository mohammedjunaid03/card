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
        $patients = User::whereHas('availments', function($query) {
            $query->where('hospital_id', auth('hospital')->id());
        })->with(['availments' => function($query) {
            $query->where('hospital_id', auth('hospital')->id());
        }])->paginate(20);

        return view('hospital.patients.index', compact('patients'));
    }

    public function show($id)
    {
        $patient = User::findOrFail($id);
        
        $availments = PatientAvailment::where('user_id', $id)
            ->where('hospital_id', auth('hospital')->id())
            ->with('service')
            ->latest()
            ->get();

        return view('hospital.patients.show', compact('patient', 'availments'));
    }
}