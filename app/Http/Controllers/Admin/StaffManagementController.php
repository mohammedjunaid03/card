<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Hospital;
use Illuminate\Http\Request;

class StaffManagementController extends Controller
{
    public function index()
    {
        $staff = Staff::with('hospital')->latest()->paginate(20);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $hospitals = Hospital::where('status', 'approved')->get();
        return view('admin.staff.create', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff',
            'mobile' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin_staff,receptionist,manager',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);

        Staff::create($request->all());

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function show($id)
    {
        $staff = Staff::with('hospital')->findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $hospitals = Hospital::where('status', 'approved')->get();
        return view('admin.staff.edit', compact('staff', 'hospitals'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'mobile' => 'required|string|max:15',
            'role' => 'required|in:admin_staff,receptionist,manager',
            'hospital_id' => 'required|exists:hospitals,id',
            'status' => 'required|in:active,inactive',
        ]);

        $staff->update($request->all());

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}