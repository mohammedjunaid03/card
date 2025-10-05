<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\HospitalApproved;

class HospitalManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with('services');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                                       ->orWhere('email', 'like', "%{$search}%")
                                       ->orWhere('city', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $hospitals = $query->latest()->paginate(20);

        return view('admin.hospitals', compact('hospitals'));
    }

    public function approve($id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update(['status' => 'approved']);

        Mail::to($hospital->email)->send(new HospitalApproved($hospital));

        return back()->with('success', 'Hospital approved successfully!');
    }

    public function reject($id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update(['status' => 'rejected']);

        return back()->with('success', 'Hospital rejected!');
    }
}
