<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\PatientAvailment;
use App\Models\HealthCard;

class AvailmentController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();
        $availments = PatientAvailment::where('hospital_id', $hospital->id)
            ->with(['healthCard.user', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('hospital.availments.index', compact('availments'));
    }

    public function create()
    {
        $hospital = Auth::guard('hospital')->user();
        $services = $hospital->services;

        return view('hospital.availments.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'original_amount' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hospital = Auth::guard('hospital')->user();

        // Verify health card
        $healthCard = HealthCard::where('card_number', $request->card_number)
            ->where('status', 'active')
            ->first();

        if (!$healthCard) {
            return redirect()->back()
                ->with('error', 'Invalid or inactive health card.')
                ->withInput();
        }

        // Calculate discount
        $discountAmount = ($request->original_amount * $request->discount_percentage) / 100;
        $finalAmount = $request->original_amount - $discountAmount;

        PatientAvailment::create([
            'health_card_id' => $healthCard->id,
            'hospital_id' => $hospital->id,
            'service_id' => $request->service_id,
            'original_amount' => $request->original_amount,
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'notes' => $request->notes,
            'visit_date' => now(),
        ]);

        return redirect()->route('hospital.availments.index')
            ->with('success', 'Patient visit recorded successfully!');
    }

    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();
        $availment = PatientAvailment::where('hospital_id', $hospital->id)
            ->with(['healthCard.user', 'service'])
            ->findOrFail($id);

        return view('hospital.availments.show', compact('availment'));
    }
}
