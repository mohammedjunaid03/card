<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceManagementController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(Request $request)
    {
        $query = Service::latest();
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status')) {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }
        
        $services = $query->paginate(20);
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
        ]);
        
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => true,
        ]);
        
        return back()->with('success', 'New service added successfully!');
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
        ]);
        
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'is_active' => $request->has('is_active'), // Checkbox value logic
        ]);
        
        return back()->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Prevent deletion if the service is actively used by any hospital
        if ($service->hospitals()->count() > 0) {
            return back()->with('error', 'Cannot delete service. It is currently mapped to one or more hospitals.');
        }

        $service->delete();
        
        return back()->with('success', 'Service deleted successfully!');
    }
}