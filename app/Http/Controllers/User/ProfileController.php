<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('web')->user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validator = Validator::make($request->all(), [
            'address' => 'nullable|string|max:500',
            'mobile' => 'nullable|string|regex:/^[0-9]{10}$/|unique:users,mobile,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'address' => $request->address,
            'mobile' => $request->mobile,
        ];

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/photos');
            $data['photo_path'] = $photoPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
