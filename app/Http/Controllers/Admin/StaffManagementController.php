<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffManagementController extends Controller
{
    public function index()
    {
        $staff = Staff::latest()->paginate(20);
        return view('admin.staff', compact('staff'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'mobile' => 'required|digits:10|unique:staff,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password,
            'status' => 'active',
        ]);
        
        return back()->with('success', 'Staff member added successfully!');
    }
    
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'mobile' => 'required|digits:10|unique:staff,mobile,' . $id,
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
        ]);
        
        $staff->update($request->only(['name', 'email', 'mobile', 'status']));
        
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
            $staff->save();
        }
        
        return back()->with('success', 'Staff member updated successfully!');
    }
    
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        
        return back()->with('success', 'Staff member deleted successfully!');
    }
}