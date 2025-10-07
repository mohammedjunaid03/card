<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'card_validity_years' => config('app.card_validity_years', 5),
            'otp_expiry_minutes' => config('app.otp_expiry_minutes', 10),
        ];
        
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'card_validity_years' => 'required|integer|min:1|max:10',
            'otp_expiry_minutes' => 'required|integer|min:5|max:60',
        ]);

        // Update configuration (in a real app, you'd store these in database)
        // For now, we'll just show success message
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
