<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Service;

class HospitalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with(['services'])
            ->where('status', 'active');

        // Filter by location
        if ($request->filled('location')) {
            $query->where('address', 'like', '%' . $request->location . '%');
        }

        // Filter by service
        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        // Filter by minimum discount
        if ($request->filled('min_discount')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('discount_percentage', '>=', $request->min_discount);
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $hospitals = $query->orderBy('name')->paginate(12);
        
        // Get all services for filter dropdown
        $services = Service::orderBy('name')->get();

        return view('user.hospitals.index', compact('hospitals', 'services'));
    }

    public function show($id)
    {
        $hospital = Hospital::with(['services'])
            ->where('id', $id)
            ->where('status', 'approved')
            ->firstOrFail();

        return view('user.hospitals.show', compact('hospital'));
    }
}
