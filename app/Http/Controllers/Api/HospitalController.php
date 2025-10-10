<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function list(Request $request)
    {
        $hospitals = Hospital::where('status', 'active')->get();
        return response()->json(['hospitals' => $hospitals]);
    }

    public function detail($id)
    {
        $hospital = Hospital::with('services')->findOrFail($id);
        return response()->json(['hospital' => $hospital]);
    }
}
