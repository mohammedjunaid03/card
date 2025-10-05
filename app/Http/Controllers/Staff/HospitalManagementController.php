<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HospitalManagementController extends Controller
{
    public function create()
    {
        return view('staff.register-hospital');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email',
            'mobile' => 'required|digits:10',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|digits:6',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'registration_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pan_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gst_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('hospital-logos', 'public') : null;
        $registrationDocPath = $request->file('registration_doc')->store('hospital-docs', 'private');
        $panDocPath = $request->file('pan_doc')->store('hospital-docs', 'private');
        $gstDocPath = $request->hasFile('gst_doc') ? $request->file('gst_doc')->store('hospital-docs', 'private') : null;

        Hospital::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'logo_path' => $logoPath,
            'registration_doc' => $registrationDocPath,
            'pan_doc' => $panDocPath,
            'gst_doc' => $gstDocPath,
            'password' => $request->password,
            'status' => 'pending',
        ]);

        return redirect()->route('staff.dashboard')
                        ->with('success', 'Hospital registered successfully! Pending admin approval.');
    }
}
