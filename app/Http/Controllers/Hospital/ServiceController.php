<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Models\HospitalService;

class ServiceController extends Controller
{
    public function index()
    {
        $hospital = Auth::guard('hospital')->user();
        $hospitalServices = $hospital->services()->get();
        $availableServices = Service::whereNotIn('id', $hospitalServices->pluck('id'))->get();

        return view('hospital.services.index', compact('hospitalServices', 'availableServices'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hospital = Auth::guard('hospital')->user();

        $hospital->services()->attach($request->service_id, [
            'discount_percentage' => $request->discount_percentage,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hospital = Auth::guard('hospital')->user();

        $hospital->services()->updateExistingPivot($id, [
            'discount_percentage' => $request->discount_percentage,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy($id)
    {
        $hospital = Auth::guard('hospital')->user();
        $hospital->services()->detach($id);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service removed successfully!');
    }
}
