<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to view services.');
        }
        
        $services = Service::where('hospital_id', $hospital->id)
            ->latest()
            ->paginate(20);

        return view('hospital.services.index', compact('services'));
    }

    public function create()
    {
        return view('hospital.services.create');
    }

    public function store(Request $request)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to create services.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage,
            'price' => $request->price,
            'hospital_id' => $hospital->id,
            'status' => 'active',
        ]);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show($id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to view service details.');
        }
        
        $service = Service::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        return view('hospital.services.show', compact('service'));
    }

    public function edit($id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to edit services.');
        }
        
        $service = Service::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        return view('hospital.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to update services.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $service = Service::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        $service->update($request->all());

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to delete services.');
        }
        
        $service = Service::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        $service->delete();

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function updateDiscount(Request $request, $id)
    {
        $hospital = auth('hospital')->user();
        
        // If no hospital is authenticated, redirect to login
        if (!$hospital) {
            return redirect()->route('hospital.login')
                ->with('error', 'Please login to update discounts.');
        }
        
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $service = Service::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        $service->update([
            'discount_percentage' => $request->discount_percentage,
        ]);

        return redirect()->back()
            ->with('success', 'Discount updated successfully.');
    }
}