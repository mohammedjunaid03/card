<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $hospital = Auth::guard('hospital')->user()->load('services');
        return view('hospital.profile', compact('hospital'));
    }

    public function update(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|regex:/^[0-9]{10}$/|unique:hospitals,mobile,' . $hospital->id,
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'address', 'city', 'state', 'pincode', 'mobile']);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $data['logo_path'] = $logoPath;
        }

        $hospital->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
