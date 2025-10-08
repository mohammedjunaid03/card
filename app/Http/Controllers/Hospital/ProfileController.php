<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $hospital = auth('hospital')->user();
        return view('hospital.profile.show', compact('hospital'));
    }

    public function update(Request $request)
    {
        $hospital = auth('hospital')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'mobile' => 'required|string|max:15',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'license_number' => 'required|string|unique:hospitals,license_number,' . $hospital->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'email', 'mobile', 'address', 'city', 'state', 'pincode', 'license_number'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($hospital->logo_path && Storage::exists('public/' . $hospital->logo_path)) {
                Storage::delete('public/' . $hospital->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('hospital-logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        $hospital->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}