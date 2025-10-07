<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminHospitalManagement extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with(['services']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            if ($request->approval_status === 'approved') {
                $query->where('status', 'approved');
            } else {
                $query->where('status', '!=', 'approved');
            }
        }

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $hospitals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.hospitals.create', compact('services'));
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

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital created successfully.');
    }

    public function show($id)
    {
        $hospital = Hospital::with(['services', 'availments.user', 'availments.service'])
            ->findOrFail($id);

        return view('admin.hospitals.show', compact('hospital'));
    }

    public function edit($id)
    {
        $hospital = Hospital::with('services')->findOrFail($id);
        $services = Service::orderBy('name')->get();
        
        return view('admin.hospitals.edit', compact('hospital', 'services'));
    }

    public function update(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email,' . $id,
            'mobile' => 'required|string',
            'address' => 'required|string',
            'license_number' => 'required|string|unique:hospitals,license_number,' . $id,
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'status' => 'required|in:active,inactive,suspended',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $hospital->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'license_number' => $request->license_number,
            'status' => $request->status
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($hospital->logo) {
                Storage::disk('public')->delete($hospital->logo);
            }
            
            $logoPath = $request->file('logo')->store('hospital-logos', 'public');
            $hospital->update(['logo' => $logoPath]);
        }

        // Update services
        $hospital->services()->sync($request->services);

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital updated successfully.');
    }

    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);
        
        // Delete associated files
        if ($hospital->logo) {
            Storage::disk('public')->delete($hospital->logo);
        }
        if ($hospital->registration_document) {
            Storage::disk('private')->delete($hospital->registration_document);
        }
        if ($hospital->pan_document) {
            Storage::disk('private')->delete($hospital->pan_document);
        }
        if ($hospital->gst_document) {
            Storage::disk('private')->delete($hospital->gst_document);
        }

        $hospital->delete();

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital deleted successfully.');
    }

    public function approve(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function showContract($id)
    {
        $hospital = Hospital::findOrFail($id);
        return view('admin.hospitals.contract', compact('hospital'));
    }

    public function storeContract(Request $request, $id)
    {
        $request->validate([
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'terms_conditions' => 'required|string'
        ]);

        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'contract_start_date' => $request->contract_start_date,
            'contract_end_date' => $request->contract_end_date,
            'discount_percentage' => $request->discount_percentage,
            'terms_conditions' => $request->terms_conditions,
            'contract_status' => 'active'
        ]);

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Contract created successfully.');
    }

    public function updateContract(Request $request, $id)
    {
        $request->validate([
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after:contract_start_date',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'terms_conditions' => 'required|string'
        ]);

        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'contract_start_date' => $request->contract_start_date,
            'contract_end_date' => $request->contract_end_date,
            'discount_percentage' => $request->discount_percentage,
            'terms_conditions' => $request->terms_conditions
        ]);

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Contract updated successfully.');
    }

    public function renewContract(Request $request, $id)
    {
        $request->validate([
            'new_end_date' => 'required|date|after:today'
        ]);

        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'contract_end_date' => $request->new_end_date,
            'contract_status' => 'active'
        ]);

        return response()->json(['success' => true]);
    }

    public function terminateContract(Request $request, $id)
    {
        $request->validate([
            'termination_reason' => 'required|string'
        ]);

        $hospital = Hospital::findOrFail($id);
        
        $hospital->update([
            'contract_status' => 'terminated',
            'termination_reason' => $request->termination_reason,
            'terminated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}