<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class PatientAvailmentController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to view patient availments.');
        }
        
        $patients = User::whereHas('availments', function($query) use ($hospital) {
            $query->where('hospital_id', $hospital->id);
        })->with(['availments' => function($query) use ($hospital) {
            $query->where('hospital_id', $hospital->id);
        }])->paginate(20);

        return view('hospital.availments.index', compact('patients'));
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

    /**
     * Show the form for creating a new availment.
     */
    public function create()
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to create patient availments.');
        }
        
        // Get all services for this hospital
        $services = Service::where('hospital_id', $hospital->id)
            ->where('status', 'active')
            ->get();
        
        return view('hospital.availments.create', compact('services'));
    }

    /**
     * Store a newly created availment.
     */
    public function store(Request $request)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to create patient availments.');
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'availment_date' => 'required|date',
            'original_amount' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate discount amount and final amount
        $discountAmount = ($request->original_amount * $request->discount_percentage) / 100;
        $finalAmount = $request->original_amount - $discountAmount;

        PatientAvailment::create([
            'user_id' => $request->user_id,
            'hospital_id' => $hospital->id,
            'service_id' => $request->service_id,
            'availment_date' => $request->availment_date,
            'original_amount' => $request->original_amount,
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'completed',
        ]);

        return redirect()->route('hospital.availments.index')
            ->with('success', 'Patient availment recorded successfully.');
    }

    /**
     * Verify a health card for availment.
     */
    public function verifyCard(Request $request)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to verify health cards.');
        }
        
        if ($request->isMethod('post')) {
            $request->validate([
                'card_number' => 'required|string',
            ]);

            // Find user by health card number
            $user = User::whereHas('healthCard', function($query) use ($request) {
                $query->where('card_number', $request->card_number);
            })->with('healthCard')->first();

            if (!$user) {
                return back()->with('error', 'Health card not found.');
            }

            if ($user->healthCard->status !== 'active') {
                return back()->with('error', 'Health card is not active.');
            }

            // Get services for this hospital
            $services = Service::where('hospital_id', $hospital->id)
                ->where('status', 'active')
                ->get();

            return view('hospital.availments.create', compact('user', 'services'));
        }

        return view('hospital.availments.verify-card');
    }

    /**
     * Record a new availment.
     */
    public function recordAvailment(Request $request)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to record availments.');
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'availment_date' => 'required|date',
            'original_amount' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate discount amount and final amount
        $discountAmount = ($request->original_amount * $request->discount_percentage) / 100;
        $finalAmount = $request->original_amount - $discountAmount;

        PatientAvailment::create([
            'user_id' => $request->user_id,
            'hospital_id' => $hospital->id,
            'service_id' => $request->service_id,
            'availment_date' => $request->availment_date,
            'original_amount' => $request->original_amount,
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'status' => 'completed',
        ]);

        return redirect()->route('hospital.availments.index')
            ->with('success', 'Availment recorded successfully.');
    }
}