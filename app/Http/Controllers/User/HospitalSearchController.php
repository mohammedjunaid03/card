<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::where('status', 'active')->with('services');

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('service_id', $request->service);
            });
        }

        $hospitals = $query->paginate(12);

        return view('user.hospitals', compact('hospitals'));
    }

    public function show($id)
    {
        $hospital = Hospital::with(['services'])->findOrFail($id);
        return view('user.hospital-detail', compact('hospital'));
    }
}
