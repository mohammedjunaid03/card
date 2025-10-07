<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class HospitalManagementController extends Controller
{
    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('staff.hospitals.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'license_number' => 'required|string|unique:hospitals',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'registration_document' => 'required|file|mimes:pdf|max:5120',
            'pan_document' => 'required|file|mimes:pdf|max:5120',
            'gst_document' => 'required|file|mimes:pdf|max:5120'
        ]);

        $hospital = Hospital::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'license_number' => $request->license_number,
            'status' => 'pending',
            'registered_by' => auth()->id(),
            'registration_type' => 'staff',
            'password' => Hash::make('hospital123') // Default password for hospitals
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('hospital-logos', 'public');
            $hospital->update(['logo' => $logoPath]);
        }

        if ($request->hasFile('registration_document')) {
            $regPath = $request->file('registration_document')->store('hospital-documents', 'private');
            $hospital->update(['registration_document' => $regPath]);
        }

        if ($request->hasFile('pan_document')) {
            $panPath = $request->file('pan_document')->store('hospital-documents', 'private');
            $hospital->update(['pan_document' => $panPath]);
        }

        if ($request->hasFile('gst_document')) {
            $gstPath = $request->file('gst_document')->store('hospital-documents', 'private');
            $hospital->update(['gst_document' => $gstPath]);
        }

        // Attach services
        $hospital->services()->attach($request->services);

        return redirect()->route('staff.dashboard')
            ->with('success', 'Hospital registered successfully.');
    }
}