<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('hospital_id', auth('hospital')->id())
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
            'hospital_id' => auth('hospital')->id(),
            'status' => 'active',
        ]);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show($id)
    {
        $service = Service::where('hospital_id', auth('hospital')->id())
            ->findOrFail($id);

        return view('hospital.services.show', compact('service'));
    }

    public function edit($id)
    {
        $service = Service::where('hospital_id', auth('hospital')->id())
            ->findOrFail($id);

        return view('hospital.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $service = Service::where('hospital_id', auth('hospital')->id())
            ->findOrFail($id);

        $service->update($request->all());

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = Service::where('hospital_id', auth('hospital')->id())
            ->findOrFail($id);

        $service->delete();

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function updateDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $service = Service::where('hospital_id', auth('hospital')->id())
            ->findOrFail($id);

        $service->update([
            'discount_percentage' => $request->discount_percentage,
        ]);

        return redirect()->back()
            ->with('success', 'Discount updated successfully.');
    }
}