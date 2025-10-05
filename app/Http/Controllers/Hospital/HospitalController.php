<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use App\Models\HospitalService;
use App\Models\Service;
use App\Services\HospitalService as HospitalServiceService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HospitalController extends Controller
{
    protected $reportService;
    protected $hospitalService;

    public function __construct(ReportService $reportService, HospitalServiceService $hospitalService)
    {
        $this->reportService = $reportService;
        $this->hospitalService = $hospitalService;
        $this->middleware('auth:hospital');
    }

    // ------------------ DASHBOARD ------------------
    public function dashboard()
    {
        $hospital = Auth::guard('hospital')->user();
        $analytics = $this->reportService->getHospitalAnalytics($hospital->id);
        $recentPatients = $hospital->availments()
                                   ->with(['user', 'service'])
                                   ->latest()
                                   ->take(10)
                                   ->get();

        return view('hospital.dashboard', compact('hospital', 'analytics', 'recentPatients'));
    }

    // ------------------ PROFILE ------------------
    public function showProfile()
    {
        $hospital = Auth::guard('hospital')->user();
        return view('hospital.profile', compact('hospital'));
    }

    public function updateProfile(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|digits:6',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $hospital->update($request->only(['name', 'mobile', 'city', 'state', 'pincode', 'address']));

        if ($request->hasFile('logo')) {
            if ($hospital->logo_path) {
                Storage::disk('public')->delete($hospital->logo_path);
            }
            $hospital->logo_path = $request->file('logo')->store('hospital-logos', 'public');
            $hospital->save();
        }

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $hospital->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $hospital->password = Hash::make($request->password);
            $hospital->save();
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    // ------------------ SERVICES ------------------
    public function listServices()
    {
        $hospital = Auth::guard('hospital')->user();
        $services = HospitalService::where('hospital_id', $hospital->id)->with('service')->get();
        $availableServices = Service::whereNotIn('id', $services->pluck('service_id'))
                                    ->where('is_active', true)
                                    ->get();

        return view('hospital.services', compact('services', 'availableServices'));
    }

    public function addService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $hospital = Auth::guard('hospital')->user();

        HospitalService::create([
            'hospital_id' => $hospital->id,
            'service_id' => $request->service_id,
            'discount_percentage' => $request->discount_percentage,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Service added successfully!');
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $hospitalService = HospitalService::findOrFail($id);
        $hospitalService->update([
            'discount_percentage' => $request->discount_percentage,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Service updated successfully!');
    }

    public function removeService($id)
    {
        $hospitalService = HospitalService::findOrFail($id);
        $hospitalService->delete();

        return back()->with('success', 'Service removed successfully!');
    }

    // ------------------ PATIENTS ------------------
    public function listPatients(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();
        $query = User::whereHas('availments', function ($q) use ($hospital) {
            $q->where('hospital_id', $hospital->id);
        })
        ->withCount('availments')
        ->withSum('availments', 'discount_amount')
        ->with('availments');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(20);

        return view('hospital.patients', compact('patients'));
    }

    public function showPatient($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $patient = User::with(['availments' => function ($q) use ($hospital) {
            $q->where('hospital_id', $hospital->id)
              ->with('service')
              ->latest();
        }])->findOrFail($id);

        return view('hospital.patient-detail', compact('patient'));
    }

    // ------------------ AVAILMENTS ------------------
    public function createAvailment(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $hospital = Auth::guard('hospital')->user();
        $services = $hospital->services;

        return view('hospital.create-availment', compact('user', 'services'));
    }

    public function storeAvailment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'original_amount' => 'required|numeric|min:0',
            'availment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $hospital = Auth::guard('hospital')->user();

        $hospitalService = HospitalService::where('hospital_id', $hospital->id)
                                          ->where('service_id', $request->service_id)
                                          ->firstOrFail();

        $discountPercentage = $hospitalService->discount_percentage;
        $discountAmount = ($request->original_amount * $discountPercentage) / 100;
        $finalAmount = $request->original_amount - $discountAmount;

        PatientAvailment::create([
            'user_id' => $request->user_id,
            'hospital_id' => $hospital->id,
            'service_id' => $request->service_id,
            'original_amount' => $request->original_amount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'availment_date' => $request->availment_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('hospital.dashboard')
                         ->with('success', 'Service availment recorded successfully');
    }

    // ------------------ CARD VERIFICATION ------------------
    public function verifyCard(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('hospital.verify-card');
        }

        $request->validate(['card_number' => 'required|string']);

        $result = $this->hospitalService->verifyHealthCard($request->card_number);

        if ($result['valid']) {
            return view('hospital.card-verified', [
                'user' => $result['user'],
                'card' => $result['card'],
            ]);
        }

        return back()->withErrors(['card_number' => $result['message']]);
    }
}
