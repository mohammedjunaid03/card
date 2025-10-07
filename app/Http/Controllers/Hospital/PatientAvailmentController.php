<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PatientAvailment;
use App\Models\HealthCard;
use App\Models\Service;
use Illuminate\Http\Request;

class PatientAvailmentController extends Controller
{
    public function index()
    {
        $availments = PatientAvailment::with(['user', 'service'])
            ->where('hospital_id', auth('hospital')->id())
            ->latest()
            ->paginate(20);

        return view('hospital.patient-availments.index', compact('availments'));
    }

    public function verifyCard(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string',
        ]);

        $cardNumber = $request->input('card_number');
        
        $healthCard = HealthCard::where('card_number', $cardNumber)
            ->where('status', 'active')
            ->with('user')
            ->first();

        if (!$healthCard) {
            return back()->withErrors(['card_number' => 'Invalid or inactive health card.']);
        }

        if ($healthCard->isExpired()) {
            return back()->withErrors(['card_number' => 'This health card has expired.']);
        }

        $services = Service::where('hospital_id', auth('hospital')->id())->get();

        return view('hospital.patient-availments.record', compact('healthCard', 'services'));
    }

    public function recordAvailment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        PatientAvailment::create([
            'user_id' => $request->user_id,
            'hospital_id' => auth('hospital')->id(),
            'service_id' => $request->service_id,
            'discount_amount' => $request->discount_amount,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'availment_date' => now(),
        ]);

        return redirect()->route('hospital.patient-availments.index')
            ->with('success', 'Patient availment recorded successfully.');
    }
}
